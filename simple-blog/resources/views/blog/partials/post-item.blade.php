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
