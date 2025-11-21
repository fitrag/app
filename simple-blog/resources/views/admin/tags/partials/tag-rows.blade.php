@foreach($tags as $tag)
    <tr class="hover:bg-gray-50 transition md:table-row block bg-white md:bg-transparent rounded-lg shadow-sm md:shadow-none mb-4 md:mb-0 border md:border-0 overflow-hidden">
        <td class="md:table-cell block w-full md:w-auto px-4 py-4 md:px-6 md:py-4 border-b md:border-0 bg-gray-50 md:bg-transparent">
            <div class="text-sm font-medium text-gray-900">{{ $tag->name }}</div>
            <div class="text-sm text-gray-500 md:hidden mt-1">{{ $tag->slug }}</div>
        </td>
        <td class="md:table-cell block w-full md:w-auto px-4 py-2 md:px-6 md:py-4 whitespace-nowrap hidden md:table-cell">
            <div class="text-sm text-gray-500">{{ $tag->slug }}</div>
        </td>
        <td class="md:table-cell block w-full md:w-auto px-4 py-2 md:px-6 md:py-4 whitespace-nowrap flex md:table-cell justify-between items-center">
            <span class="md:hidden font-medium text-gray-500 text-sm">Posts:</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                {{ $tag->posts_count }} {{ Str::plural('post', $tag->posts_count) }}
            </span>
        </td>
        <td class="md:table-cell block w-full md:w-auto px-4 py-3 md:px-6 md:py-4 whitespace-nowrap text-right text-sm font-medium border-t md:border-0 bg-gray-50 md:bg-transparent flex justify-end gap-4 md:block">
            @if(auth()->user()->isAdmin() || auth()->id() === $tag->user_id)
                <a href="{{ route('admin.tags.edit', $tag) }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center md:mr-3">
                    <span class="md:hidden mr-1">Edit</span>
                    <span class="hidden md:inline">Edit</span>
                    <svg class="w-4 h-4 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </a>
                <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline-block delete-form md:ml-0 ml-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center">
                        <span class="md:hidden mr-1">Delete</span>
                        <span class="hidden md:inline">Delete</span>
                        <svg class="w-4 h-4 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            @endif
        </td>
    </tr>
@endforeach
