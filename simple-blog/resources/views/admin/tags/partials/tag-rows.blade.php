@foreach($tags as $tag)
    <tr class="hover:bg-gray-50 transition">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">{{ $tag->name }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-500">{{ $tag->slug }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                {{ $tag->posts_count }} {{ Str::plural('post', $tag->posts_count) }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            @if(auth()->user()->isAdmin() || auth()->id() === $tag->user_id)
                <a href="{{ route('admin.tags.edit', $tag) }}" class="text-gray-600 hover:text-gray-900 mr-3">Edit</a>
                <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline-block delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                </form>
            @endif
        </td>
    </tr>
@endforeach
