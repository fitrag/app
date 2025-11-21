@extends('components.layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 font-sans">Notifications</h1>
        <p class="mt-2 text-gray-600">Stay updated with your latest interactions</p>
    </div>

    @if($notifications->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No notifications</h3>
            <p class="mt-2 text-sm text-gray-500">You're all caught up! Check back later for new updates.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y divide-gray-200">
            @foreach($notifications as $notification)
                <a href="{{ $notification->link }}" 
                   class="block px-6 py-4 hover:bg-gray-50 transition-colors {{ $notification->read_at ? '' : 'bg-blue-50' }}">
                    <div class="flex items-start gap-4">
                        <!-- Unread Indicator -->
                        <div class="flex-shrink-0 w-2 h-2 mt-2">
                            @if(!$notification->read_at)
                                <span class="block w-2 h-2 bg-blue-500 rounded-full"></span>
                            @endif
                        </div>
                        
                        <!-- Actor Avatar -->
                        <div class="flex-shrink-0">
                            @if($notification->actor->avatar)
                                <img class="h-10 w-10 rounded-full object-cover" 
                                     src="{{ asset('storage/' . $notification->actor->avatar) }}" 
                                     alt="{{ $notification->actor->name }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold">
                                    {{ substr($notification->actor->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Notification Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">
                                {{ $notification->message }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        
                        <!-- Arrow Icon -->
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
