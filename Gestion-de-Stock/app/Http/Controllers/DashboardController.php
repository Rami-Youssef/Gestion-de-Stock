<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use App\Models\MouvementStock;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */    public function index(Request $request)
    {
        // Get selected year or default to current year
        $selectedYear = $request->get('year', Carbon::now()->year);
        
        // Get available years from stock movements
        $availableYears = MouvementStock::selectRaw('YEAR(date_cmd) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        // If no movements exist, add current year
        if (empty($availableYears)) {
            $availableYears = [Carbon::now()->year];
        }
        
        // Ensure selected year is valid
        if (!in_array($selectedYear, $availableYears)) {
            $selectedYear = $availableYears[0]; // Use most recent year
        }
        // Get counts
        $totalCategories = Categorie::count();
        $totalProduits = Produit::count();
        $totalMouvements = MouvementStock::count();
        $totalUtilisateurs = Utilisateur::count();

        // Get products with low stock (less than 10)
        $lowStockProducts = Produit::where('quantite', '<', 10)
            ->orderBy('quantite', 'asc')
            ->take(5)
            ->get();

        // Get recent stock movements
        $recentMouvements = MouvementStock::with(['produit', 'utilisateur'])
            ->orderBy('date_cmd', 'desc')
            ->take(5)
            ->get();        // Get monthly stock movement monetary values for the selected year
        $monthlyMovements = MouvementStock::select(
                DB::raw('MONTH(date_cmd) as month'),
                DB::raw('SUM(quantite * (SELECT prix FROM produits WHERE produits.id = mouvement_stocks.produit_id)) as total_value'),
                'type'
            )
            ->whereYear('date_cmd', $selectedYear)
            ->groupBy('month', 'type')
            ->get()
            ->groupBy('month');
        
        // Format data for chart
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $entries = 0;
            $exits = 0;
            
            if (isset($monthlyMovements[$i])) {
                foreach ($monthlyMovements[$i] as $movement) {
                    if ($movement->type === 'entrée') {
                        $entries = round($movement->total_value, 2);
                    } else {
                        $exits = round($movement->total_value, 2);
                    }
                }
            }
            
            $chartData[] = [
                'month' => Carbon::create(null, $i, 1)->format('M'),
                'entrées' => $entries,
                'sorties' => $exits
            ];
        }        return view('dashboard', compact(
            'totalCategories', 
            'totalProduits', 
            'totalMouvements', 
            'totalUtilisateurs',
            'lowStockProducts',
            'recentMouvements',
            'chartData',
            'selectedYear',
            'availableYears'
        ));
    }
}
