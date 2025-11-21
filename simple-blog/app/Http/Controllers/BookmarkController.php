<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the bookmarked posts.
     */
    public function index()
    {
        $bookmarks = Auth::user()->bookmarks()->with('user', 'category')->latest()->paginate(10);
        return view('bookmarks.index', compact('bookmarks'));
    }

    /**
     * Toggle bookmark status for a post.
     */
    public function store(Request $request, Post $post)
    {
        $user = Auth::user();

        if ($user->bookmarks()->where('post_id', $post->id)->exists()) {
            $user->bookmarks()->detach($post->id);
            $isBookmarked = false;
            $message = 'Post removed from bookmarks.';
        } else {
            $user->bookmarks()->attach($post->id);
            $isBookmarked = true;
            $message = 'Post added to bookmarks.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_bookmarked' => $isBookmarked,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }
}
