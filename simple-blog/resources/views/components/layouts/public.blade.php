<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? \App\Models\Setting::get('site_title', config('app.name', 'Laravel')) }}</title>
    <meta name="description" content="{{ $metaDescription ?? \App\Models\Setting::get('site_description', 'A simple blog') }}">
    <meta name="keywords" content="{{ $metaKeywords ?? \App\Models\Setting::get('site_keywords', 'blog') }}">
    <meta name="author" content="{{ \App\Models\Setting::get('meta_author', '') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Noto+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Noto Serif', serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .font-sans {
            font-family: 'Inter', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-gray-900 antialiased bg-white flex flex-col min-h-screen">

    <!-- Navigation -->
    <nav class="border-b border-gray-200 bg-white sticky top-0 z-50 transition-all duration-300" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('blog.index') }}" class="font-sans text-2xl font-bold tracking-tight text-gray-900">
                        {{ \App\Models\Setting::get('site_title', 'My Blog') }}
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8 font-sans">
                    @foreach($globalMenus as $menu)
                        @if($menu->children->count() > 0)
                            <!-- Dropdown -->
                            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button class="flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors py-2">
                                    {{ $menu->label }}
                                    <svg class="w-3 h-3 ml-1 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute left-1/2 -translate-x-1/2 mt-0 w-48 rounded shadow-lg bg-white border border-gray-100 z-50"
                                     style="display: none;">
                                    <div class="py-1">
                                        @foreach($menu->activeChildren as $child)
                                            <a href="{{ $child->url }}" 
                                               class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                                               @if($child->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                                {{ $child->label }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ $menu->url }}" 
                               class="text-sm text-gray-600 hover:text-gray-900 transition-colors"
                               @if($menu->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                {{ $menu->label }}
                            </a>
                        @endif
                    @endforeach

                    <!-- Auth Buttons -->
                    <div class="flex items-center pl-6 space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Sign in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 text-sm text-white bg-green-600 rounded-full hover:bg-green-700 transition-colors">
                                    Get started
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileOpen = !mobileOpen" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileOpen" 
             class="md:hidden bg-white border-b border-gray-200 absolute w-full font-sans"
             style="display: none;">
            <div class="px-4 pt-2 pb-6 space-y-1">
                @foreach($globalMenus as $menu)
                    @if($menu->children->count() > 0)
                        <div x-data="{ subOpen: false }">
                            <button @click="subOpen = !subOpen" class="w-full flex justify-between items-center px-3 py-3 text-base text-gray-700 hover:bg-gray-50">
                                {{ $menu->label }}
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': subOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="subOpen" class="pl-4 space-y-1 bg-gray-50">
                                @foreach($menu->activeChildren as $child)
                                    <a href="{{ $child->url }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900">
                                        {{ $child->label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $menu->url }}" class="block px-3 py-3 text-base text-gray-700 hover:bg-gray-50">
                            {{ $menu->label }}
                        </a>
                    @endif
                @endforeach
                
                <div class="border-t border-gray-100 mt-4 pt-4 space-y-3 px-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block text-center w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block text-center w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded">Sign in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block text-center w-full px-4 py-2 text-sm text-white bg-green-600 rounded">Get Started</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                     <span class="text-sm font-semibold text-gray-900">
                        {{ \App\Models\Setting::get('site_title', 'My Blog') }}
                    </span>
                </div>
                <div class="flex space-x-6 text-sm text-gray-500">
                    <a href="#" class="hover:text-gray-900">About</a>
                    <a href="#" class="hover:text-gray-900">Help</a>
                    <a href="#" class="hover:text-gray-900">Terms</a>
                    <a href="#" class="hover:text-gray-900">Privacy</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
