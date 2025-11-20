<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share menus with all views - only parent menus with their children
        View::composer('*', function ($view) {
            $view->with('globalMenus', Menu::with('activeChildren')->where('location', 'public')->parents()->active()->ordered()->get());
        });
    }
}
