@extends('layouts.dashboard')

@section('title', 'Kategori')
@section('subtitle', 'Kelola kategori artikel Anda')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1e293b; margin-bottom: 5px;">Kelola Kategori</h1>
            <p style="color: #64748b; font-size: 14px;">Organisir artikel Anda dengan kategori yang terstruktur</p>
        </div>
        <a href="{{ route('categories.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.3)';">
            <i class="fas fa-plus"></i>
            Tambah Kategori Baru
        </a>
    </div>

    @if (session('success'))
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 16px 20px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div style="background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0; overflow: hidden;">
        @if($categories->count() > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                    <tr>
                        <th style="padding: 20px 24px; text-align: left; font-weight: 600; color: #475569; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0;">No</th>
                        <th style="padding: 20px 24px; text-align: left; font-weight: 600; color: #475569; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0;">Nama Kategori</th>
                        <th style="padding: 20px 24px; text-align: left; font-weight: 600; color: #475569; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0;">Slug</th>
                        <th style="padding: 20px 24px; text-align: left; font-weight: 600; color: #475569; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $index => $category)
                        <tr style="transition: all 0.3s ease;" onmouseover="this.style.background='#f8fafc'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.05)';" onmouseout="this.style.background='transparent'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <td style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9;">{{ $index + $categories->firstItem() }}</td>
                            <td style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9;">
                                <div style="font-weight: 600; color: #1e293b; font-size: 15px;">{{ $category->name }}</div>
                            </td>
                            <td style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9;">
                                <div style="color: #64748b; font-size: 13px; font-family: 'Courier New', monospace; background: #f1f5f9; padding: 4px 8px; border-radius: 6px; display: inline-block;">{{ $category->slug }}</div>
                            </td>
                            <td style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9;">
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <a href="{{ route('categories.edit', $category) }}" style="background: #3b82f6; color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 500; transition: all 0.3s ease;" onmouseover="this.style.background='#2563eb'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)';" onmouseout="this.style.background='#3b82f6'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: #ef4444; color: white; padding: 8px 12px; border-radius: 8px; font-size: 13px; font-weight: 500; border: none; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#dc2626'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)';" onmouseout="this.style.background='#ef4444'; this.style.transform='translateY(0)'; this.style.boxShadow='none';" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 60px 20px; color: #64748b;">
                <i class="fas fa-tags" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
                <h3 style="font-size: 18px; font-weight: 600; color: #475569; margin-bottom: 8px;">Belum ada kategori</h3>
                <p>Mulai buat kategori pertama Anda untuk mengorganisir artikel</p>
            </div>
        @endif
    </div>

    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $categories->links() }}
    </div>
</div>
@endsection
