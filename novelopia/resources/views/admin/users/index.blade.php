@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-users mr-3 text-blue-600"></i>
                    Manajemen User
                </h1>
                <p class="text-gray-600 mt-1">Kelola semua pengguna platform NovelVerse</p>
            </div>
            
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Tambah User Baru
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total User</p>
                    <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-red-100 text-red-600 mr-4">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Admin</p>
                    <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::where('role', 'admin')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-user-edit text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kreator</p>
                    <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::where('role', 'kreator')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border border-gray-100 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-user text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pembaca</p>
                    <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::where('role', 'biasa')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari User</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        id="search" 
                        value="{{ request('search') }}"
                        placeholder="Nama atau email..."
                        class="pl-10 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>
            
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select 
                    name="role" 
                    id="role"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kreator" {{ request('role') == 'kreator' ? 'selected' : '' }}>Kreator</option>
                    <option value="biasa" {{ request('role') == 'biasa' ? 'selected' : '' }}>Pembaca</option>
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select 
                    name="status" 
                    id="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-sync"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-list mr-3 text-blue-600"></i>
                    Daftar User
                </h2>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    {{ $users->total() }} User
                </span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            @if($users->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center text-white font-medium mr-4">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                {!! $user->getStatusBadge() !!}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                                {{ $user->created_at->format('d M Y') }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="far fa-clock mr-1 text-gray-400"></i>
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-{{ $user->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $user->is_active ? 'yellow' : 'green' }}-900 hover:bg-{{ $user->is_active ? 'yellow' : 'green' }}-50 rounded-lg transition-colors" 
                                            title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} User">
                                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check-circle' }}"></i>
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors" 
                                            onclick="return confirm('Hapus user {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')" title="Hapus User">
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
                    <i class="fas fa-users text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada User Ditemukan</h3>
                <p class="text-gray-500 mb-6">Coba ubah filter pencarian Anda</p>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah User Pertama
                </a>
            </div>
            @endif
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $users->firstItem() }}</span> sampai 
                    <span class="font-medium">{{ $users->lastItem() }}</span> dari 
                    <span class="font-medium">{{ $users->total() }}</span> user
                </div>
                <div>
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-bolt mr-3 text-yellow-500"></i>
            Aksi Cepat
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.create') }}" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100 hover:from-green-100 hover:to-emerald-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center text-white mr-4">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Tambah User</h4>
                    <p class="text-sm text-gray-600">Buat user baru</p>
                </div>
            </a>
            
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="flex items-center p-4 bg-gradient-to-r from-red-50 to-orange-50 rounded-lg border border-red-100 hover:from-red-100 hover:to-orange-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-red-500 flex items-center justify-center text-white mr-4">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">{{ App\Models\User::where('role', 'admin')->count() }} Admin</h4>
                    <p class="text-sm text-gray-600">Lihat semua admin</p>
                </div>
            </a>
            
            <a href="{{ route('admin.users.index', ['role' => 'kreator']) }}" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100 hover:from-purple-100 hover:to-pink-100 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center text-white mr-4">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">{{ App\Models\User::where('role', 'kreator')->count() }} Kreator</h4>
                    <p class="text-sm text-gray-600">Lihat semua kreator</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection