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
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Dashboard as home page
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/home', [DashboardController::class, 'index'])->name('home');

// Auth protected routes
Route::group(['middleware' => 'auth'], function () {
    // Profile management
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'password'])->name('profile.password');
    
    // Categories routes
    Route::resource('categories', CategorieController::class);
    
    // Products routes
    Route::resource('produits', ProduitController::class);
    
    // Stock movements routes
    Route::resource('mouvements', MouvementStockController::class)->except(['edit', 'update', 'destroy']);
    Route::post('mouvements/{mouvement}/cancel', [MouvementStockController::class, 'cancel'])->name('mouvements.cancel');
    
    // Admin only routes
    Route::group(['middleware' => 'role:admin'], function () {
        Route::resource('user', UserController::class, ['except' => ['show']]);
    });
});

