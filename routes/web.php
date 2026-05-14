<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\FavoriteController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Language switch route
Route::get('/lang/{lang}', function ($lang) {
    \Cookie::queue('locale', $lang, 60 * 24 * 30); // Simpan 30 hari
    return redirect()->back();
})->name('lang.switch');

// Movie routes
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

// Favorites route
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

// Redirect root ke movies
Route::get('/', function() {
    return redirect()->route('movies.index');
});