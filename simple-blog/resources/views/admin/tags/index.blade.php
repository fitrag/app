<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Tags') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with Create Button -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">All Tags</h3>
                    <p class="text-sm text-gray-500 mt-1">Manage your blog tags</p>
                </div>
                <a href="{{ route('admin.tags.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Tag
                </a>
            </div>

            <!-- Tags Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200" x-data="tagList()">
                @if($tags->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tags</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new tag.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.tags.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition">
                                Create Tag
                            </a>
                        </div>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posts</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @include('admin.tags.partials.tag-rows', ['tags' => $tags])
                        </tbody>
                    </table>

                    <!-- Load More -->
                    @if($tags->hasMorePages())
                        <div id="load-more-container" class="border-t border-gray-200">
                            <button @click="loadMore" 
                                    :disabled="loading"
                                    class="w-full group relative flex justify-center py-4 text-sm font-medium text-gray-500 hover:text-indigo-600 hover:bg-gray-50 transition-colors duration-200 focus:outline-none disabled:opacity-50">
                                <span x-show="!loading" class="flex items-center">
                                    Load More Tags
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
        function tagList() {
            return {
                loading: false,
                page: 1,
                hasMore: {{ $tags->hasMorePages() ? 'true' : 'false' }},
                
                loadMore() {
                    if (this.loading || !this.hasMore) return;
                    
                    this.loading = true;
                    this.page++;
                    
                    const searchParams = new URLSearchParams(window.location.search);
                    searchParams.set('page', this.page);
                    
                    fetch(`{{ route('admin.tags.index') }}?${searchParams.toString()}`, {
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
                        }
                    })
                    .catch(error => {
                        console.error('Error loading more tags:', error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                }
            }
        }
    </script>
</x-app-layout>
