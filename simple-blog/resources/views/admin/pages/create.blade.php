<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Create Page') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-6">
            <form action="{{ route('admin.pages.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Title -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('title') border-red-500 @enderror" 
                               value="{{ old('title') }}" 
                               placeholder="Enter page title..."
                               required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="12" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('content') border-red-500 @enderror" 
                                  placeholder="Write your page content...">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SEO Meta -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                <textarea name="meta_description" 
                                          id="meta_description" 
                                          rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition" 
                                          placeholder="Brief description for search engines (max 160 characters)">{{ old('meta_description') }}</textarea>
                            </div>

                            <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                                <input type="text" 
                                       name="meta_keywords" 
                                       id="meta_keywords" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition" 
                                       value="{{ old('meta_keywords') }}" 
                                       placeholder="keyword1, keyword2, keyword3">
                            </div>
                        </div>
                    </div>

                    <!-- Publish Status -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="is_published" 
                                   value="1" 
                                   {{ old('is_published') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 h-5 w-5">
                            <span class="ml-3 text-sm font-medium text-gray-700">Publish this page</span>
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                        <a href="{{ route('admin.pages.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition">
                            Create Page
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', '|', 'undo', 'redo']
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    @endpush
</x-app-layout>
