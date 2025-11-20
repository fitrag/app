<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleVisit;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class ArticleFrontController extends Controller
{
    public function show($slug, Request $request)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        // Cek agent (browser, device)
        $agent = new Agent();
        $device = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');
        $browser = $agent->browser();

        // Simpan data kunjungan
        ArticleVisit::create([
            'article_id' => $article->id,
            'ip_address' => $request->ip(),
            'device' => $device,
            'browser' => $browser,
        ]);

        return view('articles.show', compact('article'));
    }
}

