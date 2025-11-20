@extends('layouts.dashboard')

@section('title', 'Edit Artikel')
@section('subtitle', 'Perbarui artikel yang sudah ada')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div style="max-width: 800px;">
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; color: #1e293b; margin-bottom: 5px;">Edit Artikel</h1>
        <p style="color: #64748b; font-size: 14px;">Perbarui konten artikel Anda</p>
    </div>

    <div style="background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0; padding: 30px;">
        <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 25px;">
                <label for="title" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">
                    <i class="fas fa-heading" style="margin-right: 8px; color: #667eea;"></i>
                    Judul Artikel
                </label>
                <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 14px; transition: all 0.3s ease;" 
                       onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';" 
                       onblur="this.style.borderColor='#e1e5e9'; this.style.boxShadow='none';" 
                       placeholder="Masukkan judul artikel" required>
                @error('title')
                    <div style="color: #ef4444; font-size: 13px; margin-top: 8px; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label for="category_id" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">
                    <i class="fas fa-folder" style="margin-right: 8px; color: #667eea;"></i>
                    Kategori
                </label>
                <select id="category_id" name="category_id" 
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 14px; transition: all 0.3s ease; cursor: pointer;" 
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';" 
                        onblur="this.style.borderColor='#e1e5e9'; this.style.boxShadow='none';" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div style="color: #ef4444; font-size: 13px; margin-top: 8px; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label for="thumbnail" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">
                    <i class="fas fa-image" style="margin-right: 8px; color: #667eea;"></i>
                    Thumbnail (Opsional)
                </label>
                @if ($article->thumbnail)
                    <div style="margin-bottom: 12px; position: relative; display: inline-block;">
                        <img src="{{ asset('storage/'.$article->thumbnail) }}" style="width: 120px; height: 80px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                        <div style="position: absolute; top: -8px; right: -8px; background: #667eea; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 12px; cursor: pointer;" 
                             onclick="if(confirm('Hapus thumbnail ini?')) { this.parentElement.style.display='none'; document.getElementById('remove-thumbnail').value='1'; }">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                @endif
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*" 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 14px; transition: all 0.3s ease; background: #f8fafc;" 
                       onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';" 
                       onblur="this.style.borderColor='#e1e5e9'; this.style.boxShadow='none';" 
                       onchange="if(this.files[0]) { document.getElementById('file-name').textContent = this.files[0].name; }">
                <div id="file-name" style="margin-top: 8px; font-size: 12px; color: #6b7280;"></div>
                <input type="hidden" id="remove-thumbnail" name="remove_thumbnail" value="0">
                @error('thumbnail')
                    <div style="color: #ef4444; font-size: 13px; margin-top: 8px; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div style="margin-bottom: 30px;">
                <label for="content" style="display: block; font-weight: 600; margin-bottom: 8px; color: #2d3748;">
                    <i class="fa-solid fa-align-left" style="margin-right: 8px; color: #667eea;"></i>
                    Konten Artikel
                </label>
                <textarea id="content" name="content" rows="12"
                          style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 14px; transition: all 0.3s ease; resize: vertical; min-height: 200px; font-family: inherit;"
                          onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';"
                          onblur="this.style.borderColor='#e1e5e9'; this.style.boxShadow='none';"
                          placeholder="Tulis konten artikel Anda di sini...">{{ old('content', $article->content ?? '') }}</textarea>
                <div id="contentError"
                     style="margin-top: 8px; color: #e02424; font-size: 13px; display: @if($errors->has('content')) block @else none @endif;">
                    @error('content') {{ $message }} @enderror
                </div>
            </div>

            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 25px; border-left: 4px solid #667eea;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-lightbulb" style="color: #667eea; font-size: 16px;"></i>
                    <div>
                        <div style="font-weight: 600; color: #1e293b; font-size: 14px;">Tips Editing</div>
                        <div style="color: #64748b; font-size: 13px; margin-top: 2px;">Pastikan konten tetap informatif dan menarik untuk pembaca Anda</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);" 
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.4)';" 
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.3)';" 
                        onmousedown="this.style.transform='translateY(0)';" 
                        onmouseup="this.style.transform='translateY(-2px)';">
                    <i class="fas fa-save"></i>
                    Perbarui Artikel
                </button>
                <a href="{{ route('articles.index') }}" style="background: #f1f5f9; color: #64748b; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 500; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;" 
                   onmouseover="this.style.background='#e2e8f0'; this.style.color='#475569';" 
                   onmouseout="this.style.background='#f1f5f9'; this.style.color='#64748b';">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const contentEl = document.querySelector('#content');
    if (!contentEl) return;

    // Hindari error invalid control not focusable saat CKEditor menyembunyikan textarea
    contentEl.removeAttribute('required');

    function showError(msg) {
        const el = document.getElementById('contentError');
        if (el) {
            el.textContent = msg;
            el.style.display = 'block';
        }
    }

    function hideError() {
        const el = document.getElementById('contentError');
        if (el) el.style.display = 'none';
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

                    // Validasi konten editor (wajib diisi)
                    if (isEditorEmpty(data)) {
                        e.preventDefault();
                        showError('Konten artikel wajib diisi.');
                        editor.editing.view.focus();
                        return;
                    }

                    hideError();
                    // Sinkronkan nilai ke textarea agar backend menerima konten
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
