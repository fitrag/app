<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with(['category', 'tags', 'user'])
            ->where('is_published', true)
            ->latest()
            ->paginate(\App\Models\Setting::get('posts_per_page', 10));

        if ($request->ajax()) {
            $html = '';
            foreach ($posts as $post) {
                $html .= view('blog.partials.post-item', compact('post'))->render();
            }
            return response()->json([
                'html' => $html,
                'nextUrl' => $posts->nextPageUrl()
            ]);
        }

        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->with(['user', 'category', 'tags'])
            ->firstOrFail();

        // Increment view count
        $post->increment('views');

        // Track analytics
        $userAgent = request()->userAgent();
        $ipAddress = request()->ip();
        $countryData = \App\Models\PostAnalytic::getCountryFromIP($ipAddress);
        
        \App\Models\PostAnalytic::create([
            'post_id' => $post->id,
            'referrer_url' => request()->headers->get('referer'),
            'referrer_type' => \App\Models\PostAnalytic::categorizeReferrer(request()->headers->get('referer')),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_type' => \App\Models\PostAnalytic::parseDeviceType($userAgent),
            'browser' => \App\Models\PostAnalytic::parseBrowser($userAgent),
            'country' => $countryData['country'],
            'country_code' => $countryData['country_code'],
            'visited_at' => now(),
        ]);

        // Check for milestone and award coins
        $currentViews = $post->views;
        $lastMilestone = $post->last_milestone;
        $newMilestone = floor($currentViews / 1000) * 1000;

        if ($newMilestone > $lastMilestone && $newMilestone > 0 && $post->user->monetization_enabled) {
            // Award coins for reaching milestone
            $coinsPerMilestone = \App\Models\Setting::get('coins_per_1000_views', 5);
            
            $post->user->increment('coins', $coinsPerMilestone);
            
            \App\Models\CoinTransaction::create([
                'user_id' => $post->user_id,
                'post_id' => $post->id,
                'amount' => $coinsPerMilestone,
                'type' => 'views_milestone',
                'description' => "Milestone reward: {$newMilestone} views on post '{$post->title}'",
            ]);

            // Update last milestone
            $post->update(['last_milestone' => $newMilestone]);
        }

        $relatedPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where(function($query) use ($post) {
                $query->where('category_id', $post->category_id)
                      ->orWhereHas('tags', function($q) use ($post) {
                          $q->whereIn('tags.id', $post->tags->pluck('id'));
                      });
            })
            ->take(3)
            ->get();
        
        return view('blog.show', compact('post', 'relatedPosts'));
    }

    public function category($slug)
    {
        $category = \App\Models\Category::where('slug', $slug)->firstOrFail();
        $posts = $category->posts()
            ->with(['user', 'category', 'tags'])
            ->where('is_published', true)
            ->latest()
            ->paginate(10);
            
        return view('blog.archive', [
            'title' => $category->name,
            'subtitle' => 'Category',
            'posts' => $posts
        ]);
    }

    public function tag($slug)
    {
        $tag = \App\Models\Tag::where('slug', $slug)->firstOrFail();
        $posts = $tag->posts()
            ->with(['user', 'category', 'tags'])
            ->where('is_published', true)
            ->latest()
            ->paginate(10);
            
        return view('blog.archive', [
            'title' => '#' . $tag->name,
            'subtitle' => 'Tag',
            'posts' => $posts
        ]);
    }
}
