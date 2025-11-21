<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        if (! $post->is_commentable) {
            abort(403, 'Comments are disabled for this post.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        // If parent_id is provided, verify it belongs to the same post
        if ($request->parent_id) {
            $parentComment = Comment::findOrFail($request->parent_id);
            if ($parentComment->post_id !== $post->id) {
                abort(403, 'Invalid parent comment.');
            }
        }

        $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Comment added successfully.');
    }
}
