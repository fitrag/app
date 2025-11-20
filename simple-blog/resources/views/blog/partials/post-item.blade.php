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
            </div>
            
            <!-- Bookmark Icon (Placeholder) -->
            <button class="text-gray-400 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Thumbnail (Right Side) -->
    @if($post->image)
        <a href="{{ route('blog.show', $post->slug) }}" class="flex-shrink-0 w-full sm:w-40 h-40 sm:h-28 bg-gray-100 rounded-md overflow-hidden order-first sm:order-last">
            <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}" 
                 alt="{{ $post->title }}" 
                 class="w-full h-full object-cover">
        </a>
    @endif
</article>
<hr class="border-gray-100">
