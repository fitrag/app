<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryFollowController extends Controller
{
    /**
     * Follow a category.
     */
    public function store(Request $request, Category $category)
    {
        $user = Auth::user();
        
        if (!$user->interests()->where('category_id', $category->id)->exists()) {
            $user->interests()->attach($category->id);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'You are now following ' . $category->name,
                'is_following' => true,
                'followers_count' => $category->followers()->count()
            ]);
        }

        return back()->with('success', 'You are now following ' . $category->name);
    }

    /**
     * Unfollow a category.
     */
    public function destroy(Request $request, Category $category)
    {
        $user = Auth::user();
        
        $user->interests()->detach($category->id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'You have unfollowed ' . $category->name,
                'is_following' => false,
                'followers_count' => $category->followers()->count()
            ]);
        }

        return back()->with('success', 'You have unfollowed ' . $category->name);
    }
}
