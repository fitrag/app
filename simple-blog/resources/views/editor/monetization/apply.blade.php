<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            Apply for Monetization
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Requirements Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Monetization Requirements</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Minimum Posts</p>
                                <p class="text-xs text-gray-600">At least {{ $requirements['min_posts'] }} published posts</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-green-600">
                            {{ $totalPosts }} / {{ $requirements['min_posts'] }} ✓
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Minimum Views</p>
                                <p class="text-xs text-gray-600">At least {{ number_format($requirements['min_views']) }} total views</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-green-600">
                            {{ number_format($totalViews) }} / {{ number_format($requirements['min_views']) }} ✓
                        </span>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <span class="font-medium">Great!</span> You meet all the requirements. Please complete the application form below.
                    </p>
                </div>
            </div>

            <!-- Application Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Form</h3>

                <form action="{{ route('editor.monetization.submit') }}" method="POST">
                    @csrf

                    <!-- Application Reason -->
                    <div class="mb-6">
                        <label for="application_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Why do you want to enable monetization? <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="application_reason" 
                            name="application_reason" 
                            rows="6" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('application_reason') border-red-500 @enderror"
                            placeholder="Tell us why you want to monetize your content. Minimum 50 characters..."
                            required>{{ old('application_reason') }}</textarea>
                        
                        <p class="mt-1 text-sm text-gray-500">
                            Minimum 50 characters, maximum 1000 characters
                        </p>
                        
                        @error('application_reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input 
                                    id="agree_terms" 
                                    name="agree_terms" 
                                    type="checkbox" 
                                    value="1"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 @error('agree_terms') border-red-500 @enderror"
                                    required
                                    {{ old('agree_terms') ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3">
                                <label for="agree_terms" class="text-sm font-medium text-gray-700">
                                    I agree to the terms and conditions <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">
                                    By checking this box, you agree to follow our monetization policies and content guidelines.
                                </p>
                            </div>
                        </div>
                        
                        @error('agree_terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">What happens next?</h4>
                        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Your application will be reviewed by our admin team</li>
                            <li>You will be notified once a decision is made</li>
                            <li>If approved, monetization will be enabled automatically</li>
                            <li>If rejected, you can reapply after addressing the feedback</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('editor.monetization.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
