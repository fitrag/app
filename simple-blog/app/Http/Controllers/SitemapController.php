<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $posts = Post::where('is_published', true)
            ->latest()
            ->get();

        $pages = Page::where('is_published', true)
            ->latest()
            ->get();

        $categories = Category::all();
        $tags = Tag::all();
        $users = User::all();

        $content = view('sitemap.index', compact('posts', 'pages', 'categories', 'tags', 'users'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
