<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\ExportableTrait;
use App\Exports\ProduitsExport;

class ProduitController extends Controller
{
    use ExportableTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }    /**
     * Display a listing of the products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $sort = $request->input('sort', 'nom_asc'); // Default sort is alphabetical
        
        $query = Produit::with('categorie');
          if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                  ->orWhere('reference', 'like', '%' . $search . '%');
            });
        }
        
        if ($category) {
            $query->where('categorie_id', $category);
        }
        
        // Apply sorting
        switch ($sort) {
            case 'nom_desc':
                $query->orderBy('nom', 'desc');
                break;
            case 'prix_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'quantite_asc':
                $query->orderBy('quantite', 'asc');
                break;
            case 'quantite_desc':
                $query->orderBy('quantite', 'desc');
                break;
            case 'categorie_asc':
                $query->join('categories', 'produits.categorie_id', '=', 'categories.id')
                      ->orderBy('categories.nom', 'asc')
                      ->select('produits.*');
                break;
            case 'categorie_desc':
                $query->join('categories', 'produits.categorie_id', '=', 'categories.id')
                      ->orderBy('categories.nom', 'desc')
                      ->select('produits.*');
                break;
            default: // nom_asc
                $query->orderBy('nom', 'asc');
                break;
        }
        
        $produits = $query->paginate(10)->appends(request()->query());
        $categories = \App\Models\Categorie::all();
        
        return view('produits.index', compact('produits', 'categories', 'search', 'category', 'sort'));
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
      public function exportExcel(Request $request)
    {
        return $this->handleExcelExport($request, ProduitsExport::class, 'Produits');
    }

    public function exportPdf(Request $request)
    {
        return $this->handlePdfExport($request, 'exports.produits-pdf', 'Produits', function($request, $allData) {
            $query = Produit::with('categorie');

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $query->where(function($q) use ($request) {
                    $q->where('nom', 'like', '%' . $request->search . '%')
                      ->orWhere('reference', 'like', '%' . $request->search . '%')
                      ->orWhereHas('categorie', function($subQ) use ($request) {
                          $subQ->where('nom', 'like', '%' . $request->search . '%');
                      });
                });
            }

            // Apply category filter
            if ($request->has('categorie') && !empty($request->categorie)) {
                $query->where('categorie_id', $request->categorie);
            }

            // Apply stock filter
            if ($request->has('stock') && !empty($request->stock)) {
                switch ($request->stock) {
                    case 'low':
                        $query->where('quantite', '<', 10);
                        break;
                    case 'medium':
                        $query->whereBetween('quantite', [10, 50]);
                        break;
                    case 'high':
                        $query->where('quantite', '>', 50);
                        break;
                }
            }

            // Apply sorting
            $sort = $request->get('sort', 'nom_asc');
            switch ($sort) {
                case 'nom_desc':
                    $query->orderBy('nom', 'desc');
                    break;
                case 'prix_asc':
                    $query->orderBy('prix', 'asc');
                    break;
                case 'prix_desc':
                    $query->orderBy('prix', 'desc');
                    break;
                case 'quantite_asc':
                    $query->orderBy('quantite', 'asc');
                    break;
                case 'quantite_desc':
                    $query->orderBy('quantite', 'desc');
                    break;
                case 'categorie':
                    $query->join('categories', 'produits.categorie_id', '=', 'categories.id')
                          ->orderBy('categories.nom', 'asc');
                    break;
                case 'recent':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('nom', 'asc');
                    break;
            }

            // If not exporting all data, apply pagination limit
            if (!$allData) {
                $query->limit(10);
                if ($request->has('page')) {
                    $offset = ($request->page - 1) * 10;
                    $query->offset($offset);
                }
            }

            $produits = $query->get();
            
            // Build filters array for display
            $filters = [];
            if ($request->has('search') && !empty($request->search)) {
                $filters[] = 'Recherche: ' . $request->search;
            }
            if ($request->has('categorie') && !empty($request->categorie)) {
                $categorie = Categorie::find($request->categorie);
                $filters[] = 'Catégorie: ' . ($categorie ? $categorie->nom : 'N/A');
            }
            if ($request->has('stock') && !empty($request->stock)) {
                $stockLabels = [
                    'low' => 'Stock faible (< 10)',
                    'medium' => 'Stock moyen (10-50)',
                    'high' => 'Stock élevé (> 50)'
                ];
                $filters[] = $stockLabels[$request->stock] ?? 'Stock personnalisé';
            }

            return [
                'produits' => $produits,
                'scope' => $allData ? 'all' : 'current',
                'filters' => $filters
            ];
        });
    }
}
