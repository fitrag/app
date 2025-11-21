<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between px-0">
            <div>
                <h1 class="text-3xl font-serif font-bold text-gray-900 tracking-tight">Application Review</h1>
                <p class="mt-1 text-base text-gray-600">Review and process monetization application</p>
            </div>
            <a href="{{ route('admin.monetization-applications.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Applications
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-8">
        
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Application Status Card -->
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-xl font-serif font-bold text-gray-900">Application Status</h2>
                                <p class="text-sm text-gray-500 mt-1">Submitted {{ $application->created_at->diffForHumans() }}</p>
                            </div>
                            @if($application->isPending())
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse mr-2"></div>
                                    Pending Review
                                </span>
                            @elseif($application->isApproved())
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Approved
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Rejected
                                </span>
                            @endif
                        </div>

                        <!-- Stats at Application Time -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-900">Posts</p>
                                        <p class="text-xs text-blue-700 mt-0.5">At submission</p>
                                    </div>
                                    <p class="text-2xl font-bold text-blue-600">{{ $application->total_posts_at_application }}</p>
                                </div>
                            </div>
                            <div class="p-4 bg-green-50 rounded-xl border border-green-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-green-900">Views</p>
                                        <p class="text-xs text-green-700 mt-0.5">At submission</p>
                                    </div>
                                    <p class="text-2xl font-bold text-green-600">{{ number_format($application->total_views_at_application) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Reason -->
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-xl font-serif font-bold text-gray-900 mb-4">Application Reason</h2>
                        <div class="prose prose-sm max-w-none">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $application->application_reason }}</p>
                        </div>
                    </div>
                </div>

                <!-- Review Information (if reviewed) -->
                @if($application->reviewed_at)
                    <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl border border-gray-200 overflow-hidden">
                        <div class="p-8">
                            <h2 class="text-xl font-serif font-bold text-gray-900 mb-6">Review Details</h2>
                            
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-32">
                                        <p class="text-sm font-medium text-gray-500">Reviewed By</p>
                                    </div>
                                    <p class="text-sm text-gray-900">{{ $application->reviewer->name ?? 'N/A' }}</p>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-32">
                                        <p class="text-sm font-medium text-gray-500">Reviewed At</p>
                                    </div>
                                    <p class="text-sm text-gray-900">{{ $application->reviewed_at->format('M d, Y \a\t H:i') }}</p>
                                </div>
                                
                                @if($application->admin_notes)
                                    <div class="flex items-start pt-4 border-t border-gray-200">
                                        <div class="flex-shrink-0 w-32">
                                            <p class="text-sm font-medium text-gray-500">Admin Notes</p>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $application->admin_notes }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <!-- Sidebar (1/3 width) -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Applicant Information -->
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-serif font-bold text-gray-900 mb-4">Applicant</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Name</p>
                                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $application->user->name }}</p>
                            </div>
                            
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $application->user->email }}</p>
                            </div>
                            
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Role</p>
                                <p class="mt-1 text-sm text-gray-900 capitalize">{{ $application->user->role }}</p>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-100">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Current Status</p>
                                <p class="mt-1 text-sm">
                                    @if($application->user->monetization_enabled)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Monetization Enabled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Monetization Disabled
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (only for pending applications) -->
                @if($application->isPending())
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-serif font-bold text-gray-900 mb-4">Review Actions</h3>
                            
                            <!-- Approve Form -->
                            <form action="{{ route('admin.monetization-applications.approve', $application->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this application?')" class="mb-4">
                                @csrf
                                
                                <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Admin Notes <span class="text-gray-400">(Optional)</span>
                                </label>
                                <textarea 
                                    id="approve_notes" 
                                    name="admin_notes" 
                                    rows="3" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                                    placeholder="Add any notes for the applicant..."></textarea>
                                
                                <button type="submit" class="mt-3 w-full px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-medium text-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Approve Application
                                </button>
                            </form>

                            <!-- Reject Form -->
                            <form action="{{ route('admin.monetization-applications.reject', $application->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this application?')">
                                @csrf
                                
                                <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Rejection Reason <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    id="reject_notes" 
                                    name="admin_notes" 
                                    rows="3" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm @error('admin_notes') border-red-500 @enderror"
                                    placeholder="Explain why this application is being rejected..."
                                    required></textarea>
                                
                                @error('admin_notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                <button type="submit" class="mt-3 w-full px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition font-medium text-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Reject Application
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>
</x-app-layout>
