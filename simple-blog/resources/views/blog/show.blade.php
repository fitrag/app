@extends('components.layouts.public', [
    'title' => $post->title . ' - ' . \App\Models\Setting::get('site_title'),
    'metaDescription' => Str::limit(strip_tags($post->content), 160),
    'metaKeywords' => $post->tags->pluck('name')->implode(', ')
])

@push('meta')
    @include('blog.partials.seo', ['post' => $post])
@endpush



@section('content')
    <!-- Reading Progress Bar -->
    <div x-data="{ width: '0%' }" 
         x-on:scroll.window="width = (window.pageYOffset / (document.documentElement.scrollHeight - window.innerHeight)) * 100 + '%'"
         class="fixed top-0 left-0 h-1 bg-gray-900 z-50 transition-all duration-150 ease-out"
         :style="'width: ' + width"></div>

    @if($post->enable_font_adjuster)
    <style>
        .font-size-small .prose { font-size: 1rem; line-height: 1.75; }
        .font-size-normal .prose { font-size: 1.125rem; line-height: 1.75; }
        .font-size-large .prose { font-size: 1.25rem; line-height: 1.8; }
    </style>

    <script>
        function fontSizeAdjuster() {
            return {
                size: localStorage.getItem('post_font_size') || 'normal',
                setSize(newSize) {
                    this.size = newSize;
                    localStorage.setItem('post_font_size', newSize);
                    this.applySize();
                },
                applySize() {
                    const article = document.querySelector('article');
                    if (article) {
                        article.className = article.className.replace(/font-size-\w+/g, '');
                        article.classList.add('font-size-' + this.size);
                    }
                },
                init() {
                    this.$nextTick(() => this.applySize());
                }
            }
        }
    </script>
    @endif

    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 font-size-normal">
        <!-- Header -->
        <header class="mb-10 text-center mx-auto">
            @if($post->enable_font_adjuster)
            <!-- Font Size Adjuster -->
            <div x-data="fontSizeAdjuster()" class="fixed top-20 right-4 z-30 bg-white rounded-lg shadow-lg border border-gray-200 p-2 flex flex-col gap-1">
                <button @click="setSize('small')" 
                        :class="size === 'small' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded text-xs font-medium transition-colors"
                        title="Small font">
                    A-
                </button>
                <button @click="setSize('normal')" 
                        :class="size === 'normal' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded text-sm font-medium transition-colors"
                        title="Normal font">
                    A
                </button>
                <button @click="setSize('large')" 
                        :class="size === 'large' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded text-base font-medium transition-colors"
                        title="Large font">
                    A+
                </button>
            </div>
            @endif

            <div class="flex items-center justify-center gap-3 text-sm sm:text-base text-gray-500 font-sans mb-6">
                <a href="{{ route('blog.category', $post->category->slug) }}" class="text-gray-900 font-medium hover:underline uppercase tracking-wider text-xs sm:text-sm">
                    {{ $post->category->name }}
                </a>
                <span class="text-gray-300">&middot;</span>
                <time datetime="{{ $post->created_at->toDateString() }}">
                    {{ $post->created_at->format('M d, Y') }}
                </time>
                <span class="text-gray-300">&middot;</span>
                <span>{{ ceil(str_word_count($post->content) / 200) }} min read</span>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 font-serif leading-tight mb-8 tracking-tight">
                {{ $post->title }}
            </h1>

            <!-- Author & Actions Bar -->
            <div class="flex items-center justify-between w-full mt-8 border-t border-b border-gray-100 py-6">
                <!-- Author Profile (Left) -->
                <a href="{{ route('profile.show', $post->user->id) }}" class="group flex items-center gap-3 text-left">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gray-200 flex items-center justify-center text-base sm:text-lg font-bold text-gray-600 group-hover:opacity-80 transition-opacity">
                        {{ substr($post->user->name, 0, 1) }}
                    </div>
                    <div>
                        <span class="block font-sans text-base font-bold text-gray-900 group-hover:underline">{{ $post->user->name }}</span>
                        <span class="block text-xs text-gray-500">Author</span>
                    </div>
                </a>

                <!-- Action Buttons (Right) -->
                <div class="flex items-center gap-4 sm:gap-6">
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
                    }" class="flex items-center gap-2">
                        <button @click="toggleLove()" 
                                class="group flex items-center gap-2 px-3 py-1.5 rounded-full transition-all duration-200" 
                                :class="{ 'bg-red-50 text-red-500': loved, 'hover:bg-gray-50 text-gray-500 hover:text-gray-700': !loved }"
                                title="Love this post">
                            <svg class="w-6 h-6 transition-transform group-active:scale-110" :fill="loved ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span x-text="lovesCount" class="font-medium text-lg"></span>
                        </button>
                    </div>

                    <div class="w-px h-6 bg-gray-200"></div>

                    <!-- Bookmark Button -->
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
                        <button @click="toggleBookmark()" 
                                class="group p-2 rounded-full transition-all duration-200" 
                                :class="{ 'text-yellow-500 bg-yellow-50': bookmarked, 'text-gray-400 hover:text-gray-600 hover:bg-gray-50': !bookmarked }"
                                title="Bookmark this post">
                            <svg class="w-6 h-6 transition-transform group-active:scale-110" :fill="bookmarked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="w-px h-6 bg-gray-200"></div>

                    <!-- Share Button -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false"
                                class="group p-2 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-all duration-200" 
                                title="Share this post">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                        </button>
                        
                        <!-- Share Dropdown -->
                        <div x-show="open" 
                             class="absolute right-0 top-full mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                             style="display: none;">
                             @include('blog.partials.share-menu')
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        @if($post->image)
            <figure class="mb-12 -mx-4 sm:mx-0">
                <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}" 
                     alt="{{ $post->title }}" 
                     loading="lazy"
                     class="w-full h-auto sm:rounded-lg object-cover max-h-[500px] shadow-sm">
                @if($post->image_caption)
                    <figcaption class="mt-3 text-center text-sm text-gray-500 font-sans">
                        {{ $post->image_caption }}
                    </figcaption>
                @endif
            </figure>
        @endif

        <!-- Content -->
        <div class="prose prose-lg sm:prose-xl max-w-none prose-gray font-serif prose-headings:font-sans prose-headings:font-bold prose-a:text-gray-900 prose-a:no-underline prose-a:border-b prose-a:border-gray-300 hover:prose-a:border-gray-900 prose-img:rounded-lg">
            <style>
                /* CKEditor Image Caption Styling */
                .prose figure.image {
                    margin: 2rem 0;
                }
                
                .prose figure.image img {
                    margin: 0 auto;
                    border-radius: 0.5rem;
                }
                
                .prose figure.image figcaption {
                    margin-top: 0.75rem;
                    text-align: center;
                    font-size: 0.875rem;
                    line-height: 1.5;
                    color: #6b7280;
                    font-family: system-ui, -apple-system, sans-serif;
                    font-style: italic;
                }
            </style>
            {!! $post->content !!}
        </div>

        <!-- Tags -->
        @if($post->tags->isNotEmpty())
            <div class="mt-12 pt-8 border-t border-gray-100">
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}" class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 text-sm font-sans hover:bg-gray-200 transition-colors">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Comments Section -->
        <div class="mt-16 pt-8 border-t border-gray-200" id="comments">
            <h3 class="text-2xl font-bold text-gray-900 font-sans mb-8">Comments ({{ $post->comments->count() }})</h3>

            @if($post->is_commentable)
                <!-- Comment Form -->
                @auth
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-12">
                        @csrf
                        <div class="mb-4">
                            <label for="content" class="sr-only">Your comment</label>
                            <textarea name="content" 
                                      id="content" 
                                      rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                                      placeholder="Write a comment..."
                                      required></textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                                Post Comment
                            </button>
                        </div>
                    </form>
                @else
                    <div class="bg-gray-50 rounded-lg p-6 mb-12 text-center">
                        <p class="text-gray-600">Please <a href="{{ route('login') }}" class="text-gray-900 font-medium hover:underline">log in</a> to leave a comment.</p>
                    </div>
                @endauth

                <!-- Comments List -->
                <div class="space-y-8">
                    @forelse($post->comments as $comment)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-bold text-gray-900">{{ $comment->user->name }}</h4>
                                    
                                    @if($comment->user->role === 'admin')
                                        <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded">Admin</span>
                                    @endif
                                    
                                    @if($comment->user_id === $post->user_id)
                                        <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded">Author</span>
                                    @endif
                                    
                                    <time datetime="{{ $comment->created_at->toIso8601String() }}" class="text-sm text-gray-500 ml-auto">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </time>
                                </div>
                                <div class="prose prose-sm max-w-none text-gray-700">
                                    <p>{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 italic">No comments yet. Be the first to share your thoughts!</p>
                    @endforelse
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center text-yellow-800">
                    <p>Comments are disabled for this post.</p>
                </div>
            @endif
        </div>

        <!-- Footer / Author Bio -->
        <div class="mt-16 pt-8 border-t border-gray-200">
            <div class="flex items-start gap-6 bg-gray-50 p-8 rounded-xl">
                <a href="{{ route('profile.show', $post->user->id) }}" class="group flex-shrink-0">
                    <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-600 group-hover:opacity-80 transition-opacity">
                        {{ substr($post->user->name, 0, 1) }}
                    </div>
                </a>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 font-sans mb-2">
                        Written by <a href="{{ route('profile.show', $post->user->id) }}" class="hover:underline">{{ $post->user->name }}</a>
                    </h3>
                    <p class="text-gray-600 font-serif text-lg mb-4 leading-relaxed">
                        {{ $post->user->bio ?? 'Sharing stories and insights on this platform.' }}
                    </p>
                    @if(!Auth::check())
                        <a href="{{ route('register') }}" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-700 font-sans">
                            Follow Author
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Author's Other Posts -->
        @if($authorPosts->isNotEmpty())
            <div class="mt-16 pt-16 border-t border-gray-200">
                <h3 class="text-xl font-bold text-gray-900 font-sans mb-8">More from {{ $post->user->name }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($authorPosts as $authorPost)
                        <a href="{{ route('blog.show', $authorPost->slug) }}" class="block group">
                            @if($authorPost->image)
                                <div class="aspect-[16/10] bg-gray-200 rounded mb-4 overflow-hidden">
                                    <img src="{{ str_starts_with($authorPost->image, 'http') ? $authorPost->image : asset('storage/' . $authorPost->image) }}" alt="{{ $authorPost->title }}" loading="lazy" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                </div>
                            @endif
                            <h4 class="text-lg font-bold text-gray-900 font-sans mb-2 group-hover:underline decoration-2 decoration-gray-300 underline-offset-4">
                                {{ $authorPost->title }}
                            </h4>
                            <div class="flex items-center justify-between mt-3">
                                <time class="text-sm text-gray-500 font-sans">{{ $authorPost->created_at->format('M d, Y') }}</time>
                                <div class="flex items-center gap-3 text-gray-400 text-sm">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        <span>{{ $authorPost->loves_count }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <span>{{ $authorPost->comments_count }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Related Posts (Minimal) -->
        @if($relatedPosts->isNotEmpty())
            <div class="mt-16 pt-16 border-t border-gray-200 bg-gray-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 pb-16">
                <div class="max-w-3xl mx-auto">
                    <h3 class="text-lg font-bold text-gray-900 font-sans mb-8">More from {{ \App\Models\Setting::get('site_title', 'My Blog') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($relatedPosts as $related)
                            <a href="{{ route('blog.show', $related->slug) }}" class="block group">
                                @if($related->image)
                                    <div class="aspect-[16/10] bg-gray-200 rounded mb-4 overflow-hidden">
                                        <img src="{{ str_starts_with($related->image, 'http') ? $related->image : asset('storage/' . $related->image) }}" alt="{{ $related->title }}" loading="lazy" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                    </div>
                                @endif
                                <h4 class="text-xl font-bold text-gray-900 font-sans mb-2 group-hover:underline decoration-2 decoration-gray-300 underline-offset-4">
                                    {{ $related->title }}
                                </h4>
                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center gap-2 text-sm text-gray-500 font-sans">
                                        <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                            {{ substr($related->user->name, 0, 1) }}
                                        </div>
                                        <span>{{ $related->user->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-gray-400 text-sm">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            <span>{{ $related->loves_count }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                            <span>{{ $related->comments_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </article>
@endsection
