<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['category', 'tags']);
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // Editors can only see their own posts
        if (auth()->user()->isEditor()) {
            $query->where('user_id', auth()->id());
        }
        // Admins can see all posts
        
        $posts = $query->latest()->paginate(10)->withQueryString();
        
        if ($request->ajax()) {
            return view('admin.posts.partials.post-rows', compact('posts'))->render();
        }
        
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $post = Post::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'image' => $imagePath,
            'is_published' => $request->has('is_published'),
            'is_commentable' => $request->has('is_commentable'),
            'show_read_also' => $request->has('show_read_also'),
            'enable_font_adjuster' => $request->has('enable_font_adjuster'),
            'focus_keyword' => $request->focus_keyword,
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        // Award coins to editor for creating post
        if (auth()->user()->isEditor() && auth()->user()->monetization_enabled) {
            $coinsPerPost = \App\Models\Setting::get('coins_per_post', 10);
            
            auth()->user()->increment('coins', $coinsPerPost);
            
            \App\Models\CoinTransaction::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
                'amount' => $coinsPerPost,
                'type' => 'create_post',
                'description' => 'Coins earned for creating post: ' . $post->title,
            ]);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $slug = Str::slug($request->title);
        if ($slug !== $post->slug) {
            $originalSlug = $slug;
            $count = 1;

            while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
        }

        if ($request->hasFile('image')) {
            // Delete old image if it's a file (not a URL)
            if ($post->image && !str_starts_with($post->image, 'http')) {
                Storage::disk('public')->delete($post->image);
            }
            $imagePath = $request->file('image')->store('posts', 'public');
        } elseif ($request->filled('image_url')) {
            // Delete old image if it's a file (not a URL)
            if ($post->image && !str_starts_with($post->image, 'http')) {
                Storage::disk('public')->delete($post->image);
            }
            $imagePath = $request->image_url;
        } else {
            $imagePath = $post->image;
        }

        $post->update([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'image' => $imagePath,
            'is_published' => $request->has('is_published'),
            'is_commentable' => $request->has('is_commentable'),
            'show_read_also' => $request->has('show_read_also'),
            'enable_font_adjuster' => $request->has('enable_font_adjuster'),
            'focus_keyword' => $request->focus_keyword,
            'category_id' => $request->category_id,
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    /**
     * Show analytics for a specific post
     */
    public function analytics(Post $post)
    {
        // Get analytics data
        $totalViews = $post->views;
        $uniqueVisitors = $post->analytics()->distinct('ip_address')->count('ip_address');
        $lovesCount = $post->loves()->count();
        $commentsCount = $post->comments()->count();
        
        // Calculate total coins earned from this post
        $totalCoinsEarned = \App\Models\CoinTransaction::where('post_id', $post->id)
            ->sum('amount');
        
        // Traffic sources breakdown
        $trafficSources = $post->analytics()
            ->selectRaw('referrer_type, COUNT(*) as count')
            ->groupBy('referrer_type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->referrer_type => $item->count];
            });

        // Top referrers
        $topReferrers = $post->analytics()
            ->whereNotNull('referrer_url')
            ->where('referrer_url', '!=', '')
            ->selectRaw('referrer_url, COUNT(*) as count')
            ->groupBy('referrer_url')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Recent activity
        $recentActivity = $post->analytics()
            ->orderByDesc('visited_at')
            ->limit(20)
            ->get();

        // Device breakdown
        $deviceBreakdown = $post->analytics()
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->device_type => $item->count];
            });

        // Browser breakdown
        $browserBreakdown = $post->analytics()
            ->selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Country breakdown
        $countryBreakdown = $post->analytics()
            ->selectRaw('country, country_code, COUNT(*) as count')
            ->groupBy('country', 'country_code')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('admin.posts.analytics', compact(
            'post',
            'totalViews',
            'uniqueVisitors',
            'lovesCount',
            'commentsCount',
            'totalCoinsEarned',
            'trafficSources',
            'topReferrers',
            'recentActivity',
            'deviceBreakdown',
            'browserBreakdown',
            'countryBreakdown'
        ));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:posts,id',
            'action' => 'required|in:publish,draft,delete',
        ]);

        $ids = $request->ids;
        $action = $request->action;
        
        // Security: Ensure editors can only touch their own posts
        $query = Post::whereIn('id', $ids);
        if (auth()->user()->isEditor()) {
            $query->where('user_id', auth()->id());
        }

        $count = 0;

        if ($action === 'delete') {
            $posts = $query->get();
            $count = $posts->count();
            foreach ($posts as $post) {
                if ($post->image && !str_starts_with($post->image, 'http')) {
                    Storage::disk('public')->delete($post->image);
                }
                $post->delete();
            }
            $message = "$count posts deleted successfully.";
        } else {
            $isPublished = $action === 'publish';
            $count = $query->update(['is_published' => $isPublished]);
            $status = $isPublished ? 'published' : 'reverted to draft';
            $message = "$count posts $status successfully.";
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}
