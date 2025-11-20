<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pages - {{ \App\Models\Setting::get('site_title', config('app.name', 'Laravel')) }}</title>
    <meta name="description" content="Browse all pages">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased">
    <!-- Navigation -->
    <nav class="border-b border-gray-200 bg-white sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6 sm:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('blog.index') }}" class="text-2xl font-bold text-gray-900 tracking-tight">
                        {{ \App\Models\Setting::get('site_title', 'My Blog') }}
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    @foreach($globalMenus as $menu)
                        <a href="{{ $menu->url }}" 
                           class="text-sm text-gray-600 hover:text-gray-900"
                           @if($menu->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>
                            {{ $menu->label }}
                        </a>
                    @endforeach
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Sign in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm bg-gray-900 text-white px-4 py-2 rounded-full hover:bg-gray-800 transition">Get started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-6 sm:px-8 py-12">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Pages</h1>
            <p class="text-lg text-gray-600">Browse all available pages</p>
        </div>

        @if($pages->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No pages available</h3>
                <p class="text-gray-500">Check back later for new content.</p>
            </div>
        @else
            <!-- Pages Grid -->
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($pages as $page)
                    <article class="group">
                        <a href="{{ route('page.show', $page->slug) }}" class="block">
                            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
                                <!-- Icon -->
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-gray-900 transition-colors">
                                    <svg class="w-6 h-6 text-gray-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>

                                <!-- Title -->
                                <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-gray-600 transition">
                                    {{ $page->title }}
                                </h2>

                                <!-- Description -->
                                @if($page->meta_description)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {{ $page->meta_description }}
                                    </p>
                                @else
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {{ Str::limit(strip_tags($page->content), 120) }}
                                    </p>
                                @endif

                                <!-- Meta -->
                                <div class="flex items-center text-xs text-gray-500">
                                    <span>Updated {{ $page->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-16 border-t border-gray-200 pt-8">
                {{ $pages->links() }}
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 mt-20 py-8">
        <div class="max-w-5xl mx-auto px-6 sm:px-8">
            <div class="flex justify-between items-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_title', 'My Blog') }}. {{ \App\Models\Setting::get('footer_text', 'All rights reserved.') }}</p>
                <div class="flex space-x-6">
                    <a href="#" class="hover:text-gray-900">About</a>
                    <a href="#" class="hover:text-gray-900">Terms</a>
                    <a href="#" class="hover:text-gray-900">Privacy</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
