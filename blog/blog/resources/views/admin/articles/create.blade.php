@extends('layouts.dashboard')

@section('title', 'Tambah Artikel')
@section('subtitle', 'Buat artikel baru untuk blog Anda')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-1">Tambah Artikel</h1>
        <p class="text-slate-500 text-sm">Buat artikel baru untuk blog Anda</p>
    </div>

    <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-5">
                <label for="title" class="block mb-2 text-slate-700 font-semibold text-sm flex items-center gap-2">
                    <i class="fas fa-heading text-indigo-600"></i>
                    Judul Artikel
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600"
                       placeholder="Masukkan judul artikel" required>
                @error('title')
                    <div class="text-red-600 text-sm mt-2 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="category_id" class="block mb-2 text-slate-700 font-semibold text-sm flex items-center gap-2">
                    <i class="fas fa-folder text-indigo-600"></i>
                    Kategori
                </label>
                <select id="category_id" name="category_id"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 cursor-pointer"
                        required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-red-600 text-sm mt-2 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="thumbnail" class="block mb-2 text-slate-700 font-semibold text-sm flex items-center gap-2">
                    <i class="fas fa-image text-indigo-600"></i>
                    Thumbnail (Opsional)
                </label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600"
                       onchange="if(this.files[0]) { document.getElementById('file-name').textContent = this.files[0].name; }">
                <div id="file-name" class="mt-2 text-xs text-slate-500"></div>
                @error('thumbnail')
                    <div class="text-red-600 text-sm mt-2 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-6">
                <label for="content" class="block mb-2 text-slate-700 font-semibold text-sm flex items-center gap-2">
                    <i class="fa-solid fa-align-left text-indigo-600"></i>
                    Konten Artikel
                </label>
                <textarea id="content" name="content" rows="12"
                          class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 resize-y min-h-[200px]"
                          placeholder="Tulis konten artikel Anda di sini...">{{ old('content') }}</textarea>
                <div id="contentError" class="mt-2 text-sm text-red-600 {{ $errors->has('content') ? '' : 'hidden' }}">
                    @error('content') {{ $message }} @enderror
                </div>
            </div>

            <div class="bg-slate-50 rounded-lg p-4 mb-5 border border-slate-200">
                <div class="flex items-start gap-3">
                    <i class="fas fa-lightbulb text-indigo-600 text-base"></i>
                    <div>
                        <div class="font-bold text-slate-800 text-sm">Tips Menulis</div>
                        <div class="text-slate-500 text-sm mt-1">Buat judul yang menarik dan konten yang informatif untuk pembaca Anda</div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold inline-flex items-center gap-2 shadow-sm">
                    <i class="fas fa-save"></i>
                    Simpan Artikel
                </button>
                <a href="{{ route('articles.index') }}"
                   class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg font-semibold border border-slate-300 inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>

{{-- CKEditor loader + fallback (hapus <script src="https://cdn.ckeditor.com/..."> lama) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const contentEl = document.querySelector('#content');
    if (!contentEl) return;

    // Hindari error invalid control not focusable saat textarea disembunyikan oleh CKEditor
    contentEl.removeAttribute('required');

    function showError(msg) {
        const el = document.getElementById('contentError');
        if (el) {
            el.textContent = msg;
            el.classList.remove('hidden');
        }
    }

    function hideError() {
        const el = document.getElementById('contentError');
        if (el) el.classList.add('hidden');
    }

    function isEditorEmpty(html) {
        const temp = document.createElement('div');
        temp.innerHTML = html;
        const text = (temp.textContent || '').replace(/\u00A0/g, ' ').trim();
        const hasMedia = /<(img|iframe|video|embed)[\s>]/i.test(html);
        return !hasMedia && text.length === 0;
    }

    function initEditor() {
        if (!window.ClassicEditor) {
            console.error('CKEditor belum tersedia.');
            return;
        }
        ClassicEditor.create(contentEl, {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', 'blockQuote', '|',
                'undo', 'redo'
            ]
        }).then(editor => {
            editor.ui.view.editable.element.style.minHeight = '300px';

            const form = contentEl.closest('form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    const data = editor.getData();

                    if (isEditorEmpty(data)) {
                        e.preventDefault();
                        showError('Konten artikel wajib diisi.');
                        editor.editing.view.focus();
                        return;
                    }

                    hideError();
                    contentEl.value = data;
                });
            }
        }).catch(error => {
            console.error('CKEditor init error:', error);
        });
    }

    function loadScript(src, onload, onerror) {
        var s = document.createElement('script');
        s.src = src;
        s.onload = onload;
        s.onerror = onerror;
        document.head.appendChild(s);
    }

    // Coba jsDelivr dulu, fallback ke cdn.ckeditor.com
    if (!window.ClassicEditor) {
        loadScript('https://cdn.jsdelivr.net/npm/@ckeditor/ckeditor5-build-classic@41.3.1/build/ckeditor.js',
            initEditor,
            function () {
                loadScript('https://cdn.ckeditor.com/ckeditor5/41.1.1/classic/ckeditor.js', initEditor, function () {
                    console.error('Gagal memuat CKEditor dari kedua CDN.');
                });
            }
        );
    } else {
        initEditor();
    }
});
</script>
@endsection
