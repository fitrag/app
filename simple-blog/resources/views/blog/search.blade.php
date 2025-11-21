@extends('components.layouts.public')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Search Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 font-serif mb-3">
                Search Results
            </h1>
            <p class="text-lg text-gray-600 font-sans">
                Found <span class="font-semibold">{{ $totalResults }}</span> result{{ $totalResults != 1 ? 's' : '' }} for "<span class="font-semibold">{{ $query }}</span>"
            </p>
        </div>

        @if($totalResults > 0)
            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-8" x-data="{ activeTab: 'posts' }">
                <nav class="-mb-px flex space-x-8 font-sans" aria-label="Tabs">
                    <button @click="activeTab = 'posts'" 
                            :class="activeTab === 'posts' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Posts
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs" :class="activeTab === 'posts' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600'">{{ $posts->count() }}</span>
                    </button>
                    <button @click="activeTab = 'users'" 
                            :class="activeTab === 'users' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        People
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs" :class="activeTab === 'users' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600'">{{ $users->count() }}</span>
                    </button>
                    <button @click="activeTab = 'categories'" 
                            :class="activeTab === 'categories' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Categories
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs" :class="activeTab === 'categories' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600'">{{ $categories->count() }}</span>
                    </button>
                    <button @click="activeTab = 'tags'" 
                            :class="activeTab === 'tags' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Tags
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs" :class="activeTab === 'tags' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600'">{{ $tags->count() }}</span>
                    </button>
                </nav>

                <!-- Posts Tab -->
                <div x-show="activeTab === 'posts'" class="py-8">
                    @if($posts->count() > 0)
                        <div class="space-y-8">
                            @foreach($posts as $post)
                                @include('blog.partials.post-item', ['post' => $post])
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-12 font-sans">No posts found</p>
                    @endif
                </div>

                <!-- Users Tab -->
                <div x-show="activeTab === 'users'" style="display: none;" class="py-8">
                    @if($users->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($users as $user)
                                <a href="{{ route('profile.show', $user->id) }}" class="block p-6 bg-white border border-gray-200 rounded-lg hover:border-gray-300 hover:shadow-sm transition">
                                    <div class="flex items-start gap-4">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" loading="lazy" class="w-16 h-16 rounded-full object-cover flex-shrink-0">
                                        @else
                                            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-xl font-bold text-gray-600 flex-shrink-0">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 font-sans truncate">{{ $user->name }}</h3>
                                            <p class="text-sm text-gray-500 font-sans mb-2">{{ ucfirst($user->role) }}</p>
                                            @if($user->bio)
                                                <p class="text-sm text-gray-600 font-sans line-clamp-2">{{ $user->bio }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-12 font-sans">No people found</p>
                    @endif
                </div>

                <!-- Categories Tab -->
                <div x-show="activeTab === 'categories'" style="display: none;" class="py-8">
                    @if($categories->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($categories as $category)
                                <a href="{{ route('blog.category', $category->slug) }}" class="block p-6 bg-white border border-gray-200 rounded-lg hover:border-gray-300 hover:shadow-sm transition">
                                    <h3 class="font-semibold text-gray-900 font-sans mb-3">{{ $category->name }}</h3>
                                    <p class="text-xs text-gray-500 font-sans">{{ $category->posts()->where('is_published', true)->count() }} posts</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-12 font-sans">No categories found</p>
                    @endif
                </div>

                <!-- Tags Tab -->
                <div x-show="activeTab === 'tags'" style="display: none;" class="py-8">
                    @if($tags->count() > 0)
                        <div class="flex flex-wrap gap-3">
                            @foreach($tags as $tag)
                                <a href="{{ route('blog.tag', $tag->slug) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full hover:border-gray-300 hover:shadow-sm transition font-sans">
                                    <span class="text-gray-700">#{{ $tag->name }}</span>
                                    <span class="ml-2 text-xs text-gray-500">{{ $tag->posts()->where('is_published', true)->count() }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-12 font-sans">No tags found</p>
                    @endif
                </div>
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-16">
                <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="mt-6 text-xl font-semibold text-gray-900 font-sans">No results found</h3>
                <p class="mt-2 text-gray-600 font-sans">Try searching with different keywords</p>
                <div class="mt-8">
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition font-sans">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
