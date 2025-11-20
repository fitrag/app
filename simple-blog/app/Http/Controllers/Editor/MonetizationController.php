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
            'meetsRequirements'
        ));
    }
}
