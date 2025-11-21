<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagFollowController extends Controller
{
    /**
     * Follow a tag.
     */
    public function store(Request $request, Tag $tag)
    {
        $user = Auth::user();
        
        if (!$user->followedTags()->where('tag_id', $tag->id)->exists()) {
            $user->followedTags()->attach($tag->id);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'You are now following #' . $tag->name,
                'is_following' => true,
                'followers_count' => $tag->followers()->count()
            ]);
        }

        return back()->with('success', 'You are now following #' . $tag->name);
    }

    /**
     * Unfollow a tag.
     */
    public function destroy(Request $request, Tag $tag)
    {
        $user = Auth::user();
        
        $user->followedTags()->detach($tag->id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'You have unfollowed #' . $tag->name,
                'is_following' => false,
                'followers_count' => $tag->followers()->count()
            ]);
        }

        return back()->with('success', 'You have unfollowed #' . $tag->name);
    }
}
