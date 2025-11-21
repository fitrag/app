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

    @stack('meta')

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

                    <!-- Live Search Box -->
                    <div class="relative" x-data="{
                        query: '{{ request('q') }}',
                        suggestions: [],
                        showSuggestions: false,
                        selectedIndex: -1,
                        loading: false,
                        
                        async fetchSuggestions() {
                            if (this.query.length < 2) {
                                this.suggestions = [];
                                this.showSuggestions = false;
                                return;
                            }
                            
                            this.loading = true;
                            try {
                                const response = await fetch(`{{ route('api.search.suggestions') }}?q=${encodeURIComponent(this.query)}`);
                                this.suggestions = await response.json();
                                this.showSuggestions = this.suggestions.length > 0;
                                this.selectedIndex = -1;
                            } catch (error) {
                                console.error('Search error:', error);
                            } finally {
                                this.loading = false;
                            }
                        },
                        
                        selectSuggestion(url) {
                            window.location.href = url;
                        },
                        
                        handleKeydown(event) {
                            if (!this.showSuggestions) return;
                            
                            if (event.key === 'ArrowDown') {
                                event.preventDefault();
                                this.selectedIndex = Math.min(this.selectedIndex + 1, this.suggestions.length - 1);
                            } else if (event.key === 'ArrowUp') {
                                event.preventDefault();
                                this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
                            } else if (event.key === 'Enter' && this.selectedIndex >= 0) {
                                event.preventDefault();
                                this.selectSuggestion(this.suggestions[this.selectedIndex].url);
                            }
                        },
                        
                        getTypeIcon(type) {
                            const icons = {
                                post: '📄',
                                user: '👤',
                                category: '📁',
                                tag: '🏷️'
                            };
                            return icons[type] || '•';
                        },
                        
                        getTypeLabel(type) {
                            const labels = {
                                post: 'Post',
                                user: 'People',
                                category: 'Category',
                                tag: 'Tag'
                            };
                            return labels[type] || type;
                        }
                    }" @click.away="showSuggestions = false">
                        <form action="{{ route('blog.search') }}" method="GET">
                            <input type="text" 
                                   name="q" 
                                   x-model="query"
                                   @input.debounce.300ms="fetchSuggestions()"
                                   @keydown="handleKeydown($event)"
                                   @focus="if (query.length >= 2 && suggestions.length > 0) showSuggestions = true"
                                   placeholder="Search posts..." 
                                   autocomplete="off"
                                   class="w-64 px-4 py-2 pl-10 text-sm border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            
                            <!-- Loading Spinner -->
                            <svg x-show="loading" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </form>
                        
                        <!-- Suggestions Dropdown -->
                        <div x-show="showSuggestions" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute top-full mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 max-h-96 overflow-y-auto"
                             style="display: none;">
                            <template x-for="(suggestion, index) in suggestions" :key="index">
                                <a :href="suggestion.url"
                                   @mouseenter="selectedIndex = index"
                                   :class="selectedIndex === index ? 'bg-gray-50' : ''"
                                   class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors cursor-pointer">
                                    <span class="text-lg" x-text="getTypeIcon(suggestion.type)"></span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="suggestion.title"></p>
                                        <p class="text-xs text-gray-500" x-text="getTypeLabel(suggestion.type)"></p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </template>
                            
                            <!-- View All Results -->
                            <div class="border-t border-gray-100 mt-2 pt-2">
                                <a :href="`{{ route('blog.search') }}?q=${encodeURIComponent(query)}`"
                                   class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    View all results
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center pl-6 space-x-4">
                        @auth
                            <!-- User Dropdown -->
                            <div class="relative ml-3" x-data="{ open: false }" @click.away="open = false">
                                <div>
                                    <button @click="open = !open" type="button" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="sr-only">Open user menu</span>
                                        @if(Auth::user()->avatar)
                                            <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold">
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </button>
                                </div>
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                                     role="menu" 
                                     aria-orientation="vertical" 
                                     aria-labelledby="user-menu-button" 
                                     tabindex="-1"
                                     style="display: none;">
                                    
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-0">Dashboard</a>
                                    <a href="{{ route('profile.show', Auth::id()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-1">My Profile</a>
                                    <a href="{{ route('bookmarks.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-2">My Bookmarks</a>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-3">
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
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
                
                <!-- Mobile Search -->
                <div class="pt-4 pb-2">
                    <form action="{{ route('blog.search') }}" method="GET" class="relative px-3">
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}"
                               placeholder="Search..." 
                               class="w-full px-4 py-2 pl-10 text-sm border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        <svg class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </form>
                </div>
                
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
