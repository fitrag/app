<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Follow a user.
     */
    public function store(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        if ($currentUser->id === $user->id) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'You cannot follow yourself.'], 422);
            }
            return back()->with('error', 'You cannot follow yourself.');
        }

        $currentUser->follow($user);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'You are now following ' . $user->name,
                'is_following' => true,
                'followers_count' => $user->followers()->count()
            ]);
        }

        return back()->with('success', 'You are now following ' . $user->name);
    }

    /**
     * Unfollow a user.
     */
    public function destroy(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        $currentUser->unfollow($user);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'You have unfollowed ' . $user->name,
                'is_following' => false,
                'followers_count' => $user->followers()->count()
            ]);
        }

        return back()->with('success', 'You have unfollowed ' . $user->name);
    }

    /**
     * Get followers list.
     */
    public function followers(User $user)
    {
        // Only allow the user themselves to view their followers list
        if (Auth::id() !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $followers = $user->followers()->paginate(10);
        
        // Transform to add is_following status for the current user
        $followers->getCollection()->transform(function ($follower) {
            $follower->is_following = Auth::check() ? Auth::user()->isFollowing($follower) : false;
            return $follower;
        });

        return response()->json($followers);
    }

    /**
     * Get following list.
     */
    public function following(User $user)
    {
        // Only allow the user themselves to view their following list
        if (Auth::id() !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $following = $user->following()->paginate(10);

        // Transform to add is_following status for the current user
        $following->getCollection()->transform(function ($followedUser) {
            $followedUser->is_following = Auth::check() ? Auth::user()->isFollowing($followedUser) : false;
            return $followedUser;
        });

        return response()->json($following);
    }
}
