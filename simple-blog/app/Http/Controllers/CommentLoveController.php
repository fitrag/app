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
        } else {
            $comment->loves()->create(['user_id' => $user->id]);
            $isLoved = true;
            $message = 'Comment loved!';
        }

        return response()->json([
            'success' => true,
            'is_loved' => $isLoved,
            'loves_count' => $comment->loves()->count(),
            'message' => $message
        ]);
    }
}
