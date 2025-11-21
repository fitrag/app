<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $feed = $request->query('feed', 'foryou');
        $query = Post::with(['category', 'tags', 'user'])
            ->withCount('comments')
            ->where('is_published', true);

        $showInterestModal = false;

        if ($feed === 'followed' && auth()->check()) {
            $user = auth()->user();
            
            // Get followed user IDs
            $followedUserIds = $user->following()->pluck('users.id');
            
            // Get followed category IDs
            $followedCategoryIds = $user->interests()->pluck('categories.id');
            
            // Get followed tag IDs
            $followedTagIds = $user->followedTags()->pluck('tags.id');
            
            // Build query to get posts from all followed sources
            $query->where(function($q) use ($followedUserIds, $followedCategoryIds, $followedTagIds) {
                // Posts from followed users
                if ($followedUserIds->isNotEmpty()) {
                    $q->whereIn('user_id', $followedUserIds);
                }
                
                // Posts from followed categories
                if ($followedCategoryIds->isNotEmpty()) {
                    $q->orWhereIn('category_id', $followedCategoryIds);
                }
                
                // Posts with followed tags
                if ($followedTagIds->isNotEmpty()) {
                    $q->orWhereHas('tags', function($tagQuery) use ($followedTagIds) {
                        $tagQuery->whereIn('tags.id', $followedTagIds);
                    });
                }
            });
            
            $query->distinct()->latest();
        } elseif ($feed === 'foryou' && auth()->check()) {
            // Check if user has interests
            $userInterests = auth()->user()->interests()->pluck('categories.id');
            
            if ($userInterests->isNotEmpty()) {
                $query->whereIn('category_id', $userInterests);
            } else {
                // If user has no interests, show the modal
                $showInterestModal = true;
            }

            // Randomize with session seed for consistent pagination
            $page = $request->input('page', 1);
            if ($page == 1) {
                $seed = rand();
                session(['foryou_seed' => $seed]);
            } else {
                $seed = session('foryou_seed', rand());
            }
            $query->inRandomOrder($seed);
        } elseif ($feed === 'latest') {
            $query->latest();
        } else {
            $query->latest();
        }

        $posts = $query->paginate(\App\Models\Setting::get('posts_per_page', 10))
            ->withQueryString();

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

        // Get popular posts from last 7 days
        $popularPosts = Post::where('is_published', true)
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        // Get hot categories this week (cached for 1 hour)
        $hotCategories = cache()->remember('hot_categories_weekly', 3600, function () {
            return \App\Models\Category::hotThisWeek(5)->get();
        });

        return view('blog.index', compact('posts', 'popularPosts', 'hotCategories', 'feed', 'showInterestModal'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->with(['user', 'category', 'tags', 'comments' => function($query) {
                $query->topLevel()->with('user', 'replies.user', 'replies.replies.user');
            }])
            ->firstOrFail();

        // Check if user has visited this post recently (e.g., last 60 minutes)
        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();
        
        $hasVisitedRecently = \App\Models\PostAnalytic::where('post_id', $post->id)
            ->where('ip_address', $ipAddress)
            ->where('visited_at', '>=', now()->subMinutes(60))
            ->exists();

        if (! $hasVisitedRecently) {
            // Increment view count
            $post->increment('views');

            // Track analytics
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
        }

        $relatedPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where(function($query) use ($post) {
                $query->where('category_id', $post->category_id)
                      ->orWhereHas('tags', function($q) use ($post) {
                          $q->whereIn('tags.id', $post->tags->pluck('id'));
                      });
            })
            ->withCount(['loves', 'comments'])
            ->take(3)
            ->get();

        $authorPosts = Post::where('user_id', $post->user_id)
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->withCount(['loves', 'comments'])
            ->latest()
            ->take(3)
            ->get();
        
        return view('blog.show', compact('post', 'relatedPosts', 'authorPosts'));
    }

    public function category($slug)
    {
        $category = \App\Models\Category::where('slug', $slug)->firstOrFail();
        $posts = $category->posts()
            ->with(['user', 'category', 'tags'])
            ->where('is_published', true)
            ->latest()
            ->paginate(10);
            
        $postsCount = $category->posts()->where('is_published', true)->count();
        
        // Get popular post this week
        $popularPost = $category->posts()
            ->where('is_published', true)
            ->where('posts.created_at', '>=', now()->subDays(7))
            ->orderBy('views', 'desc')
            ->with(['user', 'category'])
            ->withCount(['loves', 'comments'])
            ->first();
            
        return view('blog.archive', [
            'title' => $category->name,
            'subtitle' => 'Category',
            'posts' => $posts,
            'model' => $category,
            'type' => 'category',
            'postsCount' => $postsCount,
            'popularPost' => $popularPost
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
            
        $postsCount = $tag->posts()->where('is_published', true)->count();
        
        // Get popular post this week
        $popularPost = $tag->posts()
            ->where('is_published', true)
            ->where('posts.created_at', '>=', now()->subDays(7))
            ->orderBy('views', 'desc')
            ->with(['user', 'category'])
            ->withCount(['loves', 'comments'])
            ->first();
            
        return view('blog.archive', [
            'title' => '#' . $tag->name,
            'subtitle' => 'Tag',
            'posts' => $posts,
            'model' => $tag,
            'type' => 'tag',
            'postsCount' => $postsCount,
            'popularPost' => $popularPost
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return redirect()->route('blog.index');
        }

        // Search Posts
        $posts = Post::with(['user', 'category', 'tags'])
            ->where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->latest()
            ->limit(10)
            ->get();

        // Search Users
        $users = \App\Models\User::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('bio', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();

        // Search Categories
        $categories = \App\Models\Category::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        // Search Tags
        $tags = \App\Models\Tag::where('name', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        // Calculate total results
        $totalResults = $posts->count() + $users->count() + $categories->count() + $tags->count();

        return view('blog.search', [
            'query' => $query,
            'posts' => $posts,
            'users' => $users,
            'categories' => $categories,
            'tags' => $tags,
            'totalResults' => $totalResults
        ]);
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        // Get top 3 posts
        $posts = Post::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%");
            })
            ->select('id', 'title', 'slug')
            ->limit(3)
            ->get()
            ->map(function($post) {
                return [
                    'type' => 'post',
                    'title' => $post->title,
                    'url' => route('blog.show', $post->slug)
                ];
            });

        // Get top 2 users
        $users = \App\Models\User::where('name', 'like', "%{$query}%")
            ->select('id', 'name')
            ->limit(2)
            ->get()
            ->map(function($user) {
                return [
                    'type' => 'user',
                    'title' => $user->name,
                    'url' => route('profile.show', $user->id)
                ];
            });

        // Get top 2 categories
        $categories = \App\Models\Category::where('name', 'like', "%{$query}%")
            ->select('id', 'name', 'slug')
            ->limit(2)
            ->get()
            ->map(function($category) {
                return [
                    'type' => 'category',
                    'title' => $category->name,
                    'url' => route('blog.category', $category->slug)
                ];
            });

        // Get top 3 tags
        $tags = \App\Models\Tag::where('name', 'like', "%{$query}%")
            ->select('id', 'name', 'slug')
            ->limit(3)
            ->get()
            ->map(function($tag) {
                return [
                    'type' => 'tag',
                    'title' => $tag->name,
                    'url' => route('blog.tag', $tag->slug)
                ];
            });

        $suggestions = $posts->concat($users)->concat($categories)->concat($tags);

        return response()->json($suggestions);
    }
}
