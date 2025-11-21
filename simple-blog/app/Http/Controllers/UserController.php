<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;

class UserController extends Controller
{
    /**
     * Upgrade user to editor/writer role
     */
    public function becomeWriter(Request $request)
    {
        \Log::info('becomeWriter method called for user: ' . Auth::id());
        $user = Auth::user();

        // Check if user is already an editor or admin
        if ($user->role !== 'user') {
            return redirect()->route('dashboard')->with('error', 'You are already a writer or admin.');
        }

        DB::beginTransaction();
        try {
            // Update user role to editor
            $user->role = 'editor';
            $user->save();

            // Get the default editor menus (Posts, Categories, Tags, Monetization)
            $editorMenus = Menu::whereIn('label', ['Posts', 'Categories', 'Tags', 'Monetization', 'Nulis dapat uang'])->pluck('id');

            // Assign menus to the user
            if ($editorMenus->isNotEmpty()) {
                $user->menus()->sync($editorMenus);
            }

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Congratulations! You are now a writer. Start creating your first post!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Become Writer Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
