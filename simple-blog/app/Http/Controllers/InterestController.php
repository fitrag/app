<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterestController extends Controller
{
    /**
     * Store the user's selected interests.
     */
    public function store(Request $request)
    {
        $request->validate([
            'interests' => 'required|array',
            'interests.*' => 'exists:categories,id',
        ]);

        $user = Auth::user();
        $user->interests()->sync($request->interests);

        return response()->json(['message' => 'Interests saved successfully.']);
    }
}
