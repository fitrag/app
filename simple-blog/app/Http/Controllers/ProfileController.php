<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's public profile.
     */
    public function show($id): View
    {
        $user = \App\Models\User::findOrFail($id);
        
        // Get pinned post
        $pinnedPost = \App\Models\Post::where('user_id', $id)
            ->where('is_published', true)
            ->where('is_pinned', true)
            ->with(['category', 'user'])
            ->withCount(['comments', 'loves'])
            ->first();

        // Get other posts
        $posts = \App\Models\Post::where('user_id', $id)
            ->where('is_published', true)
            ->where('is_pinned', false)
            ->with(['category', 'user'])
            ->withCount(['comments', 'loves'])
            ->latest()
            ->paginate(10);
        
        return view('profile.show', compact('user', 'posts', 'pinnedPost'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($request->user()->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($request->user()->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $request->user()->avatar = $path;
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
