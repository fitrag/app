<!-- Notification Bell -->
<div class="relative" x-data="{
    open: false,
    unreadCount: 0,
    notifications: [],
    loading: false,
    pollingInterval: null,
    
    async fetchNotifications() {
        this.loading = true;
        try {
            const response = await fetch('{{ route('notifications.recent') }}');
            const data = await response.json();
            this.notifications = data.notifications;
            this.unreadCount = data.unread_count;
        } catch (error) {
            console.error('Error fetching notifications:', error);
        } finally {
            this.loading = false;
        }
    },
    
    async fetchUnreadCount() {
        try {
            const response = await fetch('{{ route('notifications.unreadCount') }}');
            const data = await response.json();
            this.unreadCount = data.count;
        } catch (error) {
            console.error('Error fetching unread count:', error);
        }
    },
    
    async markAsRead(notificationId, link) {
        try {
            await fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            window.location.href = link;
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    },
    
    async markAllAsRead() {
        try {
            await fetch('{{ route('notifications.readAll') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            this.unreadCount = 0;
            this.notifications = this.notifications.map(n => ({ ...n, is_read: true }));
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    },
    
    startPolling() {
        // Poll every 30 seconds for new notifications
        this.pollingInterval = setInterval(() => {
            this.fetchUnreadCount();
        }, 30000);
    },
    
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    },
    
    init() {
        this.fetchNotifications();
        this.startPolling();
    }
}" @click.away="open = false" x-init="$watch('$el', (value) => { if (!value) stopPolling(); })">
    <button @click="open = !open; if(open) fetchNotifications()" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full min-w-[18px]"></span>
    </button>
    
    <!-- Notifications Dropdown -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-[32rem] overflow-hidden flex flex-col"
         style="display: none;">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
            <button @click="markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-800">Mark all as read</button>
        </div>
        
        <!-- Notifications List -->
        <div class="overflow-y-auto flex-1">
            <template x-if="loading">
                <div class="flex items-center justify-center py-8">
                    <svg class="animate-spin h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </template>
            
            <template x-if="!loading && notifications.length === 0">
                <div class="py-8 text-center text-gray-500 text-sm">
                    No notifications yet
                </div>
            </template>
            
            <template x-for="notification in notifications" :key="notification.id">
                <button @click="markAsRead(notification.id, notification.link)"
                        :class="notification.is_read ? 'bg-white' : 'bg-blue-50'"
                        class="w-full text-left px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2">
                            <span x-show="!notification.is_read" class="block w-2 h-2 bg-blue-500 rounded-full"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900" x-text="notification.message"></p>
                            <p class="text-xs text-gray-500 mt-1" x-text="notification.time_ago"></p>
                        </div>
                    </div>
                </button>
            </template>
        </div>
        
        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View all notifications</a>
        </div>
    </div>
</div>
