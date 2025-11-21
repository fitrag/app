@extends('components.layouts.public')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16" 
         x-data="{ 
            showModal: false, 
            modalTitle: '', 
            users: [], 
            loading: false,
            currentUserId: {{ Auth::id() ?? 'null' }},
            
            fetchUsers(type) {
                this.modalTitle = type === 'followers' ? 'Followers' : 'Following';
                this.showModal = true;
                this.loading = true;
                this.users = [];
                
                fetch(`/users/{{ $user->id }}/${type}`)
                    .then(response => response.json())
                    .then(data => {
                        this.users = data.data;
                        this.loading = false;
                    });
            },
            
            toggleFollow(user) {
                if (!this.currentUserId) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }

                const url = user.is_following 
                    ? `/users/${user.id}/unfollow` 
                    : `/users/${user.id}/follow`;
                
                const method = user.is_following ? 'DELETE' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        user.is_following = !user.is_following;
                    }
                });
            }
         }">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left Column: Profile Info (Desktop Sticky) -->
            <div class="lg:w-1/3 order-first lg:order-last">
                <div class="lg:sticky lg:top-24">
                    <div class="flex flex-col items-start">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" loading="lazy" class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover mb-6">
                        @else
                            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-gray-200 flex items-center justify-center mb-6">
                                <span class="text-4xl font-bold text-gray-600 font-sans">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif


                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-sans mb-2">{{ $user->name }}</h1>
                        <div class="text-sm text-gray-500 font-sans mb-4">
                            {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} &middot; Joined {{ $user->created_at->format('M Y') }}
                        </div>

                        <!-- Follower/Following Stats -->
                        <div class="flex gap-4 text-sm font-sans mb-6">
                            @if(Auth::id() === $user->id)
                                <button @click="fetchUsers('followers')" class="text-left hover:text-green-600 transition-colors">
                                    <span class="font-bold text-gray-900">{{ $user->followers()->count() }}</span>
                                    <span class="text-gray-500">Followers</span>
                                </button>
                                <button @click="fetchUsers('following')" class="text-left hover:text-green-600 transition-colors">
                                    <span class="font-bold text-gray-900">{{ $user->following()->count() }}</span>
                                    <span class="text-gray-500">Following</span>
                                </button>
                            @else
                                <div>
                                    <span class="font-bold text-gray-900">{{ $user->followers()->count() }}</span>
                                    <span class="text-gray-500">Followers</span>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900">{{ $user->following()->count() }}</span>
                                    <span class="text-gray-500">Following</span>
                                </div>
                            @endif
                        </div>

                        @if($user->bio)
                            <p class="text-gray-600 font-serif text-lg leading-relaxed mb-6">
                                {{ $user->bio }}
                            </p>
                        @endif

                        @auth
                            @if(Auth::id() !== $user->id)
                                @if(Auth::user()->isFollowing($user))
                                    <form action="{{ route('users.unfollow', $user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-full hover:bg-gray-50 transition-colors font-sans">
                                            Unfollow
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('users.follow', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-full hover:bg-green-700 transition-colors font-sans">
                                            Follow
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('profile.edit') }}" class="text-sm text-green-600 hover:text-green-700 font-sans font-medium">
                                    Edit Profile
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-full hover:bg-green-700 transition-colors font-sans">
                                Follow
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Right Column: Post List -->
            <div class="lg:w-2/3">
                <div class="border-b border-gray-200 pb-4 mb-8">
                    <h2 class="text-xl font-bold text-gray-900 font-sans">Latest from {{ $user->name }}</h2>
                </div>

                @if($posts->isEmpty())
                    <div class="py-12 text-center">
                        <p class="text-gray-500 font-sans">No posts published yet.</p>
                    </div>
                @else
                    <div class="space-y-12">
                        @foreach($posts as $post)
                            @include('blog.partials.post-item')
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Users List Modal -->
        <div x-show="showModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true"
             style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     @click="showModal = false"
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title" x-text="modalTitle"></h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="mt-2">
                            <div x-show="loading" class="flex justify-center py-4">
                                <svg class="animate-spin h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>

                            <ul x-show="!loading" class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                                <template x-for="user in users" :key="user.id">
                                    <li class="py-4 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <template x-if="user.avatar">
                                                    <img class="h-10 w-10 rounded-full object-cover" :src="'/storage/' + user.avatar" :alt="user.name">
                                                </template>
                                                <template x-if="!user.avatar">
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold" x-text="user.name.charAt(0)"></div>
                                                </template>
                                            </div>
                                            <div class="ml-4">
                                                <a :href="'/profile/' + user.id" class="text-sm font-medium text-gray-900 hover:underline" x-text="user.name"></a>
                                                <p class="text-sm text-gray-500" x-text="user.role"></p>
                                            </div>
                                        </div>
                                        <div x-show="currentUserId && currentUserId !== user.id">
                                            <button @click="toggleFollow(user)" 
                                                    :class="user.is_following ? 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' : 'bg-green-600 border-transparent text-white hover:bg-green-700'"
                                                    class="inline-flex items-center px-3 py-1 border text-xs font-medium rounded-full shadow-sm focus:outline-none transition-colors">
                                                <span x-text="user.is_following ? 'Unfollow' : 'Follow'"></span>
                                            </button>
                                        </div>
                                    </li>
                                </template>
                                <li x-show="users.length === 0" class="py-4 text-center text-gray-500 text-sm">
                                    No users found.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
