<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, CategoryController};
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AnalyticsController;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin,moderator'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('articles', ArticleController::class);
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
});
