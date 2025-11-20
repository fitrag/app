<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ArticleVisit;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }


    public function index()
    {
        $totalArticles = Article::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();

        $categories = Category::withCount('articles')->get();
        $chartLabels = $categories->pluck('name');
        $chartData = $categories->pluck('articles_count');

        // Analitik kunjungan
        $totalVisits = ArticleVisit::count();
        $visitsByDevice = ArticleVisit::selectRaw('device, COUNT(*) as count')
            ->groupBy('device')->pluck('count', 'device');

        $visitsByBrowser = ArticleVisit::selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')->pluck('count', 'browser');

        return view('admin.analytics.index', compact(
            'totalArticles', 'totalCategories', 'totalUsers',
            'chartLabels', 'chartData',
            'totalVisits', 'visitsByDevice', 'visitsByBrowser'
        ));
    }
}

