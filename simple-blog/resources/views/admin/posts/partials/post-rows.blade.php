@foreach($posts as $post)
    <tr class="hover:bg-gray-50 transition md:table-row block bg-white md:bg-transparent rounded-lg shadow-sm md:shadow-none mb-4 md:mb-0 border md:border-0 overflow-hidden">
        <td class="md:table-cell block w-full md:w-auto px-4 py-2 md:px-6 md:py-4 border-b md:border-0 bg-gray-50 md:bg-transparent">
            <input type="checkbox" 
                   value="{{ $post->id }}" 
                   x-model="selected"
                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </td>
        <td class="md:table-cell block w-full md:w-auto px-4 py-4 md:px-6 md:py-4">
            <div class="flex items-center">
                @if($post->image)
                    <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="h-12 w-12 rounded object-cover mr-4">
                @else
                    <div class="h-12 w-12 rounded bg-gray-200 mr-4 flex items-center justify-center flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                <div class="min-w-0">
                    <div class="text-sm font-medium text-gray-900 truncate">{{ $post->title }}</div>
                    <div class="text-sm text-gray-500 truncate">{{ Str::limit($post->content, 50) }}</div>
                </div>
            </div>
        </td>
        <td class="md:table-cell block w-full md:w-auto px-4 py-2 md:px-6 md:py-4 whitespace-nowrap flex md:table-cell justify-between items-center">
            <span class="md:hidden font-medium text-gray-500 text-sm">Status:</span>
            @if($post->is_published)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Published
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"></path>
                    </svg>
                    Draft
                </span>
            @endif
        </td>
        <td class="md:table-cell block w-full md:w-auto px-4 py-2 md:px-6 md:py-4 whitespace-nowrap text-sm text-gray-500 flex md:table-cell justify-between items-center">
            <span class="md:hidden font-medium text-gray-500 text-sm">Date:</span>
            <span>{{ $post->created_at->format('M d, Y') }}</span>
        </td>
        <td class="md:table-cell block w-full md:w-auto px-4 py-3 md:px-6 md:py-4 whitespace-nowrap text-right text-sm font-medium border-t md:border-0 bg-gray-50 md:bg-transparent flex justify-end gap-4 md:block">
            <a href="{{ route('admin.posts.analytics', $post) }}" class="text-purple-600 hover:text-purple-900 inline-flex items-center" title="Analytics">
                <svg class="w-4 h-4 mr-1 md:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="md:hidden">Analytics</span>
            </a>
            <a href="{{ route('admin.posts.edit', $post) }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center md:ml-3">
                <span class="md:hidden mr-1">Edit</span>
                <span class="hidden md:inline">Edit</span>
                <svg class="w-4 h-4 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </a>
            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline-block delete-form md:ml-3">
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
