<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2 transition-colors duration-300">Notifications</h1>
            <p class="text-gray-600 dark:text-gray-400 transition-colors duration-300">Stay updated with your latest activities and updates</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-300">
                Mark all as read
            </button>
            <button class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-primary to-blue-600 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all shadow-lg shadow-primary/20 duration-300">
                Clear all
            </button>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-3">
        @forelse($notifications as $notification)
        <div class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 overflow-hidden {{ !$notification['read'] ? 'ring-2 ring-primary/20 bg-blue-50/30 dark:bg-blue-900/20' : '' }}">
            <a href="{{ $notification['url'] }}" class="block p-6">
                <div class="flex items-start gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <div class="p-3 rounded-xl transition-all duration-300
                            @if($notification['color'] === 'blue') bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400
                            @elseif($notification['color'] === 'green') bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400
                            @elseif($notification['color'] === 'red') bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400
                            @elseif($notification['color'] === 'purple') bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400
                            @else bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300
                            @endif">
                            @if($notification['icon'] === 'task')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            @elseif($notification['icon'] === 'calendar')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @elseif($notification['icon'] === 'check')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($notification['icon'] === 'alert')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors duration-300">
                                        {{ $notification['title'] }}
                                    </h3>
                                    @if(!$notification['read'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-primary text-white transition-colors duration-300">
                                        New
                                    </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-2 transition-colors duration-300">
                                    {{ $notification['message'] }}
                                </p>
                                <div class="flex items-center gap-4 text-xs text-gray-400 dark:text-gray-500 transition-colors duration-300">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $notification['time'] }}
                                    </span>
                                    <span class="capitalize">{{ $notification['type'] }}</span>
                                </div>
                            </div>
                            
                            <!-- Action Button -->
                            <div class="flex-shrink-0">
                                <button class="p-2 rounded-lg text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-12 text-center transition-all duration-300">
            <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                <svg class="w-24 h-24 mb-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-600 dark:text-gray-400 mb-2 transition-colors duration-300">No notifications</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 transition-colors duration-300">You're all caught up! Check back later for updates.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

