<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Create Post') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto px-6">
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Main Content (2/3 width) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Title Section -->
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden transition-shadow duration-200 hover:shadow-sm">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-50">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900">Title</h3>
                                            <p class="text-xs text-gray-500">Give your post a catchy title</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400">
                                        <span id="title-count">0</span>/60
                                    </span>
                                </div>
                                
                                <input type="text" 
                                       name="title" 
                                       id="title" 
                                       maxlength="60"
                                       class="w-full px-0 py-3 text-2xl font-semibold text-gray-900 placeholder-gray-300 border-0 border-b-2 border-transparent focus:border-gray-900 focus:ring-0 transition-colors duration-200 @error('title') border-red-300 @enderror" 
                                       value="{{ old('title') }}" 
                                       placeholder="Enter your post title..."
                                       required>
                                
                                @error('title')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Featured Image Section -->
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden transition-shadow duration-200 hover:shadow-sm">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-50">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">Featured Image</h3>
                                        <p class="text-xs text-gray-500">Add a cover image for your post</p>
                                    </div>
                                </div>
                                
                                <!-- Upload Method Toggle -->
                                <div class="flex gap-2 mb-4">
                                    <label class="flex-1">
                                        <input type="radio" name="image_source" value="file" checked class="peer sr-only" onchange="toggleImageSource()">
                                        <div class="flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            Upload File
                                        </div>
                                    </label>
                                    <label class="flex-1">
                                        <input type="radio" name="image_source" value="url" class="peer sr-only" onchange="toggleImageSource()">
                                        <div class="flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                            From URL
                                        </div>
                                    </label>
                                </div>

                                <!-- File Upload Section -->
                                <div id="fileUploadSection">
                                    <div id="imagePreview" class="hidden mb-4">
                                        <div class="relative group">
                                            <img id="previewImg" src="" alt="Preview" class="w-full h-64 object-cover rounded-lg">
                                            <button type="button" 
                                                    onclick="removePreview()" 
                                                    class="absolute top-2 right-2 p-2 bg-white/90 backdrop-blur-sm rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-200 opacity-0 group-hover:opacity-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="uploadArea">
                                        <label for="image" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-200 border-dashed rounded-lg cursor-pointer bg-gray-50/50 hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-600">
                                                    <span class="font-semibold">Click to upload</span> or drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400">PNG, JPG, GIF up to 2MB</p>
                                            </div>
                                            <input id="image" name="image" type="file" class="hidden" accept="image/*" onchange="previewImage(event)">
                                        </label>
                                    </div>
                                </div>

                                <!-- URL Input Section -->
                                <div id="urlInputSection" class="hidden">
                                    <input type="text" 
                                           name="image_url" 
                                           id="image_url" 
                                           class="w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200" 
                                           placeholder="https://example.com/image.jpg"
                                           onchange="previewUrlImage(event)">
                                    <p class="text-xs text-gray-500 mt-2">Enter the full URL of the image</p>
                                    
                                    <div id="urlImagePreview" class="hidden mt-4">
                                        <img id="urlPreviewImg" src="" alt="Preview" class="w-full h-64 object-cover rounded-lg">
                                    </div>
                                </div>

                                @error('image')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                                @error('image_url')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden transition-shadow duration-200 hover:shadow-sm">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-50">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900">Content</h3>
                                            <p class="text-xs text-gray-500">Write your story</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400" id="word-count">0 words</span>
                                </div>
                                
                                <textarea name="content" 
                                          id="content" 
                                          rows="16"
                                          class="w-full px-0 py-3 text-base text-gray-900 placeholder-gray-300 border-0 focus:ring-0 resize-y @error('content') text-red-600 @enderror" 
                                          placeholder="Start writing your story...">{{ old('content') }}</textarea>
                                
                                @error('content')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        
                    </div>

                    <!-- Right Column - Sidebar (1/3 width) -->
                    <div class="space-y-6">
                        <!-- Publish Settings -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Publish</h3>
                            
                            <div class="flex items-center mb-4">
                                <input type="checkbox" 
                                       name="is_published" 
                                       id="is_published" 
                                       class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded" 
                                       value="1" 
                                       {{ old('is_published') ? 'checked' : '' }}>
                                <label for="is_published" class="ml-2 block text-sm text-gray-700">
                                    Publish immediately
                                </label>
                            </div>

                            <div class="flex items-center mb-4">
                                <input type="checkbox" 
                                       name="is_commentable" 
                                       id="is_commentable" 
                                       class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded" 
                                       value="1" 
                                       {{ old('is_commentable', true) ? 'checked' : '' }}>
                                <label for="is_commentable" class="ml-2 block text-sm text-gray-700">
                                    Enable Comments
                                </label>
                            </div>

                            <div class="flex items-center mb-4">
                                <input type="checkbox" 
                                       name="enable_font_adjuster" 
                                       id="enable_font_adjuster" 
                                       class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded" 
                                       value="1" 
                                       {{ old('enable_font_adjuster', true) ? 'checked' : '' }}>
                                <label for="enable_font_adjuster" class="ml-2 block text-sm text-gray-700">
                                    Enable Font Size Adjuster
                                </label>
                            </div>

                            <div class="flex items-center mb-4">
                                <input type="checkbox" 
                                       name="show_read_also" 
                                       id="show_read_also" 
                                       class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded" 
                                       value="1" 
                                       {{ old('show_read_also', true) ? 'checked' : '' }}>
                                <label for="show_read_also" class="ml-2 block text-sm text-gray-700">
                                    Show "Read Also" Widget
                                </label>
                            </div>

                            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Create Post
                            </button>

                            <a href="{{ route('admin.posts.index') }}" class="mt-2 w-full inline-flex justify-center items-center px-6 py-3 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                                Cancel
                            </a>
                        </div>


                        <!-- SEO Analyzer -->
                        @if(\App\Models\Setting::get('enable_seo_analyzer', '1') == '1')
                            @include('admin.posts.partials.seo-analyzer', ['post' => new \App\Models\Post()])
                        @endif

                        <!-- Category -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Category</h3>
                            <select name="category_id" 
                                    id="category_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tags Section -->
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden transition-shadow duration-200 hover:shadow-sm">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-50">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">Tags</h3>
                                        <p class="text-xs text-gray-500">Select relevant tags</p>
                                    </div>
                                </div>

                                <!-- Search Tags -->
                                <div class="mb-3">
                                    <div class="relative">
                                        <input type="text" 
                                               id="tag-search" 
                                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200" 
                                               placeholder="Search tags..."
                                               onkeyup="searchTags()">
                                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Tags List -->
                                <div class="space-y-1 max-h-64 overflow-y-auto custom-scrollbar" id="tags-container">
                                    @forelse($tags as $tag)
                                        <label class="flex items-center p-2.5 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 tag-item" data-tag-name="{{ strtolower($tag->name) }}">
                                            <input type="checkbox" 
                                                   name="tags[]" 
                                                   value="{{ $tag->id }}" 
                                                   class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded"
                                                   {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                            <span class="ml-3 text-sm text-gray-700">{{ $tag->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-400 text-center py-4" id="no-tags-message">No tags available</p>
                                    @endforelse
                                    <p class="text-sm text-gray-400 text-center py-4 hidden" id="no-results-message">No tags found</p>
                                </div>
                                
                                <!-- Quick Add Tag -->
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <label for="new_tag" class="block text-xs font-medium text-gray-600 mb-2">Quick Add Tag</label>
                                    <div class="flex gap-2">
                                        <input type="text" 
                                               id="new_tag" 
                                               class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200" 
                                               placeholder="Tag name">
                                        <button type="button" 
                                                onclick="createNewTag()"
                                                class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <p id="tag-error" class="text-red-500 text-xs mt-1 hidden"></p>
                                </div>

                                @error('tags')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Custom Scrollbar Style -->
                        <style>
                            .custom-scrollbar::-webkit-scrollbar {
                                width: 6px;
                            }
                            .custom-scrollbar::-webkit-scrollbar-track {
                                background: #f1f1f1;
                                border-radius: 10px;
                            }
                            .custom-scrollbar::-webkit-scrollbar-thumb {
                                background: #d1d5db;
                                border-radius: 10px;
                            }
                            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                                background: #9ca3af;
                            }
                        </style>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Title Character Counter
        const titleInput = document.getElementById('title');
        const titleCount = document.getElementById('title-count');
        
        if (titleInput && titleCount) {
            titleInput.addEventListener('input', function() {
                titleCount.textContent = this.value.length;
            });
            // Initialize count
            titleCount.textContent = titleInput.value.length;
        }

        // Word Count for Content
        const contentTextarea = document.getElementById('content');
        const wordCountEl = document.getElementById('word-count');
        
        function updateWordCount() {
            if (contentTextarea && wordCountEl) {
                const text = contentTextarea.value.trim();
                const words = text.split(/\s+/).filter(word => word.length > 0);
                wordCountEl.textContent = words.length + ' words';
            }
        }

        if (contentTextarea) {
            contentTextarea.addEventListener('input', updateWordCount);
            // Initialize word count
            updateWordCount();
        }

        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('uploadArea').classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function removePreview() {
            document.getElementById('image').value = '';
            document.getElementById('previewImg').src = '';
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('uploadArea').classList.remove('hidden');
        }

        // Live Search Tags
        function searchTags() {
            const searchInput = document.getElementById('tag-search');
            const searchTerm = searchInput.value.toLowerCase();
            const tagItems = document.querySelectorAll('.tag-item');
            const noResultsMsg = document.getElementById('no-results-message');
            let visibleCount = 0;

            tagItems.forEach(item => {
                const tagName = item.getAttribute('data-tag-name');
                if (tagName.includes(searchTerm)) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            // Show/hide no results message
            if (visibleCount === 0 && tagItems.length > 0) {
                noResultsMsg.classList.remove('hidden');
            } else {
                noResultsMsg.classList.add('hidden');
            }
        }

        function createNewTag() {
            const input = document.getElementById('new_tag');
            const errorMsg = document.getElementById('tag-error');
            const name = input.value.trim();

            if (!name) return;

            // Reset error
            errorMsg.classList.add('hidden');
            errorMsg.textContent = '';

            fetch('{{ route("admin.tags.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ name: name })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new tag to list
                    const container = document.getElementById('tags-container');
                    const noTagsMsg = document.getElementById('no-tags-message');
                    
                    if (noTagsMsg) {
                        noTagsMsg.remove();
                    }

                    const label = document.createElement('label');
                    label.className = 'flex items-center';
                    label.innerHTML = `
                        <input type="checkbox" 
                               name="tags[]" 
                               value="${data.tag.id}" 
                               class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded"
                               checked>
                        <span class="ml-2 text-sm text-gray-700">${data.tag.name}</span>
                    `;
                    
                    container.prepend(label);
                    input.value = '';
                } else {
                    // Handle validation errors
                    errorMsg.textContent = data.message || 'Error creating tag';
                    errorMsg.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMsg.textContent = 'An error occurred. Please try again.';
                errorMsg.classList.remove('hidden');
            });
        }
    </script>

    <script>
        // Toggle between file upload and URL input
        function toggleImageSource() {
            const fileSection = document.getElementById('fileUploadSection');
            const urlSection = document.getElementById('urlInputSection');
            const selectedSource = document.querySelector('input[name="image_source"]:checked').value;
            
            if (selectedSource === 'file') {
                fileSection.classList.remove('hidden');
                urlSection.classList.add('hidden');
                // Clear URL input
                document.getElementById('image_url').value = '';
                document.getElementById('urlImagePreview').classList.add('hidden');
            } else {
                fileSection.classList.add('hidden');
                urlSection.classList.remove('hidden');
                // Clear file input
                document.getElementById('image').value = '';
                document.getElementById('imagePreview').classList.add('hidden');
            }
        }

        // Preview uploaded file
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('uploadArea').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Preview URL image
        function previewUrlImage(event) {
            const url = event.target.value;
            if (url) {
                document.getElementById('urlPreviewImg').src = url;
                document.getElementById('urlImagePreview').classList.remove('hidden');
            } else {
                document.getElementById('urlImagePreview').classList.add('hidden');
            }
        }

        // Remove preview
        function removePreview() {
            document.getElementById('image').value = '';
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('uploadArea').classList.remove('hidden');
        }
    </script>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'blockQuote', '|',
                        'undo', 'redo'
                    ]
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                    ]
                }
            })
            .then(editor => {
                window.editor = editor;
                
                // Add custom button to toolbar
                const button = document.createElement('button');
                button.innerHTML = `<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;"><path d="M6.91 10.54c.26-.23.64-.21.88.03l3.36 3.14 2.23-2.06a.64.64 0 0 1 .87 0l2.52 2.97V4.5H3.2v10.12l3.71-4.08zm10.27-7.51c.6 0 1.09.47 1.09 1.05v11.84c0 .59-.49 1.06-1.09 1.06H2.79c-.6 0-1.09-.47-1.09-1.06V4.08c0-.58.49-1.05 1.1-1.05h14.38zm-5.22 5.56a1.96 1.96 0 1 1 3.4-1.96 1.96 1.96 0 0 1-3.4 1.96z"/></svg>`;
                button.className = 'ck ck-button ck-off';
                button.type = 'button';
                button.title = 'Insert Image from URL';
                button.style.cssText = 'padding: 0; margin: 0 2px;';
                
                button.addEventListener('click', () => {
                    const url = prompt('Masukkan URL gambar:');
                    if (url) {
                        const viewFragment = editor.data.processor.toView(
                            `<figure class="image"><img src="${url}" alt=""></figure>`
                        );
                        const modelFragment = editor.data.toModel(viewFragment);
                        editor.model.insertContent(modelFragment);
                    }
                });
                
                // Simply append button to toolbar
                const toolbar = document.querySelector('.ck-toolbar__items');
                if (toolbar) {
                    toolbar.appendChild(button);
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    @endpush
</x-app-layout>
