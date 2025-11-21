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
                        <!-- Title -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('title') border-red-500 @enderror" 
                                   value="{{ old('title') }}" 
                                   placeholder="Enter post title..."
                                   required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                            <textarea name="content" 
                                      id="content" 
                                      rows="12" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('content') border-red-500 @enderror" 
                                      placeholder="Write your post content...">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Featured Image -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <label class="block text-sm font-medium text-gray-700 mb-4">Featured Image</label>
                            
                            <!-- Upload Method Toggle -->
                            <div class="flex gap-4 mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="image_source" value="file" checked class="mr-2" onchange="toggleImageSource()">
                                    <span class="text-sm text-gray-700">Upload File</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="image_source" value="url" class="mr-2" onchange="toggleImageSource()">
                                    <span class="text-sm text-gray-700">From URL</span>
                                </label>
                            </div>

                            <!-- File Upload Section -->
                            <div id="fileUploadSection">
                                <!-- Preview Container -->
                                <div id="imagePreview" class="hidden mb-4">
                                    <img id="previewImg" src="" alt="Preview" class="h-48 w-auto rounded-lg border border-gray-200">
                                    <button type="button" onclick="removePreview()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        Remove image
                                    </button>
                                </div>

                                <div id="uploadArea" class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-gray-900 hover:text-gray-700">
                                                <span>Upload a file</span>
                                                <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                            </div>

                            <!-- URL Input Section -->
                            <div id="urlInputSection" class="hidden">
                                <input type="text" 
                                       name="image_url" 
                                       id="image_url" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition" 
                                       placeholder="https://example.com/image.jpg"
                                       onchange="previewUrlImage(event)">
                                <p class="text-xs text-gray-500 mt-2">Enter the full URL of the image</p>
                                
                                <!-- URL Preview -->
                                <div id="urlImagePreview" class="hidden mt-4">
                                    <img id="urlPreviewImg" src="" alt="Preview" class="h-48 w-auto rounded-lg border border-gray-200">
                                </div>
                            </div>

                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @error('image_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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

                        <!-- Tags -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Tags</h3>
                            <div class="space-y-2 max-h-64 overflow-y-auto" id="tags-container">
                                @forelse($tags as $tag)
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="tags[]" 
                                               value="{{ $tag->id }}" 
                                               class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300 rounded"
                                               {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500" id="no-tags-message">No tags available</p>
                                @endforelse
                            </div>
                            
                            <!-- Quick Add Tag -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <label for="new_tag" class="block text-xs font-medium text-gray-700 mb-1">Add New Tag</label>
                                <div class="flex gap-2">
                                    <input type="text" 
                                           id="new_tag" 
                                           class="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-gray-900 focus:border-gray-900" 
                                           placeholder="Tag name">
                                    <button type="button" 
                                            onclick="createNewTag()"
                                            class="px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-md hover:bg-gray-800 transition">
                                        Add
                                    </button>
                                </div>
                                <p id="tag-error" class="text-red-500 text-xs mt-1 hidden"></p>
                            </div>

                            @error('tags')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
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
