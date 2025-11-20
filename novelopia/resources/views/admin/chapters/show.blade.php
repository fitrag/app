@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-book-open mr-3 text-indigo-600"></i>
                    Detail Chapter
                </h1>
                <p class="text-gray-600 mt-1">
                    Novel: <span class="font-semibold text-indigo-600">{{ $novel->title }}</span> | 
                    Chapter {{ $chapter->chapter_number }}
                </p>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.novels.chapters.index', $novel) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                
                <a href="{{ route('admin.novels.chapters.edit', [$novel, $chapter]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors shadow-sm">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Chapter
                </a>
                
                <form action="{{ route('admin.novels.chapters.destroy', [$novel, $chapter]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors shadow-sm" 
                            onclick="return confirm('Hapus chapter ini? Tindakan ini tidak bisa dibatalkan.')">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Chapter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chapter Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Chapter {{ $chapter->chapter_number }}: {{ $chapter->title }}</h2>
                            <div class="flex items-center mt-2 space-x-4">
                                <span class="text-sm text-gray-600">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $chapter->created_at->format('d M Y') }}
                                </span>
                                <span class="text-sm text-gray-600">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $chapter->updated_at->diffForHumans() }}
                                </span>
                                <span class="text-sm">
                                    {!! $chapter->getStatusBadge() !!}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="prose max-w-none">
                        @if($chapter->content)
                            <!-- Parse content paragraphs -->
                            @php
                                $paragraphs = explode("\n", $chapter->content);
                            @endphp
                            
                            @foreach($paragraphs as $paragraph)
                                @if(trim($paragraph) !== '')
                                    <p class="mb-4 text-gray-700 leading-relaxed text-justify">{!! $paragraph !!}</p>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-file-alt text-4xl mb-3"></i>
                                <p>Tidak ada isi chapter</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Navigation -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            Chapter {{ $chapter->chapter_number }} dari {{ $novel->chapters()->count() }} total chapter
                        </div>
                        <div class="flex space-x-3">
                            @php
                                $prevChapter = $novel->chapters()
                                    ->where('chapter_number', '<', $chapter->chapter_number)
                                    ->orderBy('chapter_number', 'desc')
                                    ->first();
                                $nextChapter = $novel->chapters()
                                    ->where('chapter_number', '>', $chapter->chapter_number)
                                    ->orderBy('chapter_number', 'asc')
                                    ->first();
                            @endphp
                            
                            @if($prevChapter)
                                <a href="{{ route('admin.novels.chapters.show', [$novel, $prevChapter]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Sebelumnya
                                </a>
                            @endif
                            
                            @if($nextChapter)
                                <a href="{{ route('admin.novels.chapters.show', [$novel, $nextChapter]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Selanjutnya
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chapter Info Sidebar -->
        <div class="space-y-6">
            <!-- Chapter Info -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                        Informasi Chapter
                    </h3>
                </div>
                
                <div class="p-5">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Judul</label>
                            <p class="text-gray-900 font-medium">{{ $chapter->title }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nomor Chapter</label>
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-800 font-bold mr-2">
                                    {{ $chapter->chapter_number }}
                                </span>
                                <span class="text-gray-900">Chapter {{ $chapter->chapter_number }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</label>
                            <div class="inline-block">
                                {!! $chapter->getStatusBadge() !!}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tanggal Dibuat</label>
                            <div class="flex items-center text-gray-900">
                                <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                                {{ $chapter->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Terakhir Diupdate</label>
                            <div class="flex items-center text-gray-900">
                                <i class="far fa-clock mr-2 text-gray-400"></i>
                                {{ $chapter->updated_at->format('d M Y H:i') }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Panjang Isi</label>
                            <div class="flex items-center text-gray-900">
                                <i class="fas fa-font mr-2 text-gray-400"></i>
                                {{ strlen(strip_tags($chapter->content)) }} karakter
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Novel Info -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-book mr-3 text-purple-600"></i>
                        Informasi Novel
                    </h3>
                </div>
                
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-20 rounded-lg bg-gray-200 flex-shrink-0 overflow-hidden">
                            <img src="{{ $novel->getCoverImageUrl() }}" alt="Cover Novel" class="w-full h-full object-cover">
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">{{ $novel->title }}</h4>
                            <p class="text-sm text-gray-600">{{ $novel->user->name }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Chapter</label>
                            <div class="flex items-center text-gray-900">
                                <i class="fas fa-book-open mr-2 text-gray-400"></i>
                                {{ $novel->chapters()->count() }} chapter
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status Novel</label>
                            <div class="inline-block">
                                {!! $novel->getStatusBadge() !!}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Genre</label>
                            <div class="flex flex-wrap gap-1">
                                @foreach($novel->getGenreNames() as $genreName)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        {{ $genreName }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-t border-gray-200">
                        <a href="{{ route('admin.novels.show', $novel) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Lihat Detail Novel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt mr-3 text-green-600"></i>
                        Aksi Cepat
                    </h3>
                </div>
                
                <div class="p-5">
                    <div class="space-y-3">
                        <a href="{{ route('admin.novels.chapters.create', $novel) }}" class="w-full flex items-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100 hover:from-green-100 hover:to-emerald-100 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-green-500 flex items-center justify-center text-white mr-3">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Tambah Chapter Baru</p>
                                <p class="text-xs text-gray-600">Chapter #{{ $novel->chapters()->max('chapter_number') + 1 }}</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.novels.chapters.edit', [$novel, $chapter]) }}" class="w-full flex items-center p-3 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-100 hover:from-indigo-100 hover:to-purple-100 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center text-white mr-3">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Edit Chapter Ini</p>
                                <p class="text-xs text-gray-600">Ubah konten chapter</p>
                            </div>
                        </a>
                        
                        <button onclick="window.print()" class="w-full flex items-center p-3 bg-gradient-to-r from-gray-50 to-slate-50 rounded-lg border border-gray-100 hover:from-gray-100 hover:to-slate-100 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-gray-500 flex items-center justify-center text-white mr-3">
                                <i class="fas fa-print"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Cetak Chapter</p>
                                <p class="text-xs text-gray-600">Simpan sebagai PDF</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection