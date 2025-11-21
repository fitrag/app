<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinnedPostController extends Controller
{
    /**
     * Toggle pin status of a post.
     */
    public function toggle(Request $request, Post $post)
    {
        // Authorization: Only post owner can pin/unpin
        if ($post->user_id !== Auth::id()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to pin this post.'
                ], 403);
            }
            abort(403, 'Unauthorized to pin this post.');
        }

        // If pinning this post, unpin any other pinned post by the same user
        if (!$post->is_pinned) {
            Post::where('user_id', Auth::id())
                ->where('is_pinned', true)
                ->update(['is_pinned' => false]);
        }

        // Toggle pin status
        $post->is_pinned = !$post->is_pinned;
        $post->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_pinned' => $post->is_pinned,
                'message' => $post->is_pinned ? 'Post pinned successfully.' : 'Post unpinned successfully.'
            ]);
        }

        return back()->with('success', $post->is_pinned ? 'Post pinned successfully.' : 'Post unpinned successfully.');
    }
}
