@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Novel<span class="text-gray-800">Verse</span>
                        </h1>
                    </div>
                </div>
                
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-indigo-600 font-medium border-b-2 border-indigo-600">Beranda</a>
                    <a href="#" class="px-4 py-2 text-gray-600 hover:text-indigo-600 font-medium hover:border-b-2 hover:border-indigo-600 transition-colors">Eksplor</a>
                    <a href="#" class="px-4 py-2 text-gray-600 hover:text-indigo-600 font-medium hover:border-b-2 hover:border-indigo-600 transition-colors">Bookmark</a>
                    <a href="#" class="px-4 py-2 text-gray-600 hover:text-indigo-600 font-medium hover:border-b-2 hover:border-indigo-600 transition-colors">Komunitas</a>
                </nav>
                
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="hidden md:block relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            placeholder="Cari novel..." 
                            class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-64"
                        >
                    </div>
                    
                    <!-- Notifications -->
                    <button class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded-lg relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    
                    <!-- User Profile -->
                    <div class="relative">
                        <button class="flex items-center space-x-2 focus:outline-none group">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-medium">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="hidden md:block">
                                <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs">Pembaca</span>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Selamat Datang Kembali, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                    <p class="text-gray-600 mt-1">
                        Nikmati ribuan novel terbaik dari penulis Indonesia dan dunia
                    </p>
                </div>
                
                <div class="flex space-x-3">
                    <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Bookmark
                    </button>
                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Eksplor Novel
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-indigo-100 text-indigo-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Novel Dibaca</p>
                        <p class="text-2xl font-bold text-gray-900">24</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-purple-100 text-purple-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Bookmark</p>
                        <p class="text-2xl font-bold text-gray-900">12</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-pink-100 text-pink-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Komunitas</p>
                        <p class="text-2xl font-bold text-gray-900">8</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-blue-100 text-blue-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Koleksi</p>
                        <p class="text-2xl font-bold text-gray-900">15</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Continue Reading -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Lanjutkan Membaca</h2>
                    <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full">3 Novel</span>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                            <div class="w-16 h-20 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 truncate">Judul Novel Panjang yang Menarik {{ $i }}</h3>
                                <p class="text-sm text-gray-600 truncate">Penulis {{ $i }}</p>
                                <div class="mt-2">
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                        <span>Bab {{ rand(5, 15) }} dari {{ rand(20, 30) }}</span>
                                        <span>{{ rand(25, 75) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full" style="width: {{ rand(25, 75) }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <button class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-medium rounded-lg shadow hover:shadow-lg transition-all">
                                Lanjut
                            </button>
                        </div>
                        @endfor
                    </div>
                    <button class="w-full mt-4 py-3 text-center text-indigo-600 hover:text-indigo-800 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Lihat Semua Novel yang Dibaca
                    </button>
                </div>
            </div>

            <!-- Reading Streak -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Streak Membaca</h2>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 flex items-center justify-center mb-4">
                            <span class="text-2xl font-bold text-white">7</span>
                        </div>
                        <h3 class="font-semibold text-gray-900">Hari Berturut-turut</h3>
                        <p class="text-sm text-gray-600">Pertahankan streak-mu!</p>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Target Minggu Ini</span>
                            <span class="text-sm font-medium text-gray-900">5/7 hari</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: 71%"></div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h4 class="font-medium text-gray-900 mb-3">Pencapaian</h4>
                        <div class="flex space-x-2">
                            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center" title="Pembaca Aktif">
                                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L13.09 8.26L20 9L13.09 9.74L12 16L10.91 9.74L4 9L10.91 8.26L12 2Z"></path>
                                </svg>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center" title="100 Novel Dibaca">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18 2H12V4H18V20H12V22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.1 2 18 2ZM10 4V8L2 8L2 20C2 21.1 2.9 22 4 22H10V22.5C10 23.05 10.45 23.5 11 23.5S12 23.05 12 22.5V22H16C17.1 22 18 21.1 18 20V16H10V4ZM16 20H12V18H16V20ZM16 17H12V15H16V17ZM16 14H12V12H16V14ZM16 11H12V9H16V11ZM16 8H12V6H16V8Z"></path>
                                </svg>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center opacity-50" title="Pembaca Terbaik">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L13.09 8.26L20 9L13.09 9.74L12 16L10.91 9.74L4 9L10.91 8.26L12 2Z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Novels -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Novel Populer Minggu Ini</h2>
                <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Lihat Semua</a>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="group cursor-pointer">
                        <div class="relative overflow-hidden rounded-xl mb-3">
                            <div class="w-full h-48 bg-gradient-to-br from-pink-400 to-rose-500 rounded-xl"></div>
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-full text-xs font-medium">
                                #{{ $i }}
                            </div>
                            <div class="absolute bottom-3 left-3 right-3">
                                <button class="w-full py-2 bg-white/90 backdrop-blur-sm text-gray-900 font-medium rounded-lg hover:bg-white transition-colors opacity-0 group-hover:opacity-100 transition-opacity">
                                    Baca Sekarang
                                </button>
                            </div>
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Judul Novel Populer {{ $i }}</h3>
                        <p class="text-sm text-gray-600">Penulis {{ $i }}</p>
                        <div class="flex items-center mt-1">
                            <div class="flex text-yellow-400">
                                @for($j = 0; $j < 5; $j++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500 ml-2">({{ rand(100, 999) }})</span>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @for($i = 1; $i <= 5; $i++)
                        <div class="flex items-start space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                @if($i % 3 == 0)
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                @elseif($i % 2 == 0)
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    @if($i % 3 == 0)
                                        Anda baru saja menambahkan novel "Judul Novel {{ $i }}" ke bookmark
                                    @elseif($i % 2 == 0)
                                        Anda memberikan komentar pada novel "Karya Terkenal {{ $i }}"
                                    @else
                                        Anda baru saja menyelesaikan membaca bab dari novel "Novel Favorit {{ $i }}"
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ rand(1, 24) }} jam yang lalu</p>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Reading Recommendations -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Rekomendasi Untukmu</h2>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Lihat Semua</a>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                            <div class="w-12 h-16 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 truncate">Rekomendasi Novel {{ $i }}</h4>
                                <p class="text-xs text-gray-600 truncate">Genre: {{ ['Romance', 'Fantasy', 'Mystery', 'Sci-Fi'][$i-1] }}</p>
                                <div class="flex items-center mt-1">
                                    <div class="flex text-yellow-400">
                                        @for($j = 0; $j < 4; $j++)
                                            <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                            </svg>
                                        @endfor
                                        <svg class="w-3 h-3 fill-current text-gray-300" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    
                    <button class="w-full mt-4 py-2 text-center text-indigo-600 hover:text-indigo-800 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        Dapatkan Rekomendasi Lebih Banyak
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="#" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-shadow border border-gray-100 group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center mx-auto mb-3 group-hover:bg-indigo-500 transition-colors">
                        <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-900">Cari Novel</h3>
                </a>
                
                <a href="#" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-shadow border border-gray-100 group">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-500 transition-colors">
                        <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-900">Bookmark</h3>
                </a>
                
                <a href="#" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-shadow border border-gray-100 group">
                    <div class="w-12 h-12 rounded-xl bg-pink-100 flex items-center justify-center mx-auto mb-3 group-hover:bg-pink-500 transition-colors">
                        <svg class="w-6 h-6 text-pink-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-900">Komunitas</h3>
                </a>
                
                <a href="#" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-shadow border border-gray-100 group">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-500 transition-colors">
                        <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-900">Koleksi</h3>
                </a>
            </div>
        </div>
    </main>
</div>
@endsection