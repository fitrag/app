<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                {{ __('Visitor Analytics') }}
            </h2>
            
            <!-- Period Filter -->
            <div class="flex items-center space-x-2">
                <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex items-center space-x-2">
                    <select name="period" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="7days" {{ $period == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30days" {{ $period == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="thismonth" {{ $period == 'thismonth' ? 'selected' : '' }}>This Month</option>
                        <option value="all" {{ $period == 'all' ? 'selected' : '' }}>All Time</option>
                    </select>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="h-full bg-gray-50">
        <div class="h-full overflow-y-auto">
            <div class="p-8 max-w-7xl mx-auto space-y-6">
                
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Visitors -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Visitors</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalVisitors) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Unique Visitors -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Unique Visitors</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($uniqueVisitors) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Visitors -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Mobile Visitors</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">
                                    {{ number_format($deviceStats->where('device_type', 'mobile')->first()->count ?? 0) }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Visitors -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Desktop Visitors</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">
                                    {{ number_format($deviceStats->where('device_type', 'desktop')->first()->count ?? 0) }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daily Visits Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Visitor Trends (Last 7 Days)</h3>
                    <div class="h-80">
                        <canvas id="dailyVisitsChart"></canvas>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Device Distribution -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Device Distribution</h3>
                        <div class="space-y-3">
                            @foreach($deviceStats as $device)
                                @php
                                    $percentage = $totalVisitors > 0 ? ($device->count / $totalVisitors) * 100 : 0;
                                    $colors = [
                                        'mobile' => 'bg-purple-500',
                                        'desktop' => 'bg-orange-500',
                                        'tablet' => 'bg-blue-500',
                                    ];
                                    $color = $colors[$device->device_type] ?? 'bg-gray-500';
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $device->device_type ?? 'Unknown' }}</span>
                                        <span class="text-sm text-gray-600">{{ number_format($device->count) }} ({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Browser Distribution -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Browsers</h3>
                        <div class="space-y-3">
                            @foreach($browserStats->take(5) as $browser)
                                @php
                                    $percentage = $totalVisitors > 0 ? ($browser->count / $totalVisitors) * 100 : 0;
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">{{ $browser->browser ?? 'Unknown' }}</span>
                                        <span class="text-sm text-gray-600">{{ number_format($browser->count) }} ({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Platform & Popular Pages -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Operating Systems -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Operating Systems</h3>
                        <div class="space-y-3">
                            @foreach($platformStats->take(5) as $platform)
                                @php
                                    $percentage = $totalVisitors > 0 ? ($platform->count / $totalVisitors) * 100 : 0;
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">{{ $platform->platform ?? 'Unknown' }}</span>
                                        <span class="text-sm text-gray-600">{{ number_format($platform->count) }} ({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Popular Pages -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Popular Pages</h3>
                        <div class="space-y-3">
                            @foreach($popularPages->take(5) as $page)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                    <div class="flex-1 min-w-0 mr-4">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $page->page_url }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ number_format($page->visits) }} visits
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Location Statistics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Top Countries -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Countries</h3>
                        <div class="space-y-3">
                            @forelse($countryStats->take(10) as $country)
                                @php
                                    $percentage = $totalVisitors > 0 ? ($country->count / $totalVisitors) * 100 : 0;
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">{{ $country->country ?? 'Unknown' }}</span>
                                        <span class="text-sm text-gray-600">{{ number_format($country->count) }} ({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">No country data available</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Top Cities -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Cities</h3>
                        <div class="space-y-3">
                            @forelse($cityStats->take(10) as $city)
                                @php
                                    $percentage = $totalVisitors > 0 ? ($city->count / $totalVisitors) * 100 : 0;
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">{{ $city->city ?? 'Unknown' }} <span class="text-gray-400 text-xs">({{ $city->country }})</span></span>
                                        <span class="text-sm text-gray-600">{{ number_format($city->count) }} ({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-teal-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">No city data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Bot Statistics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Human vs Bot Chart -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Traffic Type</h3>
                        <div class="h-64">
                            <canvas id="trafficTypeChart"></canvas>
                        </div>
                    </div>

                    <!-- Top Bots -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Crawl Bots</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bot Name</th>
                                        <th class="px-4 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Visits</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($botStats as $bot)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $bot->bot_name ?: 'Unknown Bot' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($bot->count) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-4 py-3 text-sm text-gray-500 text-center">No bot traffic detected</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Visitors Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" 
                     x-data="{
                         visitors: {{ json_encode($recentVisitors->map(function($v) {
                             return [
                                 'id' => $v->id,
                                 'ip_address' => $v->ip_address,
                                 'time' => $v->created_at->diffForHumans(),
                                 'country' => $v->country,
                                 'city' => $v->city ?? '-',
                                 'device_type' => $v->device_type ?? 'Unknown',
                                 'browser' => $v->browser ?? 'Unknown',
                                 'platform' => $v->platform ?? 'Unknown',
                                 'page_url' => $v->page_url,
                             ];
                         })) }},
                         newVisitorIds: [],
                         async fetchVisitors() {
                             try {
                                 const response = await fetch('{{ route('admin.analytics.recent-visitors') }}');
                                 const data = await response.json();
                                 
                                 // Track new visitor IDs for animation
                                 const currentIds = this.visitors.map(v => v.id);
                                 this.newVisitorIds = data.visitors
                                     .filter(v => !currentIds.includes(v.id))
                                     .map(v => v.id);
                                 
                                 this.visitors = data.visitors;
                                 
                                 // Clear animation after 2 seconds
                                 setTimeout(() => {
                                     this.newVisitorIds = [];
                                 }, 2000);
                             } catch (error) {
                                 console.error('Failed to fetch visitors:', error);
                             }
                         },
                         init() {
                             // Poll every 5 seconds
                             setInterval(() => {
                                 this.fetchVisitors();
                             }, 5000);
                         }
                     }">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Visitors</h3>
                        <span class="text-xs text-gray-500">
                            <svg class="inline w-3 h-3 animate-pulse text-green-500" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Live
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Browser</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OS</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="visitor in visitors" :key="visitor.id">
                                    <tr class="hover:bg-gray-50 transition-colors"
                                        :class="{ 'bg-green-50 animate-pulse': newVisitorIds.includes(visitor.id) }">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="visitor.time"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600" x-text="visitor.ip_address"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <span x-show="visitor.country" 
                                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                                                  x-text="visitor.country"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="visitor.city"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                                  :class="{
                                                      'bg-purple-100 text-purple-800': visitor.device_type === 'mobile',
                                                      'bg-orange-100 text-orange-800': visitor.device_type === 'desktop',
                                                      'bg-blue-100 text-blue-800': visitor.device_type === 'tablet'
                                                  }"
                                                  x-text="visitor.device_type"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="visitor.browser"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="visitor.platform"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" x-text="visitor.page_url"></td>
                                    </tr>
                                </template>
                                <template x-if="visitors.length === 0">
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            <p class="mt-4 text-sm">No visitor data available yet</p>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dailyVisitsChart').getContext('2d');
            
            // Prepare data
            const dates = @json($dailyVisits->pluck('date'));
            const totalVisits = @json($dailyVisits->pluck('total_visits'));
            const uniqueVisits = @json($dailyVisits->pluck('unique_visits'));
            
            // Format dates to be more readable (e.g., "Mon, 22 Nov")
            const formattedDates = dates.map(date => {
                const d = new Date(date);
                return d.toLocaleDateString('en-US', { weekday: 'short', day: 'numeric', month: 'short' });
            });

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: formattedDates,
                    datasets: [
                        {
                            label: 'Total Visitors',
                            data: totalVisits,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)', // Blue-500 with opacity
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1,
                            borderRadius: 4,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8,
                            stack: 'combined'
                        },
                        {
                            label: 'Unique Visitors',
                            data: uniqueVisits,
                            backgroundColor: 'rgba(16, 185, 129, 0.5)', // Green-500 with opacity
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1,
                            borderRadius: 4,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8,
                            stack: 'combined'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: true,
                            callbacks: {
                                labelColor: function(context) {
                                    return {
                                        borderColor: context.dataset.borderColor,
                                        backgroundColor: context.dataset.backgroundColor
                                    };
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 2],
                                drawBorder: false,
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        x: {
                            stacked: true,
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });

            // Traffic Type Chart
            const trafficCtx = document.getElementById('trafficTypeChart').getContext('2d');
            new Chart(trafficCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Humans', 'Bots'],
                    datasets: [{
                        data: [{{ $totalHumans }}, {{ $totalBots }}],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)', // Blue
                            'rgba(107, 114, 128, 0.8)'  // Gray
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(107, 114, 128)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
