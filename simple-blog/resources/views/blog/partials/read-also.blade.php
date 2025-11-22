<div class="mt-8 mb-12 p-4 sm:p-6 bg-gray-50 border-l-4 border-green-500 rounded-r-xl">
    <span class="block text-xs font-bold text-green-600 uppercase tracking-wider mb-1 sm:mb-2">Baca Juga</span>
    <a href="{{ route('blog.show', $post->slug) }}" class="block text-base sm:text-lg font-bold text-gray-900 hover:text-green-600 transition-colors font-serif leading-tight">
        {{ $post->title }}
    </a>
</div>
