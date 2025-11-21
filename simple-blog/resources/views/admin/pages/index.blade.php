<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Pages') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with Create Button -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">All Pages</h3>
                    <p class="text-sm text-gray-500 mt-1">Manage your static pages</p>
                </div>
                <a href="{{ route('admin.pages.create') }}" class="inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition w-full md:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Page
                </a>
            </div>

            <!-- Pages Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-x-auto border border-gray-200">
                @if($pages->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No pages</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new page.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition">
                                Create Page
                            </a>
                        </div>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200 md:table block">
                        <thead class="bg-gray-50 md:table-header-group hidden">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 md:table-row-group block space-y-4 md:space-y-0 p-4 md:p-0">
                            @foreach($pages as $page)
                                <tr class="hover:bg-gray-50 transition md:table-row block bg-white md:bg-transparent rounded-lg shadow-sm md:shadow-none mb-4 md:mb-0 border md:border-0 overflow-hidden">
                                    <td class="md:table-cell block w-full md:w-auto px-4 py-4 md:px-6 md:py-4 border-b md:border-0 bg-gray-50 md:bg-transparent">
                                        <div class="text-sm font-medium text-gray-900">{{ $page->title }}</div>
                                        <div class="text-sm text-gray-500 md:hidden mt-1">/page/{{ $page->slug }}</div>
                                    </td>
                                    <td class="md:table-cell block w-full md:w-auto px-4 py-2 md:px-6 md:py-4 whitespace-nowrap hidden md:table-cell">
                                        <div class="text-sm text-gray-500">/page/{{ $page->slug }}</div>
                                    </td>
                                    <td class="md:table-cell block w-full md:w-auto px-4 py-2 md:px-6 md:py-4 whitespace-nowrap flex md:table-cell justify-between items-center">
                                        <span class="md:hidden font-medium text-gray-500 text-sm">Author:</span>
                                        <div class="text-sm text-gray-500">{{ $page->user->name }}</div>
                                    </td>
                                    <td class="md:table-cell block w-full md:w-auto px-4 py-2 md:px-6 md:py-4 whitespace-nowrap flex md:table-cell justify-between items-center">
                                        <span class="md:hidden font-medium text-gray-500 text-sm">Status:</span>
                                        @if($page->is_published)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Published
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="md:table-cell block w-full md:w-auto px-4 py-2 md:px-6 md:py-4 whitespace-nowrap text-sm text-gray-500 flex md:table-cell justify-between items-center">
                                        <span class="md:hidden font-medium text-gray-500 text-sm">Created:</span>
                                        {{ $page->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="md:table-cell block w-full md:w-auto px-4 py-3 md:px-6 md:py-4 whitespace-nowrap text-right text-sm font-medium border-t md:border-0 bg-gray-50 md:bg-transparent flex justify-end gap-4 md:block">
                                        @if($page->is_published)
                                            <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-900 inline-flex items-center md:mr-3">
                                                <span class="md:hidden mr-1">View</span>
                                                <span class="hidden md:inline">View</span>
                                                <svg class="w-4 h-4 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.pages.edit', $page) }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center md:mr-3">
                                            <span class="md:hidden mr-1">Edit</span>
                                            <span class="hidden md:inline">Edit</span>
                                            <svg class="w-4 h-4 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline-block delete-form md:ml-0 ml-3">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center">
                                                <span class="md:hidden mr-1">Delete</span>
                                                <span class="hidden md:inline">Delete</span>
                                                <svg class="w-4 h-4 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $pages->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
