@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-indigo-600"></i>
                    Laporan & Statistik
                </h1>
                <p class="text-gray-600 mt-1">Analisis kinerja dan pertumbuhan platform NovelVerse</p>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard
                </a>
                
                <div class="flex space-x-2">
                    <button onclick="exportReport('csv')" class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors shadow-sm">
                        <i class="fas fa-file-csv mr-2"></i>
                        Export CSV
                    </button>
                    <button onclick="exportReport('pdf')" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors shadow-sm">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select 
                    name="period" 
                    id="period"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    onchange="toggleCustomDates()"
                >
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="yesterday" {{ request('period') == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                    <option value="7_days" {{ request('period', '7_days') == '7_days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="30_days" {{ request('period') == '30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                    <option value="90_days" {{ request('period') == '90_days' ? 'selected' : '' }}>90 Hari Terakhir</option>
                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>
            
            <div id="custom-date-fields" class="{{ request('period') == 'custom' ? '' : 'hidden' }}">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input 
                    type="date" 
                    name="start_date" 
                    id="start_date" 
                    value="{{ request('start_date') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
            </div>
            
            <div id="custom-date-fields-end" class="{{ request('period') == 'custom' ? '' : 'hidden' }}">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                <input 
                    type="date" 
                    name="end_date" 
                    id="end_date" 
                    value="{{ request('end_date') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('admin.reports.index') }}" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-sync mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total User</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_users']) }}</p>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +{{ $stats['new_users'] }} periode ini
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Novel</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_novels']) }}</p>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +{{ $stats['new_novels'] }} periode ini
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-book text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Chapter</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_chapters']) }}</p>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +{{ $stats['new_chapters'] }} periode ini
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-book-open text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Views</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_views']) }}</p>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +{{ number_format(rand(1000, 9999)) }} periode ini
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <i class="fas fa-eye text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Growth Chart -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-chart-line mr-3 text-indigo-600"></i>
                    Pertumbuhan Platform
                </h2>
            </div>
            <div class="p-6">
                <div class="h-80 flex items-center justify-center bg-gray-50 rounded-xl">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-r from-indigo-400 to-purple-500 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-chart-bar text-white text-2xl"></i>
                        </div>
                        <p class="text-gray-600 font-medium">Grafik Pertumbuhan Platform</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Periode: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                        </p>
                        <div class="mt-4 grid grid-cols-3 gap-4 max-w-md mx-auto">
                            <div class="text-center">
                                <div class="w-3 h-16 bg-gradient-to-t from-blue-400 to-blue-600 rounded-t mx-auto"></div>
                                <p class="text-xs text-gray-600 mt-2">User</p>
                            </div>
                            <div class="text-center">
                                <div class="w-3 h-12 bg-gradient-to-t from-purple-400 to-purple-600 rounded-t mx-auto"></div>
                                <p class="text-xs text-gray-600 mt-2">Novel</p>
                            </div>
                            <div class="text-center">
                                <div class="w-3 h-10 bg-gradient-to-t from-indigo-400 to-indigo-600 rounded-t mx-auto"></div>
                                <p class="text-xs text-gray-600 mt-2">Chapter</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Registrations -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-user-plus mr-3 text-green-600"></i>
                    Registrasi User Harian
                </h2>
            </div>
            <div class="p-6">
                <div class="h-80 flex items-center justify-center bg-gray-50 rounded-xl">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <p class="text-gray-600 font-medium">Grafik Registrasi User</p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $stats['new_users'] }} user baru dalam periode ini
                        </p>
                        <div class="mt-4 flex items-center justify-center space-x-2">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                <span class="text-xs text-gray-600">Registrasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Novels and Detailed Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Top Novels -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-fire mr-3 text-red-600"></i>
                    Novel Terpopuler
                </h2>
                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                    Top 10
                </span>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topNovels as $index => $novel)
                    <div class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-red-400 to-orange-500 flex items-center justify-center text-white font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="w-10 h-12 rounded-lg bg-gradient-to-br from-purple-400 to-pink-500 flex-shrink-0"></div>
                            <div>
                                <h3 class="font-medium text-gray-900">{{ Str::limit($novel->title, 25) }}</h3>
                                <p class="text-sm text-gray-600">{{ Str::limit($novel->user->name, 20) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900">{{ number_format($novel->view_count) }}</div>
                            <div class="text-xs text-gray-500">views</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-book text-2xl mb-2"></i>
                        <p>Tidak ada novel dalam periode ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Detailed Stats -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                    Statistik Detail
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center text-white mr-3">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Admin</p>
                                <p class="text-xl font-bold text-gray-900">{{ App\Models\User::where('role', 'admin')->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center text-white mr-3">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Kreator</p>
                                <p class="text-xl font-bold text-gray-900">{{ App\Models\User::where('role', 'kreator')->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center text-white mr-3">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Pembaca</p>
                                <p class="text-xl font-bold text-gray-900">{{ App\Models\User::where('role', 'biasa')->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-xl p-4 border border-red-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-red-500 flex items-center justify-center text-white mr-3">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Likes</p>
                                <p class="text-xl font-bold text-gray-900">{{ number_format($stats['total_likes']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-medium text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-percentage mr-2 text-yellow-500"></i>
                        Persentase Pertumbuhan
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">User Baru</span>
                                <span class="font-medium">+{{ $stats['new_users'] > 0 ? round(($stats['new_users'] / max($stats['total_users'] - $stats['new_users'], 1)) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2 rounded-full" style="width: {{ $stats['new_users'] > 0 ? min(round(($stats['new_users'] / max($stats['total_users'] - $stats['new_users'], 1)) * 100, 1), 100) : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Novel Baru</span>
                                <span class="font-medium">+{{ $stats['new_novels'] > 0 ? round(($stats['new_novels'] / max($stats['total_novels'] - $stats['new_novels'], 1)) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" style="width: {{ $stats['new_novels'] > 0 ? min(round(($stats['new_novels'] / max($stats['total_novels'] - $stats['new_novels'], 1)) * 100, 1), 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Insights -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-lightbulb mr-3 text-yellow-600"></i>
                Insight & Rekomendasi
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center text-white mr-4 flex-shrink-0">
                            <i class="fas fa-arrow-trend-up"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Pertumbuhan Positif</h3>
                            <p class="text-sm text-gray-700">
                                Platform menunjukkan pertumbuhan {{ $stats['new_users'] > 0 ? 'positif' : 'stabil' }} 
                                dengan {{ $stats['new_users'] }} user baru dalam periode ini.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 border border-green-100">
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center text-white mr-4 flex-shrink-0">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Rekomendasi Marketing</h3>
                            <p class="text-sm text-gray-700">
                                Pertimbangkan kampanye marketing untuk meningkatkan {{ $stats['new_novels'] > $stats['new_users'] ? 'jumlah pembaca' : 'jumlah novel' }}.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-5 border border-purple-100">
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center text-white mr-4 flex-shrink-0">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Insight</h3>
                            <p class="text-sm text-gray-700">
                                Rata-rata {{ round($stats['total_views'] / max($stats['total_novels'], 1)) }} views per novel.
                                Fokus pada kualitas konten untuk meningkatkan engagement.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCustomDates() {
    const period = document.getElementById('period').value;
    const customFields = document.getElementById('custom-date-fields');
    const customFieldsEnd = document.getElementById('custom-date-fields-end');
    
    if (period === 'custom') {
        customFields.classList.remove('hidden');
        customFieldsEnd.classList.remove('hidden');
    } else {
        customFields.classList.add('hidden');
        customFieldsEnd.classList.add('hidden');
    }
}

function exportReport(type) {
    // Create a form to submit the export request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.reports.export") }}';
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Add export type
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'type';
    typeInput.value = type;
    form.appendChild(typeInput);
    
    // Add period
    const periodInput = document.createElement('input');
    periodInput.type = 'hidden';
    periodInput.name = 'period';
    periodInput.value = '{{ $period }}';
    form.appendChild(periodInput);
    
    // Submit form
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection