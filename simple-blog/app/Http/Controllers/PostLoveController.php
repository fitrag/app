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
            
            // Delete notification if exists
            \App\Models\Notification::where('user_id', $post->user_id)
                ->where('actor_id', $user->id)
                ->where('type', 'post_love')
                ->where('notifiable_type', 'App\\Models\\Post')
                ->where('notifiable_id', $post->id)
                ->delete();
        } else {
            $user->loves()->attach($post->id);
            $isLoved = true;
            $message = 'Post loved.';
            
            // Create notification for post owner (don't notify self)
            if ($post->user_id !== $user->id) {
                \App\Models\Notification::create([
                    'user_id' => $post->user_id,
                    'type' => 'post_love',
                    'notifiable_type' => 'App\\Models\\Post',
                    'notifiable_id' => $post->id,
                    'actor_id' => $user->id,
                ]);
            }
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
