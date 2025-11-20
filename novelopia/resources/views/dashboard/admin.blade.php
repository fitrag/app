@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang Kembali, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                    <p class="text-blue-100">Anda adalah Administrator - Kelola platform NovelVerse</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ date('H:i') }}</div>
                        <div class="text-sm text-blue-100">{{ date('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total User</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ App\Models\User::count() }}</p>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +{{ App\Models\User::whereDate('created_at', today())->count() }} hari ini
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Novels -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Novel</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ App\Models\Novel::count() }}</p>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +{{ App\Models\Novel::whereDate('created_at', today())->count() }} hari ini
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-book text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Chapters -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Chapter</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ App\Models\Chapter::count() }}</p>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +{{ App\Models\Chapter::whereDate('created_at', today())->count() }} hari ini
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-book-open text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Views</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format(App\Models\Novel::sum('view_count')) }}</p>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +{{ rand(100, 999) }} hari ini
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="fas fa-eye text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- User Growth Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-line mr-3 text-indigo-600"></i>
                        Pertumbuhan User (30 Hari Terakhir)
                    </h2>
                    <div class="flex space-x-2">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">User</span>
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Novel</span>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-xl">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-r from-indigo-400 to-purple-500 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-chart-bar text-white text-2xl"></i>
                        </div>
                        <p class="text-gray-600 font-medium">Grafik Pertumbuhan Platform</p>
                        <p class="text-sm text-gray-500 mt-1">+15% dari bulan lalu</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Distribution -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-user-friends mr-3 text-blue-600"></i>
                    Distribusi User
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600 flex items-center">
                                <i class="fas fa-user-shield text-red-500 mr-2"></i>
                                Administrator
                            </span>
                            <span class="font-medium">{{ App\Models\User::where('role', 'admin')->count() }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-red-500 to-orange-500 h-2 rounded-full" style="width: {{ (App\Models\User::where('role', 'admin')->count() / max(App\Models\User::count(), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600 flex items-center">
                                <i class="fas fa-user-edit text-purple-500 mr-2"></i>
                                Kreator
                            </span>
                            <span class="font-medium">{{ App\Models\User::where('role', 'kreator')->count() }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-2 rounded-full" style="width: {{ (App\Models\User::where('role', 'kreator')->count() / max(App\Models\User::count(), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600 flex items-center">
                                <i class="fas fa-user text-blue-500 mr-2"></i>
                                Pembaca
                            </span>
                            <span class="font-medium">{{ App\Models\User::where('role', 'biasa')->count() }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full" style="width: {{ (App\Models\User::where('role', 'biasa')->count() / max(App\Models\User::count(), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Aktif Hari Ini</span>
                            <span class="font-medium text-green-600">
                                <i class="fas fa-user-check mr-1"></i>
                                {{ App\Models\User::whereDate('updated_at', today())->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-bolt mr-3 text-yellow-600"></i>
                    Aktivitas Terbaru
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @for($i = 1; $i <= 6; $i++)
                    <div class="flex items-start space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 flex items-center justify-center flex-shrink-0">
                            @if($i % 3 == 0)
                                <i class="fas fa-user-plus text-white"></i>
                            @elseif($i % 2 == 0)
                                <i class="fas fa-book-medical text-white"></i>
                            @else
                                <i class="fas fa-book-reader text-white"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">
                                @if($i % 3 == 0)
                                    User baru terdaftar: "Pengguna {{ $i }}"
                                @elseif($i % 2 == 0)
                                    Novel baru diupload: "Judul Novel {{ $i }}"
                                @else
                                    Chapter baru ditambahkan: "Bab {{ rand(1, 20) }} Novel {{ $i }}"
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ rand(1, 24) }} jam yang lalu</p>
                        </div>
                        <div class="text-xs text-gray-400">
                            <i class="fas fa-circle text-{{ ['green', 'blue', 'purple', 'red'][rand(0,3)] }}-500"></i>
                        </div>
                    </div>
                    @endfor
                </div>
                <button class="w-full mt-4 py-3 text-center text-indigo-600 hover:text-indigo-800 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Lihat Semua Aktivitas
                </button>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-rocket mr-3 text-green-600"></i>
                    Aksi Cepat
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.users.index') }}" class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 group">
                        <div class="w-10 h-10 rounded-xl bg-blue-500 flex items-center justify-center text-white mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Manajemen User</h3>
                        <p class="text-xs text-gray-600">{{ App\Models\User::count() }} User</p>
                    </a>
                    
                    <a href="{{ route('admin.novels.index') }}" class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100 hover:from-purple-100 hover:to-pink-100 transition-all duration-200 group">
                        <div class="w-10 h-10 rounded-xl bg-purple-500 flex items-center justify-center text-white mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Manajemen Novel</h3>
                        <p class="text-xs text-gray-600">{{ App\Models\Novel::count() }} Novel</p>
                    </a>
                    
                    <a href="#" class="p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100 hover:from-green-100 hover:to-emerald-100 transition-all duration-200 group">
                        <div class="w-10 h-10 rounded-xl bg-green-500 flex items-center justify-center text-white mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Laporan</h3>
                        <p class="text-xs text-gray-600">Analitik Platform</p>
                    </a>
                    
                    <a href="#" class="p-4 bg-gradient-to-br from-red-50 to-orange-50 rounded-xl border border-red-100 hover:from-red-100 hover:to-orange-100 transition-all duration-200 group">
                        <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center text-white mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Pengaturan</h3>
                        <p class="text-xs text-gray-600">Konfigurasi Sistem</p>
                    </a>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h3 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-bell mr-2 text-yellow-500"></i>
                        Notifikasi Penting
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg">
                            <div class="w-6 h-6 rounded-full bg-yellow-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-exclamation text-white text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">24 laporan spam menunggu review</p>
                                <p class="text-xs text-gray-600">2 jam yang lalu</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                            <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-info-circle text-white text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">Backup database berhasil</p>
                                <p class="text-xs text-gray-600">1 hari yang lalu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users and Novels -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Users -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-user-clock mr-3 text-indigo-600"></i>
                    User Terbaru
                </h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    Lihat Semua
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @php
                        $recentUsers = App\Models\User::latest()->take(5)->get();
                    @endphp
                    @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-400 to-purple-500 flex items-center justify-center text-white font-medium">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                            <div class="text-xs mt-1">
                                {!! $user->getStatusBadge() !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Novels -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-book-medical mr-3 text-purple-600"></i>
                    Novel Terbaru
                </h2>
                <a href="{{ route('admin.novels.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                    Lihat Semua
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @php
                        $recentNovels = App\Models\Novel::with('user')->latest()->take(5)->get();
                    @endphp
                    @foreach($recentNovels as $novel)
                    <div class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-12 rounded-lg bg-gradient-to-br from-purple-400 to-pink-500 flex-shrink-0"></div>
                            <div>
                                <h3 class="font-medium text-gray-900">{{ Str::limit($novel->title, 20) }}</h3>
                                <p class="text-sm text-gray-600">{{ Str::limit($novel->user->name, 15) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500">
                                {{ $novel->created_at->diffForHumans() }}
                            </div>
                            <div class="text-xs mt-1">
                                {!! $novel->getStatusBadge() !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-tachometer-alt mr-3 text-green-600"></i>
                Metrik Performa Platform
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100">
                    <div class="w-12 h-12 rounded-xl bg-green-500 flex items-center justify-center text-white mx-auto mb-3">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">98%</p>
                    <p class="text-sm text-gray-600">Uptime</p>
                </div>
                
                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center text-white mx-auto mb-3">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">120ms</p>
                    <p class="text-sm text-gray-600">Response Time</p>
                </div>
                
                <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                    <div class="w-12 h-12 rounded-xl bg-purple-500 flex items-center justify-center text-white mx-auto mb-3">
                        <i class="fas fa-server"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">99.9%</p>
                    <p class="text-sm text-gray-600">Availability</p>
                </div>
                
                <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-red-50 rounded-xl border border-orange-100">
                    <div class="w-12 h-12 rounded-xl bg-orange-500 flex items-center justify-center text-white mx-auto mb-3">
                        <i class="fas fa-database"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">95%</p>
                    <p class="text-sm text-gray-600">Storage</p>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900">System Health</h3>
                        <p class="text-sm text-gray-600">All systems operational</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                            <span class="text-sm text-gray-600">Database</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                            <span class="text-sm text-gray-600">Web Server</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                            <span class="text-sm text-gray-600">Cache</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection