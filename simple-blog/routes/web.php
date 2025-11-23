<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BlogController;
use App\Http\Controllers\PageController;

Route::get('/', [BlogController::class, 'index'])->name('blog.index');
Route::get('/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/api/search-suggestions', [BlogController::class, 'searchSuggestions'])->name('api.search.suggestions');
Route::get('/posts/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Become a Writer
    Route::post('/become-writer', [\App\Http\Controllers\UserController::class, 'becomeWriter'])->name('user.become-writer');

    // Follow System Routes
    Route::post('/users/{user}/follow', [\App\Http\Controllers\FollowController::class, 'store'])->name('users.follow');
    Route::delete('/users/{user}/unfollow', [\App\Http\Controllers\FollowController::class, 'destroy'])->name('users.unfollow');
    Route::get('/users/{user}/followers', [\App\Http\Controllers\FollowController::class, 'followers'])->name('users.followers');
    Route::get('/users/{user}/following', [\App\Http\Controllers\FollowController::class, 'following'])->name('users.following');

    // Bookmark Routes
    Route::post('/posts/{post}/bookmark', [\App\Http\Controllers\BookmarkController::class, 'store'])->name('posts.bookmark');
    Route::get('/bookmarks', [\App\Http\Controllers\BookmarkController::class, 'index'])->name('bookmarks.index');

    // Love Routes
    Route::post('/posts/{post}/love', [\App\Http\Controllers\PostLoveController::class, 'store'])->name('posts.love');
    
    // Pin Post Route
    Route::post('/posts/{post}/pin', [\App\Http\Controllers\PinnedPostController::class, 'toggle'])->name('posts.pin');

    // Comment Routes
    Route::post('/posts/{post}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/love', [\App\Http\Controllers\CommentLoveController::class, 'toggle'])->name('comments.love');
    
    // Notification Routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'recent'])->name('notifications.recent');

    // User Interests
    Route::post('/interests', [\App\Http\Controllers\InterestController::class, 'store'])->name('interests.store');

    // Category Follow Routes
    Route::post('/categories/{category}/follow', [\App\Http\Controllers\CategoryFollowController::class, 'store'])->name('categories.follow');
    Route::delete('/categories/{category}/unfollow', [\App\Http\Controllers\CategoryFollowController::class, 'destroy'])->name('categories.unfollow');

    // Tag Follow Routes
    Route::post('/tags/{tag}/follow', [\App\Http\Controllers\TagFollowController::class, 'store'])->name('tags.follow');
    Route::delete('/tags/{tag}/unfollow', [\App\Http\Controllers\TagFollowController::class, 'destroy'])->name('tags.unfollow');
});

// Editor routes
Route::middleware(['auth'])->group(function () {
    Route::get('editor/monetization', [\App\Http\Controllers\Editor\MonetizationController::class, 'index'])->name('editor.monetization.index');
    Route::get('editor/monetization/apply', [\App\Http\Controllers\Editor\MonetizationController::class, 'apply'])->name('editor.monetization.apply');
    Route::post('editor/monetization/apply', [\App\Http\Controllers\Editor\MonetizationController::class, 'submitApplication'])->name('editor.monetization.submit');
});

Route::middleware(['auth', 'admin', 'menu.permission'])->group(function () {
    Route::resource('admin/posts', \App\Http\Controllers\Admin\PostController::class)->names('admin.posts');
    Route::post('admin/posts/auto-save', [\App\Http\Controllers\Admin\PostController::class, 'autoSave'])->name('admin.posts.auto-save');
    Route::post('admin/posts/bulk-action', [\App\Http\Controllers\Admin\PostController::class, 'bulkAction'])->name('admin.posts.bulk-action');
    Route::get('admin/posts/{post}/analytics', [\App\Http\Controllers\Admin\PostController::class, 'analytics'])->name('admin.posts.analytics');
    Route::resource('admin/categories', \App\Http\Controllers\Admin\CategoryController::class)->names('admin.categories');
    Route::resource('admin/tags', \App\Http\Controllers\Admin\TagController::class)->names('admin.tags');
    Route::resource('admin/pages', \App\Http\Controllers\Admin\PageController::class)->names('admin.pages');
    Route::resource('admin/menus', \App\Http\Controllers\Admin\MenuController::class)->names('admin.menus');
    Route::post('admin/menus/reorder', [\App\Http\Controllers\Admin\MenuController::class, 'reorder'])->name('admin.menus.reorder');
    Route::get('admin/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('admin/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');
    Route::get('admin/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('admin.analytics.index');
    Route::get('admin/analytics/recent-visitors', [\App\Http\Controllers\Admin\AnalyticsController::class, 'recentVisitorsJson'])->name('admin.analytics.recent-visitors');
    Route::resource('admin/users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
    
    // Monetization Applications - Strict Admin Only
    Route::middleware('strict.admin')->group(function () {
        Route::get('admin/monetization-applications', [\App\Http\Controllers\Admin\MonetizationApplicationController::class, 'index'])->name('admin.monetization-applications.index');
        Route::get('admin/monetization-applications/{id}', [\App\Http\Controllers\Admin\MonetizationApplicationController::class, 'show'])->name('admin.monetization-applications.show');
        Route::post('admin/monetization-applications/{id}/approve', [\App\Http\Controllers\Admin\MonetizationApplicationController::class, 'approve'])->name('admin.monetization-applications.approve');
        Route::post('admin/monetization-applications/{id}/reject', [\App\Http\Controllers\Admin\MonetizationApplicationController::class, 'reject'])->name('admin.monetization-applications.reject');
    });
});

require __DIR__.'/auth.php';
