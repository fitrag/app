<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BlogController;
use App\Http\Controllers\PageController;

Route::get('/', [BlogController::class, 'index'])->name('blog.index');
Route::get('/posts/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Editor routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('editor/monetization', [\App\Http\Controllers\Editor\MonetizationController::class, 'index'])->name('editor.monetization.index');
});

Route::middleware(['auth', 'admin', 'menu.permission'])->group(function () {
    Route::resource('admin/posts', \App\Http\Controllers\Admin\PostController::class)->names('admin.posts');
    Route::get('admin/posts/{post}/analytics', [\App\Http\Controllers\Admin\PostController::class, 'analytics'])->name('admin.posts.analytics');
    Route::resource('admin/categories', \App\Http\Controllers\Admin\CategoryController::class)->names('admin.categories');
    Route::resource('admin/tags', \App\Http\Controllers\Admin\TagController::class)->names('admin.tags');
    Route::resource('admin/pages', \App\Http\Controllers\Admin\PageController::class)->names('admin.pages');
    Route::resource('admin/menus', \App\Http\Controllers\Admin\MenuController::class)->names('admin.menus');
    Route::post('admin/menus/reorder', [\App\Http\Controllers\Admin\MenuController::class, 'reorder'])->name('admin.menus.reorder');
    Route::get('admin/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('admin/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');
    Route::get('admin/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('admin.analytics.index');
    Route::resource('admin/users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
});

require __DIR__.'/auth.php';
