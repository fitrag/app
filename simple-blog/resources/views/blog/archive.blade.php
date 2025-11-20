@extends('components.layouts.public')

@section('content')
    <!-- Archive Header -->
    <div class="border-b border-gray-200 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <span class="block text-sm font-bold tracking-wide text-gray-500 uppercase mb-2 font-sans">{{ $subtitle }}</span>
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 font-serif tracking-tight">
                    {{ $title }}
                </h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left Column: Post List -->
            <div class="lg:w-2/3">
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
                                        </div>
                                    </div>
                                </div>

                                <!-- Thumbnail (Right Side) -->
                                @if($post->image)
                                    <a href="{{ route('blog.show', $post->slug) }}" class="flex-shrink-0 w-full sm:w-40 h-40 sm:h-28 bg-gray-100 rounded-md overflow-hidden order-first sm:order-last">
                                        <img src="{{ asset('storage/' . $post->image) }}" 
                                             alt="{{ $post->title }}" 
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
