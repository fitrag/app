<x-app-layout>
    <x-slot name="header">
        <div class="px-0">
            <h1 class="text-3xl font-serif font-bold text-gray-900 tracking-tight">Monetization</h1>
            <p class="mt-1 text-base text-gray-600">Earn from your creative work</p>
        </div>
    </x-slot>

    <div class="py-8 px-8">
        
        <!-- Status Card -->
        <div class="mb-8">
            @if($monetizationEnabled)
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 border border-green-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold text-gray-900">Monetization Active</h3>
                            <p class="mt-1 text-gray-700">Your account is earning coins from posts and views</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-br from-amber-50 to-yellow-50 rounded-2xl p-8 border border-amber-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold text-gray-900">Monetization Inactive</h3>
                            <p class="mt-1 text-gray-700">Complete the requirements below to start earning</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Stats Overview -->
        <div class="mb-8">
            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6">Your Earnings</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Coins -->
                <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Coins</span>
                        <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalCoins, 2) }}</p>
                </div>

                <!-- Total Posts -->
                <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Posts</span>
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalPosts) }}</p>
                </div>

                <!-- Total Views -->
                <div class="bg-white rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Views</span>
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalViews) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Requirements Section OR Earnings Breakdown (2/3 width) -->
            <div class="lg:col-span-2">
                @if($monetizationEnabled)
                    <!-- Earnings Breakdown (when monetization is active) -->
                    <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6">Earnings Breakdown</h2>
                    
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden mb-6">
                        <div class="p-8">
                            <div class="space-y-6">
                                <!-- From Creating Posts -->
                                <div>
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-base font-semibold text-gray-900">From Creating Posts</p>
                                                <p class="text-sm text-gray-600">Coins earned from publishing content</p>
                                            </div>
                                        </div>
                                        <span class="text-2xl font-bold text-blue-600">{{ number_format($earningsFromPosts, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        @php
                                            $total = $earningsFromPosts + $earningsFromViews;
                                            $postPercentage = $total > 0 ? ($earningsFromPosts / $total) * 100 : 0;
                                        @endphp
                                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ $postPercentage }}%"></div>
                                    </div>
                                </div>

                                <!-- From View Milestones -->
                                <div>
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-base font-semibold text-gray-900">From View Milestones</p>
                                                <p class="text-sm text-gray-600">Coins earned from reader engagement</p>
                                            </div>
                                        </div>
                                        <span class="text-2xl font-bold text-green-600">{{ number_format($earningsFromViews, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        @php
                                            $viewPercentage = $total > 0 ? ($earningsFromViews / $total) * 100 : 0;
                                        @endphp
                                        <div class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $viewPercentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Summary -->
                        <div class="px-8 py-6 bg-gradient-to-r from-yellow-50 to-amber-50 border-t border-yellow-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Total Lifetime Earnings</p>
                                    <p class="text-xs text-gray-600 mt-0.5">All coins earned since activation</p>
                                </div>
                                <p class="text-3xl font-bold text-yellow-900">{{ number_format($total, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Tips -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base font-semibold text-gray-900">Tips to Maximize Earnings</h3>
                                <ul class="mt-2 space-y-1 text-sm text-gray-700">
                                    <li class="flex items-start">
                                        <span class="text-blue-600 mr-2">•</span>
                                        <span>Publish consistently to earn more post creation bonuses</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-blue-600 mr-2">•</span>
                                        <span>Create engaging content to reach view milestones faster</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-blue-600 mr-2">•</span>
                                        <span>Share your posts to increase visibility and views</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Monetization Requirements (when monetization is inactive) -->
                    <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6">Monetization Requirements</h2>
                    
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="p-8">
                            <div class="space-y-6">
                                <!-- Posts Requirement -->
                                <div class="flex items-center justify-between pb-6 border-b border-gray-100">
                                    <div class="flex items-center flex-1">
                                        <div class="flex-shrink-0">
                                            @if($totalPosts >= $requirements['min_posts'])
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <p class="text-base font-semibold text-gray-900">Minimum Posts</p>
                                            <p class="text-sm text-gray-600 mt-1">Publish at least {{ $requirements['min_posts'] }} quality posts</p>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <span class="text-2xl font-bold {{ $totalPosts >= $requirements['min_posts'] ? 'text-green-600' : 'text-gray-400' }}">
                                            {{ $totalPosts }}<span class="text-base font-normal text-gray-400">/{{ $requirements['min_posts'] }}</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Views Requirement -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1">
                                        <div class="flex-shrink-0">
                                            @if($totalViews >= $requirements['min_views'])
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <p class="text-base font-semibold text-gray-900">Minimum Views</p>
                                            <p class="text-sm text-gray-600 mt-1">Reach {{ number_format($requirements['min_views']) }} total views across all posts</p>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <span class="text-2xl font-bold {{ $totalViews >= $requirements['min_views'] ? 'text-green-600' : 'text-gray-400' }}">
                                            {{ number_format($totalViews) }}<span class="text-base font-normal text-gray-400">/{{ number_format($requirements['min_views']) }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Application Status / Action -->
                        @if($latestApplication)
                            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                                @if($latestApplication->isPending())
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900">Application Under Review</p>
                                            <p class="text-sm text-gray-600 mt-0.5">Our team is reviewing your application. You'll be notified soon.</p>
                                        </div>
                                    </div>
                                @elseif($latestApplication->isApproved())
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900">Application Approved</p>
                                            <p class="text-sm text-gray-600 mt-0.5">Approved on {{ $latestApplication->reviewed_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900">Application Not Approved</p>
                                            @if($latestApplication->admin_notes)
                                                <p class="text-sm text-gray-600 mt-1">{{ $latestApplication->admin_notes }}</p>
                                            @endif
                                            @if($meetsRequirements)
                                                <a href="{{ route('editor.monetization.apply') }}" class="inline-flex items-center mt-3 text-sm font-medium text-blue-600 hover:text-blue-700">
                                                    Apply again
                                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @elseif($meetsRequirements && !$monetizationEnabled)
                            <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-t border-blue-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">You're eligible for monetization!</p>
                                        <p class="text-sm text-gray-600 mt-0.5">Submit your application to start earning from your content</p>
                                    </div>
                                    <a href="{{ route('editor.monetization.apply') }}" class="ml-4 inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition-colors duration-200 whitespace-nowrap">
                                        Apply Now
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @elseif(!$meetsRequirements)
                            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                                <p class="text-sm text-gray-600">Keep creating quality content to meet the requirements and unlock monetization.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Recent Transactions Sidebar (1/3 width) -->
            <div class="lg:col-span-1">
                <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6">Recent Activity</h2>
                
                @if(!$recentTransactions->isEmpty())
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="divide-y divide-gray-100">
                            @foreach($recentTransactions->take(8) as $transaction)
                                <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start flex-1 min-w-0">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 {{ $transaction->type === 'create_post' ? 'bg-blue-50' : 'bg-green-50' }} rounded-lg flex items-center justify-center">
                                                    @if($transaction->type === 'create_post')
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1 min-w-0">
                                                <p class="text-xs font-medium text-gray-900 truncate">{{ Str::limit($transaction->description, 40) }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $transaction->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <div class="ml-2 flex-shrink-0">
                                            <span class="text-sm font-semibold text-green-600">+{{ number_format($transaction->amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-gray-100 p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No transactions yet</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>

