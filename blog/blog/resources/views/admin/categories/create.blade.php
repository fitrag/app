@extends('layouts.dashboard')

@section('title', 'Tambah Kategori')
@section('subtitle', 'Buat kategori baru untuk mengorganisir artikel')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div style="max-width: 600px;">
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; color: #1e293b; margin-bottom: 5px;">Tambah Kategori Baru</h1>
        <p style="color: #64748b; font-size: 14px;">Buat kategori baru untuk mengorganisir artikel Anda</p>
    </div>

    <div style="background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0; padding: 30px;">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 25px;">
                <label for="name" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">
                    <i class="fas fa-tag" style="margin-right: 8px; color: #667eea;"></i>
                    Nama Kategori
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                       style="width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 14px; transition: all 0.3s ease;" 
                       onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)';" 
                       onblur="this.style.borderColor='#e1e5e9'; this.style.boxShadow='none';" 
                       placeholder="Masukkan nama kategori" required>
                @error('name')
                    <div style="color: #ef4444; font-size: 13px; margin-top: 8px; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);" 
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.4)';" 
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.3)';" 
                        onmousedown="this.style.transform='translateY(0)';" 
                        onmouseup="this.style.transform='translateY(-2px)';">
                    <i class="fas fa-save"></i>
                    Simpan Kategori
                </button>
                <a href="{{ route('categories.index') }}" style="background: #f1f5f9; color: #64748b; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 500; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;" 
                   onmouseover="this.style.background='#e2e8f0'; this.style.color='#475569';" 
                   onmouseout="this.style.background='#f1f5f9'; this.style.color='#64748b';">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
