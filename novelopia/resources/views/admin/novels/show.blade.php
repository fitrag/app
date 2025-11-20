@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Novel</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap novel "{{ $novel->title }}"</p>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.novels.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                
                <a href="{{ route('admin.novels.edit', $novel) }}" class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Novel
                </a>
                
                <form action="{{ route('admin.novels.destroy', $novel) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors" 
                            onclick="return confirm('Hapus novel ini? Tindakan ini tidak bisa dibatalkan.')">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Novel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Novel Info Card -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-orange-50">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-book mr-3 text-red-500"></i>
                    {{ $novel->title }}
                </h2>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3">
                        <div class="rounded-xl overflow-hidden shadow-lg">
                            <img src="{{ $novel->getCoverImageUrl() }}" alt="Cover Novel" class="w-full h-64 object-cover">
                        </div>
                        <div class="mt-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($novel->is_featured) 
                                    bg-yellow-100 text-yellow-800 
                                @else 
                                    bg-gray-100 text-gray-800 
                                @endif">
                                <i class="fas fa-star mr-1"></i>
                                {{ $novel->is_featured ? 'Featured' : 'Normal' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="w-full md:w-2/3">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-indigo-500"></i>
                                    Penulis
                                </label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-medium mr-3">
                                        {{ substr($novel->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $novel->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $novel->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-tags mr-2 text-green-500"></i>
                                    Genre
                                </label>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($novel->getGenreNames() as $index => $genreName)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                            {{ $genreName }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-info-circle mr-2 text-yellow-500"></i>
                                    Status
                                </label>
                                <div class="flex items-center">
                                    {!! $novel->getStatusBadge() !!}
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar mr-2 text-purple-500"></i>
                                    Tanggal
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-gray-500">Dibuat</p>
                                        <p class="font-medium text-gray-900">{{ $novel->created_at->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $novel->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-gray-500">Diupdate</p>
                                        <p class="font-medium text-gray-900">{{ $novel->updated_at->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $novel->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-align-left mr-2 text-blue-500"></i>
                        Deskripsi
                    </label>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        @if($novel->description)
                            <p class="text-gray-700 leading-relaxed">{{ $novel->description }}</p>
                        @else
                            <p class="text-gray-500 italic">Tidak ada deskripsi</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats and Chapters -->
        <div class="space-y-6">
            <!-- Stats Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-chart-bar mr-3 text-blue-500"></i>
                        Statistik
                    </h3>
                </div>
                
                <div class="p-5">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-book-open text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total Bab</p>
                                    <p class="font-bold text-gray-900">{{ $novel->chapters()->count() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.novels.chapters.index', $novel) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-eye text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total Views</p>
                                    <p class="font-bold text-gray-900">{{ number_format($novel->view_count) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-heart text-red-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total Likes</p>
                                    <p class="font-bold text-gray-900">{{ number_format($novel->like_count) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-3 border-t border-gray-200">
                            <a href="{{ route('admin.novels.chapters.create', $novel) }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-orange-600 hover:from-red-600 hover:to-orange-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Chapter Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chapters -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-list mr-3 text-purple-500"></i>
                        Daftar Chapter
                    </h3>
                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                        {{ $novel->chapters()->count() }} Bab
                    </span>
                </div>
                
                <div class="p-4 max-h-96 overflow-y-auto">
                    @if($novel->chapters()->count() > 0)
                        <div class="space-y-3">
                            @foreach($novel->chapters()->orderBy('chapter_number')->get() as $chapter)
                            <div class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 border border-gray-100 transition-colors">
                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="font-semibold text-gray-900 flex items-center">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800 text-xs font-bold mr-2">
                                                    {{ $chapter->chapter_number }}
                                                </span>
                                                {{ $chapter->title }}
                                            </div>
                                            <div class="flex items-center mt-2 space-x-3">
                                                <span class="text-xs text-gray-500">
                                                    <i class="far fa-calendar mr-1"></i>
                                                    {{ $chapter->created_at->format('d M Y') }}
                                                </span>
                                                <span class="text-xs">
                                                    {!! $chapter->getStatusBadge() !!}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-1">
                                            <a href="{{ route('admin.novels.chapters.show', [$novel, $chapter]) }}" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.novels.chapters.edit', [$novel, $chapter]) }}" class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.novels.chapters.destroy', [$novel, $chapter]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" 
                                                        onclick="return confirm('Hapus chapter ini? Tindakan ini tidak bisa dibatalkan.')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-600 line-clamp-2">
                                        {{ Str::limit(strip_tags($chapter->content), 100) }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-book text-2xl text-gray-400"></i>
                            </div>
                            <p class="font-medium text-gray-700">Belum ada chapter</p>
                            <p class="text-sm mt-1">Mulai tambahkan chapter untuk novel ini</p>
                            <a href="{{ route('admin.novels.chapters.create', $novel) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-orange-600 hover:from-red-600 hover:to-orange-700 text-white text-sm font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Chapter Pertama
                            </a>
                        </div>
                    @endif
                </div>
                
                @if($novel->chapters()->count() > 0)
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('admin.novels.chapters.index', $novel) }}" class="w-full text-center inline-block px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Lihat Semua Chapter
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
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