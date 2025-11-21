<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostLoveController extends Controller
{
    /**
     * Toggle love status for a post.
     */
    public function store(Request $request, Post $post)
    {
        $user = Auth::user();

        if ($user->loves()->where('post_id', $post->id)->exists()) {
            $user->loves()->detach($post->id);
            $isLoved = false;
            $message = 'Post unloved.';
        } else {
            $user->loves()->attach($post->id);
            $isLoved = true;
            $message = 'Post loved.';
        }

        $lovesCount = $post->loves()->count();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_loved' => $isLoved,
                'loves_count' => $lovesCount,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }
}
