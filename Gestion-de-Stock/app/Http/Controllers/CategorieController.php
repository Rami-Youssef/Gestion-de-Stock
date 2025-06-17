<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategorieController extends Controller
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
        
        $categories = $query->paginate(10)->withQueryString();
        
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
}
