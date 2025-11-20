<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">Welcome back, {{ Auth::user()->name }}!</h3>
                    
                    @if(Auth::user()->isAdmin())
                        <p class="text-gray-600 mb-6">You're logged in as an administrator.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('admin.posts.index') }}" class="block p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <h4 class="font-semibold text-lg mb-2">Manage Posts</h4>
                                <p class="text-gray-600 text-sm">Create, edit, and delete blog posts</p>
                            </a>
                            
                            <a href="{{ route('blog.index') }}" class="block p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <h4 class="font-semibold text-lg mb-2">View Blog</h4>
                                <p class="text-gray-600 text-sm">See your blog as visitors see it</p>
                            </a>
                        </div>
                    @else
                        <p class="text-gray-600">You're logged in! <a href="{{ route('blog.index') }}" class="text-indigo-600 hover:text-indigo-800">Browse the blog</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
