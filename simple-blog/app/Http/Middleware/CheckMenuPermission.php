<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuPermission
{
    /**
     * Route name to menu label mapping
     */
    protected $routeMenuMap = [
        'admin.posts' => 'Posts',
        'admin.categories' => 'Categories',
        'admin.tags' => 'Tags',
        'admin.pages' => 'Pages',
        'admin.menus' => 'Menus',
        'admin.settings' => 'Settings',
        'admin.analytics' => 'Analytics',
        'admin.users' => 'Users',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Admins have full access
        if ($user->isAdmin()) {
            return $next($request);
        }

        // For editors, check menu permissions
        if ($user->isEditor()) {
            $routeName = $request->route()->getName();
            
            // Find the menu label for this route
            $menuLabel = null;
            foreach ($this->routeMenuMap as $routePrefix => $label) {
                if (str_starts_with($routeName, $routePrefix)) {
                    $menuLabel = $label;
                    break;
                }
            }

            // If we found a menu label, check permission
            if ($menuLabel && !$user->canAccessMenu($menuLabel)) {
                abort(403, 'You do not have permission to access this page.');
            }
        }

        return $next($request);
    }
}
