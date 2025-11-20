@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.novels.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Novel
        </a>
        
        <h1 class="text-2xl font-bold text-gray-900">Edit Novel</h1>
        <p class="text-gray-600 mt-1">Edit informasi novel {{ $novel->title }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Form Edit Novel</h2>
        </div>
        
        <form action="{{ route('admin.novels.update', $novel) }}" method="POST" class="p-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Novel</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title', $novel->title) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('title') border-red-500 @enderror"
                        placeholder="Masukkan judul novel"
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Penulis</label>
                    <select 
                        name="user_id" 
                        id="user_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('user_id') border-red-500 @enderror"
                    >
                        <option value="">Pilih Penulis</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('user_id', $novel->user_id) == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Genre <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($genres as $key => $value)
                            <div class="flex items-center">
                                <input 
                                    id="genre_{{ $key }}" 
                                    name="genres[]" 
                                    type="checkbox" 
                                    value="{{ $key }}"
                                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                                    {{ in_array($key, old('genres', $novel->getGenresAttribute())) ? 'checked' : '' }}
                                >
                                <label for="genre_{{ $key }}" class="ml-2 block text-sm text-gray-700">
                                    {{ $value }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('genres')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('genres.*')
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
                        <option value="draft" {{ old('status', $novel->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $novel->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status', $novel->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <div class="flex items-center h-5">
                        <input 
                            id="is_featured" 
                            name="is_featured" 
                            type="checkbox" 
                            {{ old('is_featured', $novel->is_featured) ? 'checked' : '' }}
                            class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded"
                        >
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_featured" class="font-medium text-gray-700">Featured Novel</label>
                        <p class="text-gray-500">Centang untuk menjadikan novel sebagai featured</p>
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="Masukkan deskripsi novel"
                    >{{ old('description', $novel->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cover Novel</label>
                    <div class="mt-1 flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-32 h-40 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                                <img id="cover-preview" src="{{ $novel->getCoverImageUrl() }}" alt="Preview Cover" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div class="ml-5">
                            <div class="flex items-center">
                                <label for="cover_image" class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 cursor-pointer">
                                    <i class="fas fa-upload mr-2"></i>
                                    Ganti File
                                </label>
                                <input id="cover_image" name="cover_image" type="file" class="sr-only" accept="image/*">
                                <span id="file-name" class="ml-3 text-sm text-gray-500">Tidak ada file dipilih</span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Format: JPG, JPEG, PNG, GIF. Maksimal 2MB.
                            </p>
                            @error('cover_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.novels.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-500 to-orange-600 hover:from-red-600 hover:to-orange-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Update Novel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('cover_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileName = document.getElementById('file-name');
    const preview = document.getElementById('cover-preview');
    
    if (file) {
        fileName.textContent = file.name;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    } else {
        fileName.textContent = 'Tidak ada file dipilih';
    }
});
</script>
@endsection