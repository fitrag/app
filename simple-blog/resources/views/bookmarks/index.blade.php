@extends('components.layouts.public')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900 font-serif">My Bookmarks</h1>
        </div>

        @if($bookmarks->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No bookmarks yet</h3>
                <p class="text-gray-500 mb-6">Save posts you want to read later by clicking the bookmark icon.</p>
                <a href="{{ route('blog.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Explore Posts
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($bookmarks as $post)
                    <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                        @if($post->image)
                            <a href="{{ route('blog.show', $post->slug) }}" class="block h-48 overflow-hidden">
                                <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                            </a>
                        @endif
                        
                        <div class="p-6 flex flex-col flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs text-gray-500 font-sans">{{ $post->created_at->format('M d, Y') }}</span>
                                @if($post->category)
                                    <span class="text-gray-300">&middot;</span>
                                    <a href="{{ route('blog.category', $post->category->slug) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 uppercase tracking-wide">
                                        {{ $post->category->name }}
                                    </a>
                                @endif
                            </div>
                            
                            <h2 class="text-xl font-bold text-gray-900 font-sans mb-3 line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline decoration-2 decoration-gray-300 underline-offset-4">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            
                            <p class="text-gray-500 font-serif mb-4 line-clamp-3 flex-1">
                                {{ Str::limit(strip_tags($post->content), 150) }}
                            </p>
                            
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $post->user->name }}</span>
                                </div>
                                
                                <div x-data="{ 
                                    bookmarked: true,
                                    toggleBookmark() {
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
                                                if (!this.bookmarked) {
                                                    window.location.reload();
                                                }
                                            }
                                        });
                                    }
                                }">
                                    <button @click="toggleBookmark()" class="text-yellow-500 hover:text-yellow-600 transition-colors" title="Remove bookmark">
                                        <svg class="w-5 h-5" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $bookmarks->links() }}
            </div>
        @endif
    </div>
@endsection
