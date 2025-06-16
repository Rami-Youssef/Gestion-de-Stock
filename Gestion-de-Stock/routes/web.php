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
    Route::get('profile', ['as' => 'profile.edit', 'uses' => [ProfileController::class, 'edit']]);
    Route::put('profile', ['as' => 'profile.update', 'uses' => [ProfileController::class, 'update']]);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => [ProfileController::class, 'password']]);
    
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

    // Example pages - keeping these for template structure
    Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade'); 
    Route::get('map', function () {return view('pages.maps');})->name('map');
    Route::get('icons', function () {return view('pages.icons');})->name('icons'); 
    Route::get('table-list', function () {return view('pages.tables');})->name('table');
});

