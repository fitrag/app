<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NovelController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\ReportController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated Routes - Dashboard Berdasarkan Role
Route::middleware('auth')->group(function () {
    // Dashboard untuk user biasa
    Route::get('/dashboard', function () {
        if (Auth::user()->isAdmin() || Auth::user()->isKreator()) {
            return redirect()->route('home');
        }
        return view('dashboard.biasa');
    })->name('dashboard')->middleware('role:biasa');

    // Dashboard untuk kreator
    Route::get('/kreator/dashboard', function () {
        if (!Auth::user()->isKreator() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('dashboard.kreator');
    })->name('kreator.dashboard')->middleware('role:kreator,admin');

    // Routes untuk admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            if (!Auth::user()->isAdmin()) {
                abort(403);
            }
            return view('dashboard.admin');
        })->name('dashboard')->middleware('role:admin');
        
        // User Management Routes
        Route::resource('users', UserController::class)->middleware('role:admin');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status')
            ->middleware('role:admin');
            
        // Novel Management Routes
        Route::resource('novels', NovelController::class)->middleware('role:admin');
        Route::post('/novels/{novel}/toggle-featured', [NovelController::class, 'toggleFeatured'])
            ->name('novels.toggle-featured')
            ->middleware('role:admin');
            
        // Chapter Management Routes
        Route::prefix('novels/{novel}')->name('novels.')->group(function () {
            Route::resource('chapters', ChapterController::class)->middleware('role:admin');
            Route::post('chapters/reorder', [ChapterController::class, 'reorder'])
                ->name('chapters.reorder')
                ->middleware('role:admin');
        });
        
        // Report & Statistics Routes
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index')
            ->middleware('role:admin');
        Route::post('/reports/export', [ReportController::class, 'export'])
            ->name('reports.export')
            ->middleware('role:admin');
    });
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Home Route
Route::get('/', function () {
    return view('welcome');
})->name('home');