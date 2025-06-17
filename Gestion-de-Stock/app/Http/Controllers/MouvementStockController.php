<?php

namespace App\Http\Controllers;

use App\Models\MouvementStock;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MouvementStockController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }    /**
     * Display a listing of the stock movements.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $sort = $request->input('sort', 'date_desc'); // Default sort is date descending
        
        $query = MouvementStock::with(['produit', 'utilisateur']);
        
        if ($search) {
            $query->whereHas('produit', function($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%');
            });
        }
        
        if ($type) {
            $query->where('type', $type);
        }
        
        // Apply sorting based on the selected option
        switch ($sort) {
            case 'date_asc':
                $query->orderBy('date_cmd', 'asc');
                break;
            case 'quantite_desc':
                $query->orderBy('quantite', 'desc');
                break;
            case 'quantite_asc':
                $query->orderBy('quantite', 'asc');
                break;            case 'produit_asc':
                $query->join('produits', 'mouvement_stocks.produit_id', '=', 'produits.id')
                      ->orderBy('produits.nom', 'asc')
                      ->select('mouvement_stocks.*');
                break;
            case 'produit_desc':
                $query->join('produits', 'mouvement_stocks.produit_id', '=', 'produits.id')
                      ->orderBy('produits.nom', 'desc')
                      ->select('mouvement_stocks.*');
                break;
            default:
                $query->orderBy('date_cmd', 'desc');
                break;
        }
        
        $mouvements = $query->paginate(10)->withQueryString();
        
        return view('mouvements.index', compact('mouvements', 'search', 'type', 'sort'));
    }

    /**
     * Show the form for creating a new stock movement.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $produits = Produit::all();
        return view('mouvements.create', compact('produits'));
    }

    /**
     * Store a newly created stock movement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:entrée,sortie',
            'quantite' => 'required|integer|min:1',
            'produit_id' => 'required|exists:produits,id',
            'date_cmd' => 'required|date',
            'date_reception' => 'nullable|date|after_or_equal:date_cmd',
        ]);

        // Get the product
        $produit = Produit::findOrFail($request->produit_id);

        // Update product quantity
        if ($request->type === 'entrée') {
            // Add to stock
            $produit->quantite += $request->quantite;
        } else {
            // Remove from stock
            if ($produit->quantite < $request->quantite) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Quantité insuffisante en stock');
            }
            $produit->quantite -= $request->quantite;
        }

        // Save updated product quantity
        $produit->save();

        // Create stock movement
        MouvementStock::create([
            'type' => $request->type,
            'quantite' => $request->quantite,
            'date_cmd' => $request->date_cmd,
            'date_reception' => $request->date_reception,
            'produit_id' => $request->produit_id,
            'utilisateur_id' => Auth::id()
        ]);

        return redirect()->route('mouvements.index')
            ->with('success', 'Mouvement de stock enregistré avec succès');
    }

    /**
     * Display the specified stock movement.
     *
     * @param  \App\Models\MouvementStock  $mouvement
     * @return \Illuminate\View\View
     */
    public function show(MouvementStock $mouvement)
    {
        $mouvement->load(['produit', 'utilisateur']);
        return view('mouvements.show', compact('mouvement'));
    }

    /**
     * No edit functionality for stock movements to maintain data integrity.
     * Stock movements should be added or canceled, not edited.
     */

    /**
     * Cancel a stock movement (not delete, but reverse its effects).
     *
     * @param  \App\Models\MouvementStock  $mouvement
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(MouvementStock $mouvement)
    {
        // Only allow cancellation if the user has admin role
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('mouvements.index')
                ->with('error', 'Seuls les administrateurs peuvent annuler les mouvements de stock');
        }

        // Get the product
        $produit = $mouvement->produit;

        // Reverse the effect on the product quantity
        if ($mouvement->type === 'entrée') {
            // Remove from stock
            if ($produit->quantite < $mouvement->quantite) {
                return redirect()->route('mouvements.index')
                    ->with('error', 'Impossible d\'annuler ce mouvement car la quantité en stock est insuffisante');
            }
            $produit->quantite -= $mouvement->quantite;
        } else {
            // Add back to stock
            $produit->quantite += $mouvement->quantite;
        }

        // Save updated product quantity
        $produit->save();

        // Create a new counter movement
        $counterType = ($mouvement->type === 'entrée') ? 'sortie' : 'entrée';
        
        MouvementStock::create([
            'type' => $counterType,
            'quantite' => $mouvement->quantite,
            'date_cmd' => now(),
            'date_reception' => now(),
            'produit_id' => $mouvement->produit_id,
            'utilisateur_id' => Auth::id()
        ]);

        // Mark the original movement as canceled
        $mouvement->update([
            'canceled' => true
        ]);

        return redirect()->route('mouvements.index')
            ->with('success', 'Mouvement de stock annulé avec succès');
    }
}
