<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="h-full bg-gray-50">
        <div class="h-full overflow-y-auto">
            <div class="p-8 max-w-7xl mx-auto">
                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Site Settings</h1>
                    <p class="mt-2 text-sm text-gray-600">Manage your blog's general settings and SEO configuration</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-r-lg shadow-sm" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- General Settings Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">General Settings</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Basic information about your blog</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-8 py-8 space-y-6">
                            <!-- Site Title -->
                            <div>
                                <label for="site_title" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Site Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="site_title" 
                                       id="site_title" 
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition @error('site_title') border-red-500 bg-red-50 @enderror" 
                                       value="{{ old('site_title', $settings['site_title']) }}" 
                                       placeholder="My Awesome Blog"
                                       required>
                                @error('site_title')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 mt-2">The main title displayed across your blog</p>
                                @enderror
                            </div>

                            <!-- Site Description -->
                            <div>
                                <label for="site_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Site Description
                                </label>
                                <textarea name="site_description" 
                                          id="site_description" 
                                          rows="4" 
                                          class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition resize-none @error('site_description') border-red-500 bg-red-50 @enderror"
                                          placeholder="A brief description of what your blog is about..."
                                          maxlength="500">{{ old('site_description', $settings['site_description']) }}</textarea>
                                @error('site_description')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 mt-2">A brief description of your blog (maximum 500 characters)</p>
                                @enderror
                            </div>

                            <!-- Posts Per Page -->
                            <div>
                                <label for="posts_per_page" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Posts Per Page <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="posts_per_page" 
                                       id="posts_per_page" 
                                       min="1"
                                       max="100"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition @error('posts_per_page') border-red-500 bg-red-50 @enderror" 
                                       value="{{ old('posts_per_page', $settings['posts_per_page']) }}" 
                                       required>
                                @error('posts_per_page')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 mt-2">Number of posts to display per page on the home page</p>
                                @enderror
                            </div>

                            <!-- Footer Text -->
                            <div>
                                <label for="footer_text" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Footer Text
                                </label>
                                <input type="text" 
                                       name="footer_text" 
                                       id="footer_text" 
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition @error('footer_text') border-red-500 bg-red-50 @enderror" 
                                       value="{{ old('footer_text', $settings['footer_text']) }}"
                                       placeholder="© 2025 My Blog. All rights reserved.">
                                @error('footer_text')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 mt-2">Text displayed in the footer of your blog</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Hero Section Settings Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Hero Section Settings</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Customize the welcome section on the home page</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-8 py-8 space-y-6">
                            <!-- Show Hero Section -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <label for="hero_show" class="text-sm font-semibold text-gray-700">Show Hero Section</label>
                                    <p class="text-sm text-gray-500">Toggle the visibility of the hero section on the home page</p>
                                </div>
                                <label for="hero_show" class="relative inline-block w-12 h-6 transition duration-200 ease-in-out rounded-full cursor-pointer">
                                    <input type="checkbox" 
                                           name="hero_show" 
                                           id="hero_show" 
                                           value="1" 
                                           class="peer sr-only" 
                                           {{ old('hero_show', $settings['hero_show']) == '1' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-900"></div>
                                </label>
                            </div>

                            <!-- Hero Title -->
                            <div>
                                <label for="hero_title" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Hero Title
                                </label>
                                <input type="text" 
                                       name="hero_title" 
                                       id="hero_title" 
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition @error('hero_title') border-red-500 bg-red-50 @enderror" 
                                       value="{{ old('hero_title', $settings['hero_title']) }}" 
                                       placeholder="Stay curious.">
                                @error('hero_title')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Hero Description -->
                            <div>
                                <label for="hero_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Hero Description
                                </label>
                                <textarea name="hero_description" 
                                          id="hero_description" 
                                          rows="3" 
                                          class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition resize-none @error('hero_description') border-red-500 bg-red-50 @enderror"
                                          placeholder="Discover stories, thinking, and expertise from writers on any topic.">{{ old('hero_description', $settings['hero_description']) }}</textarea>
                                @error('hero_description')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">SEO Settings</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Optimize your blog for search engines</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-8 py-8 space-y-6">
                            <!-- Meta Keywords -->
                            <div>
                                <label for="site_keywords" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Meta Keywords
                                </label>
                                <input type="text" 
                                       name="site_keywords" 
                                       id="site_keywords" 
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition @error('site_keywords') border-red-500 bg-red-50 @enderror" 
                                       value="{{ old('site_keywords', $settings['site_keywords']) }}" 
                                       placeholder="blog, articles, news, technology">
                                @error('site_keywords')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 mt-2">Comma-separated keywords that describe your blog content</p>
                                @enderror
                            </div>

                            <!-- Grid Layout for Meta Author and OG Image -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Meta Author -->
                                <div>
                                    <label for="meta_author" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Meta Author
                                    </label>
                                    <input type="text" 
                                           name="meta_author" 
                                           id="meta_author" 
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition @error('meta_author') border-red-500 bg-red-50 @enderror" 
                                           value="{{ old('meta_author', $settings['meta_author']) }}" 
                                           placeholder="John Doe">
                                    @error('meta_author')
                                        <p class="text-red-600 text-sm mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-500 mt-2">Default author name for meta tags</p>
                                    @enderror
                                </div>

                                <!-- OG Image -->
                                <div>
                                    <label for="meta_og_image" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Open Graph Image
                                    </label>
                                    <input type="url" 
                                           name="meta_og_image" 
                                           id="meta_og_image" 
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition @error('meta_og_image') border-red-500 bg-red-50 @enderror" 
                                           value="{{ old('meta_og_image', $settings['meta_og_image']) }}" 
                                           placeholder="https://example.com/og-image.jpg">
                                    @error('meta_og_image')
                                        <p class="text-red-600 text-sm mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-500 mt-2">Image for social media sharing</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- SEO Info Box -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-semibold mb-1">SEO Best Practices</p>
                                        <p>These settings help search engines understand your content better. Make sure to use relevant keywords and provide accurate descriptions.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monetization Settings Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Monetization Settings</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">Configure coin rewards for editors</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-8 py-8 space-y-6">
                            <!-- Coins Per Post -->
                            <div>
                                <label for="coins_per_post" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Coins Per Post <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="coins_per_post" 
                                       id="coins_per_post" 
                                       min="0"
                                       step="0.01"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition @error('coins_per_post') border-red-500 bg-red-50 @enderror" 
                                       value="{{ old('coins_per_post', $settings['coins_per_post'] ?? 10) }}" 
                                       required>
                                @error('coins_per_post')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 mt-2">Number of coins editors earn when creating a new post</p>
                                @enderror
                            </div>

                            <!-- Coins Per 1000 Views -->
                            <div>
                                <label for="coins_per_1000_views" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Coins Per 1000 Views <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="coins_per_1000_views" 
                                       id="coins_per_1000_views" 
                                       min="0"
                                       step="0.01"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition @error('coins_per_1000_views') border-red-500 bg-red-50 @enderror" 
                                       value="{{ old('coins_per_1000_views', $settings['coins_per_1000_views'] ?? 5) }}" 
                                       required>
                                @error('coins_per_1000_views')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 mt-2">Number of coins editors earn for every 1000 views on their posts</p>
                                @enderror
                            </div>

                            <!-- Minimum Posts for Monetization -->
                            <div>
                                <label for="monetization_min_posts" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Minimum Posts for Monetization <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="monetization_min_posts" 
                                       id="monetization_min_posts" 
                                       min="0"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition @error('monetization_min_posts') border-red-500 bg-red-50 @enderror" 
                                       value="{{ old('monetization_min_posts', $settings['monetization_min_posts'] ?? 3) }}" 
                                       required>
                                @error('monetization_min_posts')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 mt-2">Minimum number of published posts required to enable monetization</p>
                                @enderror
                            </div>

                            <!-- Minimum Views for Monetization -->
                            <div>
                                <label for="monetization_min_views" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Minimum Views for Monetization <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="monetization_min_views" 
                                       id="monetization_min_views" 
                                       min="0"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent focus:bg-white transition @error('monetization_min_views') border-red-500 bg-red-50 @enderror" 
                                       value="{{ old('monetization_min_views', $settings['monetization_min_views'] ?? 100) }}" 
                                       required>
                                @error('monetization_min_views')
                                    <p class="text-red-600 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 mt-2">Minimum total views required to enable monetization</p>
                                @enderror
                            </div>

                            <!-- Monetization Info Box -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <a href="{{ route('dashboard') }}" class="px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 transition shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
