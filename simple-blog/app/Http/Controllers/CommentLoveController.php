<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentLoveController extends Controller
{
    public function toggle(Comment $comment)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        $love = $comment->loves()->where('user_id', $user->id)->first();

        if ($love) {
            $love->delete();
            $isLoved = false;
            $message = 'Love removed';
            
            // Delete notification if exists
            \App\Models\Notification::where('user_id', $comment->user_id)
                ->where('actor_id', $user->id)
                ->where('type', 'comment_love')
                ->where('notifiable_type', 'App\\Models\\Comment')
                ->where('notifiable_id', $comment->id)
                ->delete();
        } else {
            $comment->loves()->create(['user_id' => $user->id]);
            $isLoved = true;
            $message = 'Comment loved!';
            
            // Create notification for comment owner (don't notify self)
            if ($comment->user_id !== $user->id) {
                \App\Models\Notification::create([
                    'user_id' => $comment->user_id,
                    'type' => 'comment_love',
                    'notifiable_type' => 'App\\Models\\Comment',
                    'notifiable_id' => $comment->id,
                    'actor_id' => $user->id,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'is_loved' => $isLoved,
            'loves_count' => $comment->loves()->count(),
            'message' => $message
        ]);
    }
}
