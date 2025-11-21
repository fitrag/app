<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonetizationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get user's monetization stats
        $totalPosts = $user->posts()->count();
        $totalViews = $user->posts()->sum('views');
        $totalCoins = $user->coins;
        $monetizationEnabled = $user->monetization_enabled;
        
        // Get coin transactions
        $recentTransactions = \App\Models\CoinTransaction::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        
        // Calculate earnings breakdown
        $earningsFromPosts = \App\Models\CoinTransaction::where('user_id', $user->id)
            ->where('type', 'create_post')
            ->sum('amount');
            
        $earningsFromViews = \App\Models\CoinTransaction::where('user_id', $user->id)
            ->where('type', 'views_milestone')
            ->sum('amount');
        
        // Monetization requirements from settings
        $requirements = [
            'min_posts' => (int) \App\Models\Setting::get('monetization_min_posts', 3),
            'min_views' => (int) \App\Models\Setting::get('monetization_min_views', 100),
        ];
        
        $meetsRequirements = $totalPosts >= $requirements['min_posts'] && 
                            $totalViews >= $requirements['min_views'];
        
        // Get latest application
        $latestApplication = $user->monetizationApplications()->latest()->first();
        
        return view('editor.monetization.index', compact(
            'user',
            'totalPosts',
            'totalViews',
            'totalCoins',
            'monetizationEnabled',
            'recentTransactions',
            'earningsFromPosts',
            'earningsFromViews',
            'requirements',
            'meetsRequirements',
            'latestApplication'
        ));
    }

    public function apply()
    {
        $user = auth()->user();
        
        // Check if already has monetization enabled
        if ($user->monetization_enabled) {
            return redirect()->route('editor.monetization.index')
                ->with('error', 'Your monetization is already enabled.');
        }
        
        // Check if has pending application
        $pendingApplication = $user->monetizationApplications()
            ->where('status', \App\Models\MonetizationApplication::STATUS_PENDING)
            ->first();
            
        if ($pendingApplication) {
            return redirect()->route('editor.monetization.index')
                ->with('error', 'You already have a pending application.');
        }
        
        // Get stats
        $totalPosts = $user->posts()->count();
        $totalViews = $user->posts()->sum('views');
        
        // Get requirements
        $requirements = [
            'min_posts' => (int) \App\Models\Setting::get('monetization_min_posts', 3),
            'min_views' => (int) \App\Models\Setting::get('monetization_min_views', 100),
        ];
        
        $meetsRequirements = $totalPosts >= $requirements['min_posts'] && 
                            $totalViews >= $requirements['min_views'];
        
        if (!$meetsRequirements) {
            return redirect()->route('editor.monetization.index')
                ->with('error', 'You do not meet the requirements yet.');
        }
        
        return view('editor.monetization.apply', compact(
            'user',
            'totalPosts',
            'totalViews',
            'requirements',
            'meetsRequirements'
        ));
    }

    public function submitApplication(Request $request)
    {
        $user = auth()->user();
        
        // Validate
        $request->validate([
            'application_reason' => 'required|string|min:50|max:1000',
            'agree_terms' => 'required|accepted',
        ], [
            'application_reason.required' => 'Please provide a reason for your application.',
            'application_reason.min' => 'Your reason must be at least 50 characters.',
            'application_reason.max' => 'Your reason must not exceed 1000 characters.',
            'agree_terms.accepted' => 'You must agree to the terms and conditions.',
        ]);
        
        // Check if already has monetization enabled
        if ($user->monetization_enabled) {
            return redirect()->route('editor.monetization.index')
                ->with('error', 'Your monetization is already enabled.');
        }
        
        // Check if has pending application
        $pendingApplication = $user->monetizationApplications()
            ->where('status', \App\Models\MonetizationApplication::STATUS_PENDING)
            ->first();
            
        if ($pendingApplication) {
            return redirect()->route('editor.monetization.index')
                ->with('error', 'You already have a pending application.');
        }
        
        // Get current stats
        $totalPosts = $user->posts()->count();
        $totalViews = $user->posts()->sum('views');
        
        // Create application
        \App\Models\MonetizationApplication::create([
            'user_id' => $user->id,
            'application_reason' => $request->application_reason,
            'total_posts_at_application' => $totalPosts,
            'total_views_at_application' => $totalViews,
            'status' => \App\Models\MonetizationApplication::STATUS_PENDING,
        ]);
        
        return redirect()->route('editor.monetization.index')
            ->with('success', 'Your monetization application has been submitted successfully. Please wait for admin review.');
    }
}
