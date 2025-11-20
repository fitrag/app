@extends('components.layouts.public')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left Column: Profile Info (Desktop Sticky) -->
            <div class="lg:w-1/3 order-first lg:order-last">
                <div class="lg:sticky lg:top-24">
                    <div class="flex flex-col items-start">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover mb-6">
                        @else
                            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-gray-200 flex items-center justify-center mb-6">
                                <span class="text-4xl font-bold text-gray-600 font-sans">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif

                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-sans mb-2">{{ $user->name }}</h1>
                        <div class="text-sm text-gray-500 font-sans mb-6">
                            {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} &middot; Joined {{ $user->created_at->format('M Y') }}
                        </div>

                        @if($user->bio)
                            <p class="text-gray-600 font-serif text-lg leading-relaxed mb-6">
                                {{ $user->bio }}
                            </p>
                        @endif

                        @if(!Auth::check())
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-full hover:bg-green-700 transition-colors font-sans">
                                Follow
                            </a>
                        @elseif(Auth::id() === $user->id)
                            <a href="{{ route('profile.edit') }}" class="text-sm text-green-600 hover:text-green-700 font-sans font-medium">
                                Edit Profile
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Post List -->
            <div class="lg:w-2/3">
                <div class="border-b border-gray-200 pb-4 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 font-sans">Latest from {{ $user->name }}</h2>
                </div>

                @if($posts->isEmpty())
                    <div class="py-12 text-center">
                        <p class="text-gray-500 font-sans">No posts published yet.</p>
                    </div>
                @else
                    <div class="space-y-12">
                        @foreach($posts as $post)
                            <article class="flex flex-col sm:flex-row gap-8 items-start group">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 text-sm text-gray-500 font-sans mb-2">
                                        <time datetime="{{ $post->created_at->toDateString() }}">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </time>
                                    </div>

                                    <a href="{{ route('blog.show', $post->slug) }}" class="block group-hover:opacity-90 transition-opacity">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2 font-serif leading-tight">
                                            {{ $post->title }}
                                        </h3>
                                        <p class="text-gray-500 font-serif text-base leading-relaxed line-clamp-2 mb-3">
                                            {{ Str::limit(strip_tags($post->content), 140) }}
                                        </p>
                                    </a>

                                    <div class="flex items-center gap-4">
                                        @if($post->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 font-sans">
                                                {{ $post->category->name }}
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-500 font-sans">{{ ceil(str_word_count($post->content) / 200) }} min read</span>
                                    </div>
                                </div>

                                @if($post->image)
                                    <a href="{{ route('blog.show', $post->slug) }}" class="flex-shrink-0 w-full sm:w-32 h-32 sm:h-24 bg-gray-100 rounded-md overflow-hidden order-first sm:order-last">
                                        <img src="{{ asset('storage/' . $post->image) }}" 
                                             alt="{{ $post->title }}" 
                                             class="w-full h-full object-cover">
                                    </a>
                                @endif
                            </article>
                            <hr class="border-gray-100">
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
