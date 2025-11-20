@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Chapter Baru</h1>
                <p class="text-gray-600 mt-1">Novel: {{ $novel->title }}</p>
            </div>
            
            <a href="{{ route('admin.novels.chapters.index', $novel) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Chapter
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Form Chapter Baru</h2>
        </div>
        
        <form action="{{ route('admin.novels.chapters.store', $novel) }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="chapter_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Chapter</label>
                    <input 
                        type="number" 
                        name="chapter_number" 
                        id="chapter_number" 
                        value="{{ old('chapter_number', $nextChapterNumber) }}"
                        min="1"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('chapter_number') border-red-500 @enderror"
                        placeholder="Masukkan nomor chapter"
                    >
                    @error('chapter_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select 
                        name="status" 
                        id="status"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('status') border-red-500 @enderror"
                    >
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Chapter</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('title') border-red-500 @enderror"
                        placeholder="Masukkan judul chapter"
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
    <label class="block text-sm font-medium text-gray-700 mb-2">Isi Chapter</label>

    <!-- Toolbar -->
    <div id="editor-toolbar" class="flex flex-wrap gap-2 mb-2 border border-gray-300 rounded-t-lg bg-gray-100 px-3 py-2">
        <button type="button" onclick="execCmd('bold')" title="Bold" class="px-3 py-1 border rounded hover:bg-gray-200 font-bold">B</button>
        <button type="button" onclick="execCmd('italic')" title="Italic" class="px-3 py-1 border rounded hover:bg-gray-200 italic">I</button>
        <button type="button" onclick="execCmd('underline')" title="Underline" class="px-3 py-1 border rounded hover:bg-gray-200 underline">U</button>
        <button type="button" onclick="createLink()" title="Link" class="px-3 py-1 border rounded hover:bg-gray-200">Link</button>
        <button type="button" onclick="execCmd('undo')" title="Undo" class="px-3 py-1 border rounded hover:bg-gray-200">↶</button>
        <button type="button" onclick="execCmd('redo')" title="Redo" class="px-3 py-1 border rounded hover:bg-gray-200">↷</button>
    </div>

    <!-- Editor -->
    <div 
        id="editor"
        contenteditable="true"
        class="w-full min-h-[300px] max-h-[600px] overflow-y-auto px-4 py-3 border border-t-0 border-gray-300 rounded-b-lg focus:outline-none focus:ring-2 focus:ring-red-500"
        style="line-height: 1.6; font-family: Inter, sans-serif;"
    >{!! old('content') !!}</div>

    <!-- Hidden textarea for form submission -->
    <textarea name="content" id="content" style="display:none;">{{ old('content') }}</textarea>

    @error('content')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
            </div>
            
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.novels.chapters.index', $novel) }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-500 to-orange-600 hover:from-red-600 hover:to-orange-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    Simpan Chapter
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-resize textarea
document.getElementById('content').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

// Initialize textarea height
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('content');
    if (textarea.value) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    }
});

    const editor = document.getElementById('editor');
    const hiddenTextarea = document.getElementById('content');

    function execCmd(command, value = null) {
        document.execCommand(command, false, value);
        updateHiddenTextarea();
    }

    function createLink() {
        const url = prompt("Masukkan URL:");
        if (url) execCmd('createLink', url);
    }

    // Sync content ke textarea tersembunyi
    function updateHiddenTextarea() {
        hiddenTextarea.value = editor.innerHTML;
    }

    // Update saat mengetik
    editor.addEventListener('input', updateHiddenTextarea);

    // Paste plain text only
    editor.addEventListener('paste', (e) => {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData('text/plain');
        document.execCommand('insertText', false, text);
    });

    // Initial sync
    editor.innerHTML = hiddenTextarea.value || '';
</script>
@endsection