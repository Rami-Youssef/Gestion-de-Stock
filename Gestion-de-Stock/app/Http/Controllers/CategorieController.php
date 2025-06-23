<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\ExportableTrait;
use App\Exports\CategoriesExport;

class CategorieController extends Controller
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
     * Display a listing of the categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'nom_asc'); // Default sort alphabetically
        
        $query = Categorie::query();
        
        if ($search) {
            $query->where('nom', 'like', '%' . $search . '%');
        }
        
        // Apply sorting
        switch ($sort) {
            case 'nom_desc':
                $query->orderBy('nom', 'desc');
                break;
            case 'produits_count':
                $query->withCount('produits')->orderBy('produits_count', 'desc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            default: // nom_asc
                $query->orderBy('nom', 'asc');
                break;
        }
        
        $categories = $query->paginate(10)->appends(request()->query());
        
        return view('categories.index', compact('categories', 'search', 'sort'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:categories'
        ]);

        Categorie::create([
            'nom' => $request->nom
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie créée avec succès');
    }    /**
     * Show the form for editing the specified category.
     *
     * @param  \App\Models\Categorie  $category
     * @return \Illuminate\View\View
     */
    public function edit(Categorie $category)
    {
        return view('categories.edit', ['categorie' => $category]);
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categorie  $category
     * @return \Illuminate\Http\RedirectResponse
     */    public function update(Request $request, Categorie $category)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,'.$category->id
        ]);

        $category->update([
            'nom' => $request->nom
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie mise à jour avec succès');
    }    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Models\Categorie  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Categorie $category)
    {
        // Check if the category has products
        if ($category->produits()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès');
    }
      public function exportExcel(Request $request)
    {
        return $this->handleExcelExport($request, CategoriesExport::class, 'Categories');
    }    public function exportPdf(Request $request)
    {
        return $this->handlePdfExport($request, 'exports.categories-pdf', 'Categories', function($request, $allData) {
            $query = Categorie::withCount('produits');

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $query->where('nom', 'like', '%' . $request->search . '%');
            }

            // Apply sorting
            $sort = $request->get('sort', 'nom_asc');
            switch ($sort) {
                case 'nom_desc':
                    $query->orderBy('nom', 'desc');
                    break;
                case 'produits_count':
                    $query->orderBy('produits_count', 'desc');
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

            $categories = $query->get();
            
            // Build filters array for display
            $filters = [];
            if ($request->has('search') && !empty($request->search)) {
                $filters[] = 'Recherche: ' . $request->search;
            }
            if ($request->has('sort') && $request->sort !== 'nom_asc') {
                $sortLabels = [
                    'nom_desc' => 'Tri: Nom (Z-A)',
                    'produits_count' => 'Tri: Nombre de produits',
                    'recent' => 'Tri: Plus récentes'
                ];
                $filters[] = $sortLabels[$request->sort] ?? 'Tri personnalisé';
            }

            return [
                'categories' => $categories,
                'scope' => $allData ? 'all' : 'current',
                'filters' => $filters
            ];
        });
    }
}
