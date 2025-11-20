<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Navigation Menus') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with Create Button -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">All Menu Items</h3>
                    <p class="text-sm text-gray-500 mt-1">Drag and drop to reorder. Drag items into each other to create submenus</p>
                </div>
                <a href="{{ route('admin.menus.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Menu Item
                </a>
            </div>

            <!-- Menus List -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                @if($menus->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No menu items</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new menu item.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.menus.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition">
                                Create Menu Item
                            </a>
                        </div>
                    </div>
                @else
                    <div id="menu-container" class="p-6">
                        <ul id="menu-sortable" class="space-y-2">
                            @foreach($menus as $menu)
                                <li class="menu-item" data-id="{{ $menu->id }}" data-parent-id="">
                                    <div class="flex items-center bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition group">
                                        <!-- Drag Handle -->
                                        <div class="drag-handle cursor-grab active:cursor-grabbing mr-3">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                            </svg>
                                        </div>

                                        <!-- Menu Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-3">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $menu->label }}</h4>
                                                @if($menu->children->count() > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $menu->children->count() }} submenu(s)
                                                    </span>
                                                @endif
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $menu->location === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800' }}">
                                                    {{ ucfirst($menu->location) }}
                                                </span>
                                                @if($menu->is_active)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">{{ $menu->url }}</p>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center space-x-2 ml-4">
                                            <a href="{{ route('admin.menus.edit', $menu) }}" class="text-gray-600 hover:text-gray-900 text-sm">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="inline-block delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Children (Submenus) -->
                                    @if($menu->children->count() > 0)
                                        <ul class="nested-sortable ml-12 mt-2 space-y-2" data-parent-id="{{ $menu->id }}">
                                            @foreach($menu->children as $child)
                                                <li class="menu-item" data-id="{{ $child->id }}" data-parent-id="{{ $menu->id }}">
                                                    <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg p-3 hover:shadow-md transition group">
                                                        <!-- Drag Handle -->
                                                        <div class="drag-handle cursor-grab active:cursor-grabbing mr-3">
                                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                            </svg>
                                                        </div>

                                                        <!-- Submenu Icon -->
                                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>

                                                        <!-- Menu Info -->
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center space-x-2">
                                                                <h4 class="text-sm font-medium text-gray-700">{{ $child->label }}</h4>
                                                                @if($child->is_active)
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                                        Active
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                                        Inactive
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <p class="text-xs text-gray-500 mt-1">{{ $child->url }}</p>
                                                        </div>

                                                        <!-- Actions -->
                                                        <div class="flex items-center space-x-2 ml-4">
                                                            <a href="{{ route('admin.menus.edit', $child) }}" class="text-gray-600 hover:text-gray-900 text-sm">
                                                                Edit
                                                            </a>
                                                            <form action="{{ route('admin.menus.destroy', $child) }}" method="POST" class="inline-block delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sortable for main menu
            const mainList = document.getElementById('menu-sortable');
            if (!mainList) return;

            // Function to initialize sortable
            function initSortable(element, isNested = false) {
                return new Sortable(element, {
                    group: 'nested',
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'opacity-50',
                    dragClass: 'shadow-lg',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    onEnd: function(evt) {
                        saveMenuOrder();
                    }
                });
            }

            // Initialize main sortable
            initSortable(mainList);

            // Initialize nested sortables
            document.querySelectorAll('.nested-sortable').forEach(function(nestedList) {
                initSortable(nestedList, true);
            });

            // Function to save menu order
            function saveMenuOrder() {
                const items = [];
                
                // Get all parent menu items
                document.querySelectorAll('#menu-sortable > .menu-item').forEach((item, index) => {
                    const menuData = {
                        id: item.dataset.id,
                        parent_id: null,
                        order: index,
                        children: []
                    };

                    // Get children if exists
                    const childrenList = item.querySelector('.nested-sortable');
                    if (childrenList) {
                        childrenList.querySelectorAll('.menu-item').forEach((child, childIndex) => {
                            menuData.children.push({
                                id: child.dataset.id,
                                parent_id: item.dataset.id,
                                order: childIndex
                            });
                        });
                    }

                    items.push(menuData);
                });

                // Send AJAX request
                fetch('{{ route("admin.menus.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ items: items })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (window.Toast) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Menu order updated successfully'
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (window.Toast) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to update menu order'
                        });
                    }
                });
            }

            // Delete confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to delete this menu item?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
