<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduitController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */    public function index()
    {
        $produits = Produit::with('categorie')->paginate(10);
        return view('produits.index', compact('produits'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Categorie::all();
        return view('produits.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'reference' => 'required|string|max:255|unique:produits',
            'quantite' => 'required|integer|min:0',
            'prix' => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id'
        ]);

        Produit::create([
            'nom' => $request->nom,
            'reference' => $request->reference,
            'quantite' => $request->quantite,
            'prix' => $request->prix,
            'categorie_id' => $request->categorie_id
        ]);

        return redirect()->route('produits.index')
            ->with('success', 'Produit créé avec succès');
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\View\View
     */
    public function show(Produit $produit)
    {
        $produit->load(['categorie', 'mouvementStocks' => function($query) {
            $query->orderBy('date_cmd', 'desc');
        }]);
        
        return view('produits.show', compact('produit'));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\View\View
     */
    public function edit(Produit $produit)
    {
        $categories = Categorie::all();
        return view('produits.edit', compact('produit', 'categories'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Produit $produit)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'reference' => 'required|string|max:255|unique:produits,reference,'.$produit->id,
            'quantite' => 'required|integer|min:0',
            'prix' => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id'
        ]);

        $produit->update([
            'nom' => $request->nom,
            'reference' => $request->reference,
            'quantite' => $request->quantite,
            'prix' => $request->prix,
            'categorie_id' => $request->categorie_id
        ]);

        return redirect()->route('produits.index')
            ->with('success', 'Produit mis à jour avec succès');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Produit $produit)
    {
        // Check if the product has stock movements
        if ($produit->mouvementStocks()->count() > 0) {
            return redirect()->route('produits.index')
                ->with('error', 'Impossible de supprimer ce produit car il a des mouvements de stock associés');
        }

        $produit->delete();

        return redirect()->route('produits.index')
            ->with('success', 'Produit supprimé avec succès');
    }
}
