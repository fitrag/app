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

        // If parent_id is provided, verify it exists and belongs to the same post
        if ($request->parent_id) {
            $parentComment = Comment::with('post')->find($request->parent_id);
            
            if (!$parentComment) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The comment you are replying to no longer exists.'
                    ], 404);
                }
                return back()->withErrors(['content' => 'The comment you are replying to no longer exists.'])->withInput();
            }
            
            if ($parentComment->post_id !== $post->id) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid parent comment.'
                    ], 403);
                }
                return back()->withErrors(['content' => 'Invalid parent comment.'])->withInput();
            }
        }

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        // Create notification for parent comment owner if this is a reply
        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            if ($parentComment && $parentComment->user_id !== Auth::id()) {
                \App\Models\Notification::create([
                    'user_id' => $parentComment->user_id,
                    'type' => 'comment_reply',
                    'notifiable_type' => 'App\\Models\\Comment',
                    'notifiable_id' => $comment->id,
                    'actor_id' => Auth::id(),
                ]);
            }
        }
        
        // Create notification for post owner when someone comments (not a reply)
        if (!$request->parent_id && $post->user_id !== Auth::id()) {
            \App\Models\Notification::create([
                'user_id' => $post->user_id,
                'type' => 'post_comment',
                'notifiable_type' => 'App\\Models\\Post',
                'notifiable_id' => $post->id,
                'actor_id' => Auth::id(),
            ]);
        }

        return back()->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        // Authorization: Only comment owner or post owner can delete
        if ($comment->user_id !== Auth::id() && $comment->post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to delete this comment.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
}
