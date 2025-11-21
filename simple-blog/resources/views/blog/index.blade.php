@extends('components.layouts.public')

@section('content')
    <!-- Minimalist Hero / Welcome -->
    @if(\App\Models\Setting::get('hero_show', '1'))
        <div class="border-b border-gray-200 bg-yellow-50/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
                <div class="max-w-3xl">
                    <h1 class="font-serif text-5xl sm:text-7xl font-medium text-gray-900 tracking-tight mb-6">
                        {{ \App\Models\Setting::get('hero_title', 'Stay curious.') }}
                    </h1>
                    <p class="font-sans text-xl text-gray-600 leading-relaxed mb-8 max-w-xl">
                        {{ \App\Models\Setting::get('hero_description', \App\Models\Setting::get('site_description', 'Discover stories, thinking, and expertise from writers on any topic.')) }}
                    </p>
                    @if(!Auth::check())
                        <a href="{{ route('register') }}" class="inline-block px-8 py-3 text-lg font-medium text-white bg-gray-900 rounded-full hover:bg-gray-800 transition-colors font-sans">
                            Start reading
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(isset($showInterestModal) && $showInterestModal)
        <x-interest-modal :show="true" />
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left Column: Post List -->
            <div class="lg:w-2/3">
                <!-- Feed Tabs -->
                @auth
                    <div class="flex items-center border-b border-gray-200 mb-8">
                        <a href="{{ route('blog.index', ['feed' => 'latest']) }}" 
                           class="pb-4 px-4 text-sm font-medium border-b-2 transition-colors {{ ($feed ?? '') === 'latest' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Latest
                        </a>
                        <a href="{{ route('blog.index', ['feed' => 'foryou']) }}" 
                           class="pb-4 px-4 text-sm font-medium border-b-2 transition-colors {{ ($feed ?? 'foryou') === 'foryou' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            For You
                        </a>
                        <a href="{{ route('blog.index', ['feed' => 'followed']) }}" 
                           class="pb-4 px-4 text-sm font-medium border-b-2 transition-colors {{ ($feed ?? '') === 'followed' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Following
                        </a>
                    </div>
                @endauth

                @if($posts->isEmpty())
                    <div class="text-center py-20">
                        <p class="text-gray-500 font-sans">No posts published yet.</p>
                    </div>
                @else
                    <div id="posts-container" class="space-y-12">
                        @foreach($posts as $post)
                            @include('blog.partials.post-item')
                        @endforeach
                    </div>

                    <!-- Load More Button -->
                    @if($posts->hasMorePages())
                        <div x-data="loadMore('{{ $posts->nextPageUrl() }}')" class="mt-12 text-center">
                            <button @click="loadPosts" 
                                    x-show="nextUrl" 
                                    :disabled="isLoading"
                                    class="inline-flex items-center px-8 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors font-sans disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isLoading">Load More</span>
                                <span x-show="isLoading" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Loading...
                                </span>
                            </button>
                        </div>

                        <script>
                            document.addEventListener('alpine:init', () => {
                                Alpine.data('loadMore', (initialNextUrl) => ({
                                    nextUrl: initialNextUrl,
                                    isLoading: false,
                                    async loadPosts() {
                                        if (!this.nextUrl || this.isLoading) return;
                                        this.isLoading = true;
                                        try {
                                            const response = await fetch(this.nextUrl, {
                                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                            });
                                            const data = await response.json();
                                            document.getElementById('posts-container').insertAdjacentHTML('beforeend', data.html);
                                            this.nextUrl = data.nextUrl;
                                        } catch (error) {
                                            console.error('Error loading posts:', error);
                                        } finally {
                                            this.isLoading = false;
                                        }
                                    }
                                }));
                            });
                        </script>
                    @endif
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

                    <!-- Hot Categories This Week -->
                    @if($hotCategories->isNotEmpty())
                        <div class="border-t border-gray-200 pt-8">
                            <h3 class="font-sans font-bold text-sm text-gray-900 uppercase tracking-wide mb-4 flex items-center gap-2">
                                <span>🔥</span>
                                <span>Hot This Week</span>
                            </h3>
                            <div class="space-y-2">
                                @foreach($hotCategories as $index => $category)
                                    <a href="{{ route('blog.category', $category->slug) }}" class="group flex items-center gap-3 py-2 hover:bg-gray-50 rounded-lg transition-colors">
                                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center text-xs font-bold text-gray-400 group-hover:text-gray-900 transition-colors">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="font-medium text-sm text-gray-700 group-hover:text-gray-900 transition-colors font-sans">
                                            {{ $category->name }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Popular Posts This Week -->
                    @if($popularPosts->isNotEmpty())
                        <div class="border-t border-gray-200 pt-8">
                            <h3 class="font-sans font-bold text-sm text-gray-900 uppercase tracking-wide mb-4">Popular This Week</h3>
                            <div class="space-y-4">
                                @foreach($popularPosts as $index => $popularPost)
                                    <a href="{{ route('blog.show', $popularPost->slug) }}" class="group block">
                                        <div class="flex gap-4">
                                            <span class="text-2xl font-bold text-gray-200 group-hover:text-gray-300 transition-colors font-serif flex-shrink-0">
                                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                            </span>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-xs font-medium text-gray-900 font-sans">{{ $popularPost->user->name }}</span>
                                                </div>
                                                <h4 class="font-bold text-sm text-gray-900 group-hover:text-gray-600 transition-colors line-clamp-2 font-serif leading-tight mb-1">
                                                    {{ $popularPost->title }}
                                                </h4>
                                                <div class="flex items-center gap-2 text-xs text-gray-500 font-sans">
                                                    <span>{{ $popularPost->created_at->format('M d') }}</span>
                                                    <span>•</span>
                                                    <span>{{ ceil(str_word_count($popularPost->content) / 200) }} min read</span>
                                                    <span>•</span>
                                                    <span>{{ number_format($popularPost->views) }} views</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
