<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\MouvementStock;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
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
     * Generate and download stock inventory report
     * 
     * @return \Illuminate\Http\Response
     */
    public function stockInventory()
    {
        // Get all products with their categories
        $products = Produit::with('categorie')
            ->orderBy('categorie_id')
            ->orderBy('nom')
            ->get();
        
        // Get total stock value
        $totalValue = $products->sum(function($product) {
            return $product->quantite * $product->prix;
        });
        
        // Generate PDF
        $pdf = PDF::loadView('reports.stock-inventory', [
            'products' => $products,
            'totalValue' => $totalValue,
            'date' => Carbon::now()->format('d/m/Y H:i')
        ]);
        
        return $pdf->download('inventaire-stock-'.Carbon::now()->format('Y-m-d').'.pdf');
    }
    
    /**
     * Generate and download stock movements report for a date range
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function stockMovements(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'nullable|in:entrée,sortie'
        ]);
        
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        
        $query = MouvementStock::with(['produit', 'utilisateur'])
            ->whereBetween('date_cmd', [$startDate, $endDate])
            ->orderBy('date_cmd', 'desc');
            
        // Filter by type if specified
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $movements = $query->get();
        
        // Calculate total quantity in/out
        $totalEntrees = $movements->where('type', 'entrée')->sum('quantite');
        $totalSorties = $movements->where('type', 'sortie')->sum('quantite');
        
        // Generate PDF
        $pdf = PDF::loadView('reports.stock-movements', [
            'movements' => $movements,
            'startDate' => $startDate->format('d/m/Y'),
            'endDate' => $endDate->format('d/m/Y'),
            'totalEntrees' => $totalEntrees,
            'totalSorties' => $totalSorties,
            'type' => $request->type ?? 'tous',
            'date' => Carbon::now()->format('d/m/Y H:i')
        ]);
        
        return $pdf->download('mouvements-stock-'.$startDate->format('Y-m-d').'-'.$endDate->format('Y-m-d').'.pdf');
    }
    
    /**
     * Display form for generating reports
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Categorie::all();
        return view('reports.index', compact('categories'));
    }
}
