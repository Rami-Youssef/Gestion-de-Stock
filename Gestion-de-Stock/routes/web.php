<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\MouvementStockController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/home', [DashboardController::class, 'index'])->name('home');

// Auth and valid role protected routes
Route::group(['middleware' => ['auth', 'valid.role']], function () {
    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'password'])->name('profile.password');

    // Categories - Read only
    Route::get('categories', [CategorieController::class, 'index'])->name('categories.index');
    Route::get('categories/export/excel', [CategorieController::class, 'exportExcel'])->name('categories.export.excel');
    Route::get('categories/export/pdf', [CategorieController::class, 'exportPdf'])->name('categories.export.pdf');

    // Products - Read only
    Route::get('produits', [ProduitController::class, 'index'])->name('produits.index');
    Route::get('produits/export/excel', [ProduitController::class, 'exportExcel'])->name('produits.export.excel');
    Route::get('produits/export/pdf', [ProduitController::class, 'exportPdf'])->name('produits.export.pdf');    // Mouvements - Read only
    Route::get('mouvements', [MouvementStockController::class, 'index'])->name('mouvements.index');
    Route::get('mouvements/export/excel', [MouvementStockController::class, 'exportExcel'])->name('mouvements.export.excel');
    Route::get('mouvements/export/pdf', [MouvementStockController::class, 'exportPdf'])->name('mouvements.export.pdf');
});

// Admin & Super Admin - Create/Edit
Route::group(['middleware' => ['auth', 'valid.role', 'admin']], function () {
    // Categories - Create/Edit
    Route::get('categories/create', [CategorieController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategorieController::class, 'store'])->name('categories.store');
    Route::get('categories/{categorie}/edit', [CategorieController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{categorie}', [CategorieController::class, 'update'])->name('categories.update');

    // Products - Create/Edit (IMPORTANT: before produits/{produit})
    Route::get('produits/create', [ProduitController::class, 'create'])->name('produits.create');
    Route::post('produits', [ProduitController::class, 'store'])->name('produits.store');
    Route::get('produits/{produit}/edit', [ProduitController::class, 'edit'])->name('produits.edit');
    Route::put('produits/{produit}', [ProduitController::class, 'update'])->name('produits.update');    // Mouvements - Create/Cancel
    Route::get('mouvements/create', [MouvementStockController::class, 'create'])->name('mouvements.create');
    Route::post('mouvements', [MouvementStockController::class, 'store'])->name('mouvements.store');
    Route::post('mouvements/{mouvement}/cancel', [MouvementStockController::class, 'cancel'])->name('mouvements.cancel');
});

// Routes with dynamic params go AFTER the specific routes
Route::group(['middleware' => ['auth', 'valid.role']], function () {
    Route::get('produits/{produit}', [ProduitController::class, 'show'])->name('produits.show');
    Route::get('mouvements/{mouvement}', [MouvementStockController::class, 'show'])->name('mouvements.show');
});

// Super Admin only - Delete + User management
Route::group(['middleware' => ['auth', 'valid.role', 'super_admin']], function () {
    // Users
    Route::resource('user', UserController::class);
    Route::get('user/export/excel', [UserController::class, 'exportExcel'])->name('user.export.excel');
    Route::get('user/export/pdf', [UserController::class, 'exportPdf'])->name('user.export.pdf');

    // Delete
    Route::delete('categories/{categorie}', [CategorieController::class, 'destroy'])->name('categories.destroy');
    Route::delete('produits/{produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');
    Route::delete('mouvements/{mouvement}', [MouvementStockController::class, 'destroy'])->name('mouvements.destroy');
});
 