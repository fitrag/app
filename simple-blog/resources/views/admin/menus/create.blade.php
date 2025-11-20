<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Create Menu Item') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                <form action="{{ route('admin.menus.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="mb-6">
                        <label for="label" class="block text-sm font-medium text-gray-700 mb-2">Menu Label *</label>
                        <input type="text" name="label" id="label" value="{{ old('label') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        @error('label')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">URL *</label>
                        <input type="text" name="url" id="url" value="{{ old('url') }}" required placeholder="/"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Use relative URLs (e.g., /, /about) or absolute URLs (e.g., https://example.com)</p>
                        @error('url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                        <select name="location" id="location" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                            <option value="public" {{ old('location') == 'public' ? 'selected' : '' }}>Public (Frontend)</option>
                            <option value="admin" {{ old('location') == 'admin' ? 'selected' : '' }}>Admin (Backend)</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Where should this menu appear?</p>
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Menu (Optional)</label>
                        <select name="parent_id" id="parent_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                            <option value="">-- Top Level Menu --</option>
                            @foreach($parentMenus as $parentMenu)
                                <option value="{{ $parentMenu->id }}" {{ old('parent_id') == $parentMenu->id ? 'selected' : '' }}>
                                    {{ $parentMenu->label }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Select a parent menu to create a submenu (dropdown)</p>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Order *</label>
                        <input type="number" name="order" id="order" value="{{ old('order', 0) }}" required min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            <span class="ml-2 text-sm text-gray-700">Active (visible in navigation)</span>
                        </label>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="open_new_tab" value="1" {{ old('open_new_tab') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            <span class="ml-2 text-sm text-gray-700">Open in new tab</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.menus.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition">
                            Create Menu Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
