<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VisitorAnalytic;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '7days'); // 7days, 30days, thismonth, all
        
        // Base query
        $query = VisitorAnalytic::query();
        
        // Apply period filter
        switch ($period) {
            case 'today':
                $query->today();
                break;
            case '7days':
                $query->lastDays(7);
                break;
            case '30days':
                $query->lastDays(30);
                break;
            case 'thismonth':
                $query->thisMonth();
                break;
            // 'all' - no filter
        }
        
        // Total visitors
        $totalVisitors = $query->count();
        
        // Unique visitors (by IP)
        $uniqueVisitors = (clone $query)->distinct('ip_address')->count('ip_address');
        
        // Device statistics
        $deviceStats = (clone $query)
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->get();
        
        // Browser statistics
        $browserStats = (clone $query)
            ->select('browser', DB::raw('count(*) as count'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        // Platform (OS) statistics
        $platformStats = (clone $query)
            ->select('platform', DB::raw('count(*) as count'))
            ->whereNotNull('platform')
            ->groupBy('platform')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        // Popular pages
        $popularPages = (clone $query)
            ->select('page_url', DB::raw('count(*) as visits'))
            ->groupBy('page_url')
            ->orderByDesc('visits')
            ->limit(10)
            ->get();
        
        // Referrer statistics
        $referrerStats = (clone $query)
            ->select('referrer', DB::raw('count(*) as count'))
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        // Country statistics
        $countryStats = (clone $query)
            ->select('country', DB::raw('count(*) as count'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // City statistics
        $cityStats = (clone $query)
            ->select('city', 'country', DB::raw('count(*) as count'))
            ->whereNotNull('city')
            ->groupBy('city', 'country')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        // Daily visits for chart (last 7 days)
        $dailyVisits = VisitorAnalytic::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total_visits'),
                DB::raw('count(DISTINCT ip_address) as unique_visits')
            )
            ->where('created_at', '>=', now()->subDays(6)) // Last 7 days including today
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Recent visitors
        $recentVisitors = VisitorAnalytic::latest()
            ->limit(20)
            ->get();
        
        return view('admin.analytics.index', compact(
            'totalVisitors',
            'uniqueVisitors',
            'deviceStats',
            'browserStats',
            'platformStats',
            'popularPages',
            'referrerStats',
            'countryStats',
            'cityStats',
            'dailyVisits',
            'recentVisitors',
            'period'
        ));
    }
}
