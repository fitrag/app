@extends('layouts.dashboard')

@section('title', 'Artikel')
@section('subtitle', 'Kelola artikel dan konten blog Anda')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Daftar Artikel</h1>
            <p style="color: #64748b; font-size: 13px;">Kelola artikel dan konten blog Anda</p>
        </div>
        <a href="{{ route('articles.create') }}"
           style="background: #4f46e5; color: #fff; padding: 10px 16px; border-radius: 10px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: background-color .2s ease;"
           onmouseover="this.style.background='#4338ca';"
           onmouseout="this.style.background='#4f46e5';">
            <i class="fas fa-plus"></i>
            Tambah Artikel
        </a>
    </div>

    @if (session('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 12px 14px; border-radius: 10px; margin-bottom: 18px; display: flex; align-items: center; gap: 10px; border: 1px solid #a7f3d0;">
            <i class="fas fa-check-circle" style="font-size: 16px; color: #10b981;"></i>
            <span style="font-weight: 500;">{{ session('success') }}</span>
        </div>
    @endif
</div>

@if($articles->count() > 0)
    <div style="background: #fff; border-radius: 14px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06); border: 1px solid #e5e7eb; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f9fafb;">
                    <tr>
                        <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; border-bottom: 1px solid #e5e7eb;">Judul</th>
                        <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; border-bottom: 1px solid #e5e7eb;">Kategori</th>
                        <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; border-bottom: 1px solid #e5e7eb;">Penulis</th>
                        <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px; border-bottom: 1px solid #e5e7eb;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $article)
                        <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s ease;"
                            onmouseover="this.style.backgroundColor='#f9fafb';"
                            onmouseout="this.style.backgroundColor='';">
                            <td style="padding: 16px; font-weight: 500; color: #1f2937; font-size: 14px;">
                                <div style="max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $article->title }}
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                <span style="background: #eef2ff; color: #4338ca; padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; border: 1px solid #e0e7ff;">
                                    <i class="fas fa-folder" style="margin-right: 6px;"></i>
                                    {{ $article->category->name }}
                                </span>
                            </td>
                            <td style="padding: 16px; color: #6b7280; font-size: 14px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-user" style="color: #9ca3af;"></i>
                                    {{ $article->user->name }}
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <a href="{{ route('articles.edit', $article) }}"
                                       style="background: #eef2ff; color: #4338ca; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 600; border: 1px solid #e0e7ff; transition: background-color 0.2s ease;"
                                       onmouseover="this.style.background='#e0e7ff';"
                                       onmouseout="this.style.background='#eef2ff';">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('articles.destroy', $article) }}" method="POST" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                style="background: #fee2e2; color: #991b1b; padding: 8px 12px; border: 1px solid #fecaca; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s ease;"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')"
                                                onmouseover="this.style.background='#fecaca';"
                                                onmouseout="this.style.background='#fee2e2';">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div style="background: #fff; border-radius: 14px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06); border: 1px solid #e5e7eb; padding: 48px 32px; text-align: center;">
        <div style="font-size: 40px; margin-bottom: 12px; color: #cbd5e1;">
            <i class="fas fa-file-alt"></i>
        </div>
        <h3 style="font-size: 18px; font-weight: 700; color: #374151; margin-bottom: 6px;">Belum ada artikel</h3>
        <p style="color: #6b7280; font-size: 13px; margin-bottom: 20px;">Mulai buat artikel pertama Anda untuk mengisi blog</p>
        <a href="{{ route('articles.create') }}"
           style="background: #4f46e5; color: white; padding: 10px 16px; border-radius: 10px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: background-color .2s ease;"
           onmouseover="this.style.background='#4338ca';"
           onmouseout="this.style.background='#4f46e5';">
            <i class="fas fa-plus"></i>
            Buat Artikel Pertama
        </a>
    </div>
@endif

@if($articles->hasPages())
    <div style="margin-top: 24px; display: flex; justify-content: center;">
        <div style="background: #fff; border-radius: 10px; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06); padding: 6px; border: 1px solid #e5e7eb;">
            {{ $articles->links() }}
        </div>
    </div>
@endif
@endsection
