@extends('components.layouts.public', [
    'title' => 'Berita tentang ' . $title . ' - ' . \App\Models\Setting::get('site_title'),
    'metaDescription' => 'Archive of posts for ' . $title,
    'ogTitle' => 'Berita tentang ' . $title,
    'ogDescription' => 'Archive of posts for ' . $title,
    'ogType' => 'website',
    'canonicalUrl' => url()->current()
])

@section('content')
    <!-- Archive Header -->
    <div class="relative bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="text-center space-y-5">
                <!-- Subtitle -->
                <div class="flex items-center justify-center gap-2 text-xs font-medium tracking-wider text-gray-500 uppercase">
                    <span>{{ $subtitle }}</span>
                </div>
                
                <!-- Title -->
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 font-serif tracking-tight">
                    {{ $type === 'tag' ? '#' : '' }}{{ $title }}
                </h1>
                
                @auth
                    @if(isset($model) && isset($type))
                        <div x-data="{
                            following: {{ auth()->user()->interests()->where('category_id', $type === 'category' ? $model->id : null)->exists() || auth()->user()->followedTags()->where('tag_id', $type === 'tag' ? $model->id : null)->exists() ? 'true' : 'false' }},
                            followersCount: {{ $model->followers()->count() }},
                            toggleFollow() {
                                const url = this.following 
                                    ? '{{ $type === 'category' ? route('categories.unfollow', $model) : route('tags.unfollow', $model) }}'
                                    : '{{ $type === 'category' ? route('categories.follow', $model) : route('tags.follow', $model) }}';
                                const method = this.following ? 'DELETE' : 'POST';
                                
                                fetch(url, {
                                    method: method,
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        this.following = data.is_following;
                                        this.followersCount = data.followers_count;
                                        window.Toast.fire({
                                            icon: 'success',
                                            title: data.message
                                        });
                                    }
                                });
                            }
                        }" class="flex flex-col items-center gap-3 pt-1">
                            <!-- Follow Button -->
                            <button @click="toggleFollow()" 
                                    class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-sm font-medium transition-all duration-200 font-sans"
                                    :class="following ? 'bg-gray-100 text-gray-900 hover:bg-gray-200' : 'bg-gray-900 text-white hover:bg-gray-800'">
                                <span x-text="following ? 'Following' : 'Follow'"></span>
                            </button>
                            
                            <!-- Stats Row -->
                            <div class="flex items-center gap-4 text-xs text-gray-500 font-sans">
                                @if(isset($postsCount))
                                    <div>
                                        <span class="font-semibold text-gray-700">{{ number_format($postsCount) }}</span>
                                        <span>{{ $postsCount === 1 ? ' post' : ' posts' }}</span>
                                    </div>
                                    <span class="text-gray-300">•</span>
                                @endif
                                <div>
                                    <span x-text="followersCount" class="font-semibold text-gray-700"></span>
                                    <span x-text="followersCount === 1 ? ' follower' : ' followers'"></span>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Post Count for non-authenticated users -->
                    @if(isset($postsCount))
                        <p class="text-sm text-gray-500 font-sans">
                            {{ number_format($postsCount) }} {{ $postsCount === 1 ? 'post' : 'posts' }}
                        </p>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left Column: Post List -->
            <div class="lg:w-2/3">
                <!-- Popular Post This Week -->
                @if(isset($popularPost) && $popularPost)
                    <div class="mb-12">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <h2 class="text-lg font-bold text-gray-900 font-sans">Popular This Week</h2>
                        </div>
                        
                        <a href="{{ route('blog.show', $popularPost->slug) }}" class="group block relative overflow-hidden rounded-xl">
                            <!-- Background Image -->
                            <div class="relative h-96 sm:h-[500px]">
                                @if($popularPost->image)
                                    <img src="{{ str_starts_with($popularPost->image, 'http') ? $popularPost->image : asset('storage/' . $popularPost->image) }}" 
                                         alt="{{ $popularPost->title }}" 
                                         loading="lazy"
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-br from-gray-200 via-gray-100 to-gray-200"></div>
                                @endif
                                
                                <!-- Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                                
                                <!-- Content Overlay -->
                                <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8 space-y-4">
                                    <!-- Category Badge -->
                                    @if($popularPost->category)
                                        <div>
                                            <span class="inline-block px-3 py-1.5 rounded-full bg-white/20 backdrop-blur-sm text-white text-xs font-semibold border border-white/30">
                                                {{ $popularPost->category->name }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <!-- Title -->
                                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white font-serif leading-tight group-hover:text-gray-200 transition-colors">
                                        {{ $popularPost->title }}
                                    </h3>
                                    
                                    <!-- Excerpt -->
                                    <p class="text-gray-200 font-serif text-base sm:text-lg leading-relaxed line-clamp-2 max-w-3xl">
                                        {{ Str::limit(strip_tags($popularPost->content), 150) }}
                                    </p>
                                    
                                    <!-- Meta Info -->
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-300">
                                        <!-- Author -->
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-xs font-bold text-white border border-white/30">
                                                {{ substr($popularPost->user->name, 0, 1) }}
                                            </div>
                                            <span class="font-medium text-white">{{ $popularPost->user->name }}</span>
                                        </div>
                                        
                                        <span class="text-white/50">•</span>
                                        
                                        <!-- Date -->
                                        <time datetime="{{ $popularPost->created_at->toDateString() }}">
                                            {{ $popularPost->created_at->format('M d, Y') }}
                                        </time>
                                        
                                        <span class="text-white/50">•</span>
                                        
                                        <!-- Stats -->
                                        <div class="flex items-center gap-3">
                                            <span>{{ ceil(str_word_count($popularPost->content) / 200) }} min read</span>
                                            <span class="text-white/50">•</span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $popularPost->loves_count }}
                                            </span>
                                            <span class="text-white/50">•</span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                {{ $popularPost->comments_count }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                
                @if($posts->isEmpty())
                    <div class="text-center py-20">
                        <p class="text-gray-500 font-sans">No posts found in this {{ strtolower($subtitle) }}.</p>
                    </div>
                @else
                    <div class="space-y-12">
                        @foreach($posts as $post)
                            <article class="flex flex-col sm:flex-row gap-8 items-start group">
                                <div class="flex-1 min-w-0">
                                    <!-- Author Info -->
                                    <div class="flex items-center gap-2 mb-3">
                                        <a href="{{ route('profile.show', $post->user->id) }}" class="flex items-center gap-2 group/author">
                                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 group-hover/author:opacity-80 transition-opacity">
                                                {{ substr($post->user->name, 0, 1) }}
                                            </div>
                                            <span class="text-sm font-medium text-gray-900 font-sans group-hover/author:underline">{{ $post->user->name }}</span>
                                        </a>
                                        <span class="text-gray-400 text-sm">&middot;</span>
                                        <time datetime="{{ $post->created_at->toDateString() }}" class="text-sm text-gray-500 font-sans">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </time>
                                    </div>

                                    <!-- Title & Excerpt -->
                                    <a href="{{ route('blog.show', $post->slug) }}" class="block group-hover:opacity-90 transition-opacity">
                                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 font-serif leading-tight">
                                            {{ $post->title }}
                                        </h2>
                                        <p class="text-gray-500 font-serif text-base leading-relaxed line-clamp-2 sm:line-clamp-3 mb-4">
                                            {{ Str::limit(strip_tags($post->content), 150) }}
                                        </p>
                                    </a>

                                    <!-- Meta & Actions -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            @if($post->category)
                                                <a href="{{ route('blog.category', $post->category->slug) }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 font-sans hover:bg-gray-200 transition-colors">
                                                    {{ $post->category->name }}
                                                </a>
                                            @endif
                                            <span class="text-xs text-gray-500 font-sans">{{ ceil(str_word_count($post->content) / 200) }} min read</span>

                                            <!-- Love Button -->
                                            <div x-data="{ 
                                                loved: {{ Auth::check() && $post->isLovedBy(Auth::user()) ? 'true' : 'false' }},
                                                lovesCount: {{ $post->loves()->count() }},
                                                toggleLove() {
                                                    if (!{{ Auth::check() ? 'true' : 'false' }}) {
                                                        window.location.href = '{{ route('login') }}';
                                                        return;
                                                    }
                                                    
                                                    fetch('{{ route('posts.love', $post) }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Content-Type': 'application/json',
                                                            'Accept': 'application/json'
                                                        }
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            this.loved = data.is_loved;
                                                            this.lovesCount = data.loves_count;
                                                            window.Toast.fire({
                                                                icon: 'success',
                                                                title: data.message
                                                            });
                                                        }
                                                    });
                                                }
                                            }" class="flex items-center gap-1">
                                                <button @click.prevent="toggleLove()" 
                                                        class="transition-colors duration-200 flex items-center gap-1" 
                                                        :class="{ 'text-red-500': loved, 'text-gray-400 hover:text-gray-900': !loved }"
                                                        title="Love this post">
                                                    <span class="sr-only">Love</span>
                                                    <svg class="w-5 h-5" :fill="loved ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                </button>
                                                <span x-text="lovesCount" class="text-xs font-medium" :class="{ 'text-red-500': loved, 'text-gray-500': !loved }"></span>
                                            </div>

                                            <!-- Comment Count -->
                                            <div class="flex items-center gap-1 text-gray-400 hover:text-gray-600 transition-colors">
                                                <a href="{{ route('blog.show', $post->slug) }}#comments" class="flex items-center gap-1" title="Comments">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                    </svg>
                                                    <span class="text-xs font-medium">{{ $post->comments_count }}</span>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <!-- Bookmark Icon -->
                                        <div x-data="{ 
                                            bookmarked: {{ Auth::check() && $post->isBookmarkedBy(Auth::user()) ? 'true' : 'false' }},
                                            toggleBookmark() {
                                                if (!{{ Auth::check() ? 'true' : 'false' }}) {
                                                    window.location.href = '{{ route('login') }}';
                                                    return;
                                                }
                                                
                                                fetch('{{ route('posts.bookmark', $post) }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Content-Type': 'application/json',
                                                        'Accept': 'application/json'
                                                    }
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        this.bookmarked = data.is_bookmarked;
                                                        window.Toast.fire({
                                                            icon: 'success',
                                                            title: data.message
                                                        });
                                                    }
                                                });
                                            }
                                        }">
                                            <button @click.prevent="toggleBookmark()" 
                                                    class="transition-colors duration-200" 
                                                    :class="{ 'text-yellow-500': bookmarked, 'text-gray-400 hover:text-gray-900': !bookmarked }"
                                                    title="Bookmark this post">
                                                <span class="sr-only">Bookmark</span>
                                                <svg class="w-5 h-5" :fill="bookmarked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Thumbnail (Right Side) -->
                                @if($post->image)
                                    <a href="{{ route('blog.show', $post->slug) }}" class="flex-shrink-0 w-full sm:w-40 h-40 sm:h-28 bg-gray-100 rounded-md overflow-hidden order-first sm:order-last">
                                        <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}" 
                                             alt="{{ $post->title }}" 
                                             loading="lazy"
                                             class="w-full h-full object-cover">
                                    </a>
                                @endif
                            </article>
                            <hr class="border-gray-100">
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>

            <!-- Right Column: Sidebar (Sticky) -->
            <div class="lg:w-1/3 hidden lg:block">
                <div class="sticky top-24 space-y-8">
                    <!-- Discover Topics -->
                    <div>
                        <h3 class="font-sans font-bold text-sm text-gray-900 uppercase tracking-wide mb-4">Discover more of what matters to you</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach(\App\Models\Category::take(8)->get() as $category)
                                <a href="{{ route('blog.category', $category->slug) }}" class="px-4 py-2 rounded border border-gray-200 text-sm text-gray-600 hover:border-gray-900 hover:text-gray-900 transition-colors font-sans">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Popular Tags -->
                    <div>
                        <h3 class="font-sans font-bold text-sm text-gray-900 uppercase tracking-wide mb-4">Popular Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach(\App\Models\Tag::take(10)->get() as $tag)
                                <a href="{{ route('blog.tag', $tag->slug) }}" class="text-sm text-gray-500 hover:text-gray-900 font-sans">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
