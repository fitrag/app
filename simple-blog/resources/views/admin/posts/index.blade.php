
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Manage Posts') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with Create Button -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">All Posts</h3>
                    <p class="text-sm text-gray-500 mt-1">Manage your blog posts</p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full md:w-auto">
                    <!-- Search -->
                    <div class="relative w-full sm:w-auto" x-data="{ search: '{{ request('search') }}' }">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" 
                               x-model="search"
                               @input.debounce.500ms="window.location.href = '{{ route('admin.posts.index') }}?search=' + search"
                               placeholder="Search posts..."
                               class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <a href="{{ route('admin.posts.create') }}" class="inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition w-full sm:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Post
                    </a>
                </div>
            </div>

            <!-- Posts List -->
            <div class="bg-white shadow-sm rounded-lg overflow-x-auto border border-gray-200" x-data="bulkActions()">
                <!-- Bulk Actions Toolbar -->
                <div x-show="selected.length > 0" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        <span class="font-semibold text-gray-900" x-text="selected.length"></span> items selected
                    </div>
                    <div class="flex space-x-2">
                        <button @click="performAction('publish')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Mark as Published
                        </button>
                        <button @click="performAction('draft')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Mark as Draft
                        </button>
                        <button @click="performAction('delete')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Delete Selected
                        </button>
                    </div>
                </div>

                @if($posts->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No posts</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new post.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition">
                                Create New Post
                            </a>
                        </div>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200 md:table block">
                        <thead class="bg-gray-50 md:table-header-group hidden">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left w-10">
                                    <input type="checkbox" 
                                           @change="toggleAll" 
                                           :checked="allSelected"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 md:table-row-group block space-y-4 md:space-y-0 p-4 md:p-0">
                            @include('admin.posts.partials.post-rows', ['posts' => $posts])
                        </tbody>
                    </table>

                    <!-- Load More -->
                    @if($posts->hasMorePages())
                        <div id="load-more-container" class="border-t border-gray-200">
                            <button @click="loadMore" 
                                    :disabled="loading"
                                    class="w-full group relative flex justify-center py-4 text-sm font-medium text-gray-500 hover:text-indigo-600 hover:bg-gray-50 transition-colors duration-200 focus:outline-none disabled:opacity-50">
                                <span x-show="!loading" class="flex items-center">
                                    Load More Posts
                                    <svg class="ml-2 w-4 h-4 transform group-hover:translate-y-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                                <span x-show="loading" class="flex items-center text-indigo-600">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Loading...
                                </span>
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <script>
        function bulkActions() {
            return {
                selected: [],
                loading: false,
                page: 1,
                hasMore: {{ $posts->hasMorePages() ? 'true' : 'false' }},
                
                get allSelected() {
                    // We need to count total visible rows for "Select All" to work correctly with Load More
                    // This is a simplification; ideally we'd track all loaded IDs
                    const visibleCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');
                    return visibleCheckboxes.length > 0 && this.selected.length === visibleCheckboxes.length;
                },
                
                toggleAll() {
                    if (this.allSelected) {
                        this.selected = [];
                    } else {
                        const visibleCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');
                        this.selected = Array.from(visibleCheckboxes).map(cb => cb.value);
                    }
                },
                
                loadMore() {
                    if (this.loading || !this.hasMore) return;
                    
                    this.loading = true;
                    this.page++;
                    
                    const searchParams = new URLSearchParams(window.location.search);
                    searchParams.set('page', this.page);
                    
                    fetch(`{{ route('admin.posts.index') }}?${searchParams.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        if (html.trim() === '') {
                            this.hasMore = false;
                            document.getElementById('load-more-container').remove();
                        } else {
                            const tbody = document.querySelector('tbody');
                            tbody.insertAdjacentHTML('beforeend', html);
                            
                            // Re-evaluate hasMore based on response or assume true until empty
                            // For simplicity, we assume if we got content, there might be more
                            // A robust solution would return JSON with hasMore flag
                        }
                    })
                    .catch(error => {
                        console.error('Error loading more posts:', error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                },

                performAction(action) {
                    if (action === 'delete') {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete them!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submitBulkAction(action);
                            }
                        });
                    } else {
                        this.submitBulkAction(action);
                    }
                },

                submitBulkAction(action) {
                    fetch('{{ route('admin.posts.bulk-action') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ids: this.selected,
                            action: action
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'An error occurred while processing your request.', 'error');
                    });
                }
            }
        }
    </script>
</x-app-layout>
