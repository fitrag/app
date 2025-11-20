@extends('components.layouts.public')

@section('content')
    <article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <!-- Header -->
        <header class="mb-10">
            <h1 class="text-3xl sm:text-5xl font-bold text-gray-900 font-serif leading-tight mb-8">
                {{ $post->title }}
            </h1>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('profile.show', $post->user->id) }}" class="group">
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-lg font-bold text-gray-600 group-hover:opacity-80 transition-opacity">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                    </a>
                    <div>
                        <a href="{{ route('profile.show', $post->user->id) }}" class="block font-sans font-medium text-gray-900 hover:underline">{{ $post->user->name }}</a>
                        <div class="flex items-center gap-2 text-sm text-gray-500 font-sans">
                            <time datetime="{{ $post->created_at->toDateString() }}">
                                {{ $post->created_at->format('M d, Y') }}
                            </time>
                            <span>&middot;</span>
                            <span>{{ ceil(str_word_count($post->content) / 200) }} min read</span>
                        </div>
                    </div>
                </div>

                <!-- Share / Actions (Placeholder) -->
                <div class="flex gap-4 text-gray-400">
                    <button class="hover:text-gray-900"><span class="sr-only">Share</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg></button>
                    <button class="hover:text-gray-900"><span class="sr-only">Bookmark</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg></button>
                </div>
            </div>
        </header>

        <!-- Featured Image (Below Title) -->
        @if($post->image)
            <figure class="mb-12 -mx-4 sm:mx-0">
                <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}" 
                     alt="{{ $post->title }}" 
                     class="w-full h-auto sm:rounded-lg object-cover max-h-[500px]">
                @if($post->image_caption)
                    <figcaption class="mt-3 text-center text-sm text-gray-500 font-sans">
                        {{ $post->image_caption }}
                    </figcaption>
                @endif
            </figure>
        @endif

        <!-- Content -->
        <div class="prose prose-lg sm:prose-xl max-w-none prose-gray font-serif prose-headings:font-sans prose-headings:font-bold prose-a:text-gray-900 prose-a:no-underline prose-a:border-b prose-a:border-gray-300 hover:prose-a:border-gray-900 prose-img:rounded-lg">
            {!! $post->content !!}
        </div>

        <!-- Tags -->
        @if($post->tags->isNotEmpty())
            <div class="mt-12 pt-8">
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}" class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 text-sm font-sans hover:bg-gray-200 transition-colors">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Footer / Author Bio -->
        <div class="mt-16 pt-8 border-t border-gray-200">
            <div class="flex items-start gap-4">
                <a href="{{ route('profile.show', $post->user->id) }}" class="group">
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-xl font-bold text-gray-600 flex-shrink-0 group-hover:opacity-80 transition-opacity">
                        {{ substr($post->user->name, 0, 1) }}
                    </div>
                </a>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 font-sans mb-1">
                        Written by <a href="{{ route('profile.show', $post->user->id) }}" class="hover:underline">{{ $post->user->name }}</a>
                    </h3>
                    <p class="text-gray-500 font-serif text-base mb-4">
                        {{ $post->user->bio ?? 'Sharing stories and insights on this platform.' }}
                    </p>
                    @if(!Auth::check())
                        <a href="{{ route('register') }}" class="text-sm font-medium text-green-600 hover:text-green-700 font-sans">
                            Follow
                        </a>
                    @endif
                </div>
            </div>
        </div>

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
                                        <img src="{{ str_starts_with($related->image, 'http') ? $related->image : asset('storage/' . $related->image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                                    </div>
                                @endif
                                <h4 class="text-xl font-bold text-gray-900 font-sans mb-2 group-hover:underline decoration-2 decoration-gray-300 underline-offset-4">
                                    {{ $related->title }}
                                </h4>
                                <div class="flex items-center gap-2 text-sm text-gray-500 font-sans">
                                    <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                        {{ substr($related->user->name, 0, 1) }}
                                    </div>
                                    <span>{{ $related->user->name }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </article>
@endsection
