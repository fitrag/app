@php
    $depth = $depth ?? 0;
    $maxDepth = 3;
@endphp

<div class="flex gap-4 {{ $depth > 0 ? 'ml-12 mt-4' : '' }}" id="comment-{{ $comment->id }}">
    <div class="flex-shrink-0">
        <div class="w-{{ $depth > 0 ? '8' : '10' }} h-{{ $depth > 0 ? '8' : '10' }} rounded-full bg-gray-200 flex items-center justify-center text-{{ $depth > 0 ? 'xs' : 'sm' }} font-bold text-gray-600">
            {{ substr($comment->user->name, 0, 1) }}
        </div>
    </div>
    <div class="flex-grow {{ $depth > 0 ? 'border-l-2 border-gray-100 pl-4' : '' }}">
        <div class="flex items-center gap-2 mb-1">
            <h4 class="font-bold text-gray-900 text-{{ $depth > 0 ? 'sm' : 'base' }}">{{ $comment->user->name }}</h4>
            
            @if($comment->user->role === 'admin')
                <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded">Admin</span>
            @endif
            
            @if($comment->user_id === $post->user_id)
                <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded">Author</span>
            @endif
            
            <time datetime="{{ $comment->created_at->toIso8601String() }}" class="text-sm text-gray-500 ml-auto">
                {{ $comment->created_at->diffForHumans() }}
            </time>
        </div>
        <div class="prose prose-sm max-w-none text-gray-700 mb-2">
            <p>{{ $comment->content }}</p>
        </div>
        
        <div class="flex items-center gap-4 mt-2">
            <!-- Love Button -->
            <div x-data="{ 
                loved: {{ Auth::check() && $comment->isLovedBy(Auth::user()) ? 'true' : 'false' }},
                lovesCount: {{ $comment->loves()->count() }},
                toggleLove() {
                    if (!{{ Auth::check() ? 'true' : 'false' }}) {
                        window.location.href = '{{ route('login') }}';
                        return;
                    }
                    
                    fetch('{{ route('comments.love', $comment) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.loved = data.is_loved;
                            this.lovesCount = data.loves_count;
                        }
                    });
                }
            }" class="inline-flex items-center gap-1">
                <button @click="toggleLove()" 
                        :class="loved ? 'text-red-500' : 'text-gray-400 hover:text-red-500'"
                        class="transition-colors">
                    <svg class="w-4 h-4" :fill="loved ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
                <span x-text="lovesCount" class="text-xs text-gray-500"></span>
            </div>
            
            @auth
                @if($depth < $maxDepth)
                    <button 
                        @click="replyingTo = replyingTo === {{ $comment->id }} ? null : {{ $comment->id }}"
                        class="text-sm text-gray-500 hover:text-gray-900 font-medium transition-colors">
                        Reply
                    </button>
                @endif
            @endauth
        </div>
        
        @auth
            @if($depth < $maxDepth)
                <div x-show="replyingTo === {{ $comment->id }}" x-cloak class="mt-4">
                    <form action="{{ route('comments.store', $post) }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <div class="mb-2">
                            <textarea name="content" 
                                      rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition text-sm" 
                                      placeholder="Write your reply..." 
                                      required></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" 
                                    @click="replyingTo = null"
                                    class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                                Post Reply
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        @endauth
        
        @if($comment->replies->isNotEmpty())
            <div class="mt-4 space-y-4">
                @foreach($comment->replies as $reply)
                    @include('blog.partials.comment-item', ['comment' => $reply, 'post' => $post, 'depth' => $depth + 1])
                @endforeach
            </div>
        @endif
    </div>
</div>
