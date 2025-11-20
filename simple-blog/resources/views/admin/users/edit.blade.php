<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto px-6">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Right Column - Sidebar (1/3 width) -->
                    <div class="space-y-6">
                        <!-- Role & Actions -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Role & Actions</h3>
                            
                            <!-- Role -->
                            <div class="mb-6">
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                <select name="role" 
                                        id="role" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="editor" {{ old('role', $user->role) == 'editor' ? 'selected' : '' }}>Editor</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update User
                            </button>

                            <a href="{{ route('admin.users.index') }}" class="mt-2 w-full inline-flex justify-center items-center px-6 py-3 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                                Cancel
                            </a>
                        </div>

                        <!-- Monetization Enabled -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <label for="monetization_enabled" class="block text-sm font-medium text-gray-700 mb-1">
                                        Monetization
                                    </label>
                                    <p class="text-xs text-gray-500">Allow this user to earn coins</p>
                                </div>
                                <label for="monetization_enabled" class="relative inline-block w-12 h-6 transition duration-200 ease-in-out rounded-full cursor-pointer">
                                    <input type="checkbox" 
                                           name="monetization_enabled" 
                                           id="monetization_enabled" 
                                           value="1" 
                                           class="peer sr-only" 
                                           {{ old('monetization_enabled', $user->monetization_enabled) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Left Column - Main Content (2/3 width) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- User Details -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">User Details</h3>
                            
                            <!-- Name -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('name') border-red-500 @enderror" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('email') border-red-500 @enderror" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Security</h3>
                            <p class="text-sm text-gray-500 mb-4">Leave blank to keep current password.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('password') border-red-500 @enderror" 
                                           placeholder="New password">
                                    @error('password')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition" 
                                           placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <!-- Menu Access Permissions -->
                        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Menu Access Permissions</h3>
                            <p class="text-sm text-gray-500 mb-6">Select which admin menus this user can access.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($adminMenus as $menu)
                                    <label class="relative flex items-start py-3 px-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                        <div class="min-w-0 flex-1 text-sm">
                                            <div class="font-medium text-gray-700 select-none">{{ $menu->label }}</div>
                                        </div>
                                        <div class="ml-3 flex items-center h-5">
                                            <input id="menu_{{ $menu->id }}" 
                                                   name="menus[]" 
                                                   value="{{ $menu->id }}" 
                                                   type="checkbox" 
                                                   {{ $user->menus->contains($menu->id) ? 'checked' : '' }}
                                                   class="focus:ring-gray-900 h-4 w-4 text-gray-900 border-gray-300 rounded">
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('menus')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
