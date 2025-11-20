@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-book-reader mr-3 text-indigo-600"></i>
                    Manajemen Chapter
                </h1>
                <p class="text-gray-600 mt-1">Novel: <span class="font-semibold text-indigo-600">{{ $novel->title }}</span></p>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.novels.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Novel
                </a>
                
                <a href="{{ route('admin.novels.chapters.create', $novel) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Chapter
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-indigo-100 text-indigo-600 mr-4">
                    <i class="fas fa-book-open text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Chapter</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $novel->chapters()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Published</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $novel->chapters()->where('status', 'published')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-edit text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Draft</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $novel->chapters()->where('status', 'draft')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-sort-numeric-up text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Chapter Terbaru</p>
                    <p class="text-lg font-bold text-gray-900">
                        @if($novel->chapters()->count() > 0)
                            #{{ $novel->chapters()->orderBy('chapter_number', 'desc')->first()->chapter_number }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.novels.chapters.index', $novel) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Chapter</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        id="search" 
                        value="{{ request('search') }}"
                        placeholder="Judul chapter..."
                        class="pl-10 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                </div>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select 
                    name="status" 
                    id="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors mr-2">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('admin.novels.chapters.index', $novel) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-sync mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Chapters Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-list mr-3 text-indigo-600"></i>
                    Daftar Chapter
                </h2>
                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
                    {{ $chapters->total() }} Chapter
                </span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            @if($chapters->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Chapter</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($chapters as $chapter)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm mr-3">
                                    {{ $chapter->chapter_number }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">Chapter {{ $chapter->chapter_number }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $chapter->title }}</div>
                            <div class="text-xs text-gray-500 mt-1 line-clamp-2">
                                {{ Str::limit(strip_tags($chapter->content), 100) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                {!! $chapter->getStatusBadge() !!}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <i class="far fa-calendar-alt mr-1 text-gray-400"></i>
                                {{ $chapter->created_at->format('d M Y') }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="far fa-clock mr-1 text-gray-400"></i>
                                {{ $chapter->updated_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.novels.chapters.show', [$novel, $chapter]) }}" class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.novels.chapters.edit', [$novel, $chapter]) }}" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Chapter">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('admin.novels.chapters.destroy', [$novel, $chapter]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors" 
                                            onclick="return confirm('Hapus chapter {{ $chapter->title }}? Tindakan ini tidak bisa dibatalkan.')" title="Hapus Chapter">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-book text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Chapter</h3>
                <p class="text-gray-500 mb-6">Mulai tambahkan chapter untuk novel ini</p>
                <a href="{{ route('admin.novels.chapters.create', $novel) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Chapter Pertama
                </a>
            </div>
            @endif
        </div>
        
        <!-- Pagination -->
        @if($chapters->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $chapters->firstItem() }}</span> sampai 
                    <span class="font-medium">{{ $chapters->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $chapters->total() }}</span> chapter
                </div>
                <div>
                    {{ $chapters->withQueryString()->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    @if($chapters->count() > 0)
    <div class="mt-6 bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-bolt mr-3 text-yellow-500"></i>
            Aksi Cepat
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.novels.chapters.create', $novel) }}" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100 hover:from-green-100 hover:to-emerald-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center text-white mr-4">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Tambah Chapter</h4>
                    <p class="text-sm text-gray-600">Buat chapter baru</p>
                </div>
            </a>
            
            <a href="{{ route('admin.novels.chapters.index', [$novel, 'status' => 'draft']) }}" class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-lg border border-yellow-100 hover:from-yellow-100 hover:to-amber-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-yellow-500 flex items-center justify-center text-white mr-4">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">{{ $novel->chapters()->where('status', 'draft')->count() }} Draft</h4>
                    <p class="text-sm text-gray-600">Lihat draft chapter</p>
                </div>
            </a>
            
            <a href="{{ route('admin.novels.chapters.index', [$novel, 'status' => 'published']) }}" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100 hover:from-blue-100 hover:to-indigo-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center text-white mr-4">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">{{ $novel->chapters()->where('status', 'published')->count() }} Published</h4>
                    <p class="text-sm text-gray-600">Lihat chapter published</p>
                </div>
            </a>
        </div>
    </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection