@extends('components.layouts.public')

@section('content')
    <div class="py-20 sm:py-32 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full mx-auto text-center">
            <!-- 404 Number -->
            <div class="mb-8">
                <h1 class="text-9xl font-bold text-gray-200 font-serif">404</h1>
            </div>

            <!-- Main Message -->
            <div class="mb-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 font-serif mb-4">
                    Page not found
                </h2>
                <p class="text-lg text-gray-600 font-sans max-w-md mx-auto">
                    Sorry, we couldn't find the page you're looking for. Perhaps you've mistyped the URL or the page has been moved.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('blog.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition font-sans">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Go to Homepage
                </a>
                <button onclick="history.back()" 
                        class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-full hover:bg-gray-50 transition font-sans">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Go Back
                </button>
            </div>

            <!-- Helpful Links -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-500 font-sans mb-4">You might be interested in:</p>
                <div class="flex flex-wrap gap-3 justify-center">
                    @foreach(\App\Models\Category::take(5)->get() as $category)
                        <a href="{{ route('blog.category', $category->slug) }}" 
                           class="px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 hover:border-gray-900 hover:text-gray-900 transition font-sans">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Search -->
            <div class="mt-8">
                <p class="text-sm text-gray-500 font-sans mb-3">Or try searching:</p>
                <form action="{{ route('blog.search') }}" method="GET" class="max-w-md mx-auto">
                    <div class="relative">
                        <input type="text" 
                               name="q" 
                               placeholder="Search for posts..." 
                               class="w-full px-6 py-3 pl-14 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent font-sans">
                        <svg class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
