<header
    x-data="{ scrolled: false }"
    x-init="
        const set = () => {
            const container = $el.closest('[data-scroll-container]');
            const y = container ? container.scrollTop : window.scrollY;
            scrolled = y > 10;
        };
        set();

        const container = $el.closest('[data-scroll-container]');
        if (container) {
            container.addEventListener('scroll', set, { passive: true });
            $cleanup(() => container.removeEventListener('scroll', set));
        } else {
            window.addEventListener('scroll', set, { passive: true });
            $cleanup(() => window.removeEventListener('scroll', set));
        }
    "
    :class="scrolled
        ? 'bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-gray-200/50 dark:border-gray-700/50 shadow-sm'
        : 'bg-white/0 dark:bg-gray-800/0 backdrop-blur-0 border-transparent shadow-none'"
    class="flex items-center justify-between px-6 py-4 border-b sticky top-0 z-40 transition-all duration-300"
>
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400 focus:outline-none lg:hidden mr-4 transition-colors duration-300 hover:text-gray-700 dark:hover:text-gray-200">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        
        <!-- Search or Breadcrumb (Optional) -->
        <div class="hidden md:block text-sm text-gray-500 dark:text-gray-400 transition-colors duration-300">
            <span class="font-medium text-gray-800 dark:text-gray-200 transition-colors duration-300">{{ $title ?? 'Dashboard' }}</span>
        </div>
    </div>

    <div class="flex items-center space-x-4">
        <!-- Dark Mode Toggle -->
        <button @click="toggleDarkMode()" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
            <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
            <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </button>
        
        <!-- Notification Bell -->
        @php
            use App\Helpers\NotificationHelper;
            $notifications = auth()->check() ? NotificationHelper::getDummyNotifications(auth()->user()) : [];
            $unreadCount = auth()->check() ? NotificationHelper::getUnreadCount(auth()->user()) : 0;
        @endphp
        <div class="relative" x-data="{ notifOpen: false }">
            <button @click="notifOpen = !notifOpen" class="relative flex items-center text-gray-500 dark:text-gray-400 hover:text-primary focus:outline-none transition-colors p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                @if($unreadCount > 0)
                <span class="absolute top-0 right-0 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold leading-none text-white bg-gradient-to-r from-red-500 to-red-600 rounded-full ring-2 ring-white shadow-sm animate-pulse">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </button>

            <!-- Notification Dropdown -->
            <div x-show="notifOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                 @click.away="notifOpen = false" 
                 class="absolute right-0 w-96 mt-2 origin-top-right bg-white/95 dark:bg-gray-800/95 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden z-50 border border-gray-200 dark:border-gray-700 transition-all duration-300" 
                 style="display: none; max-height: 500px; overflow-y: auto;">
                <!-- Header -->
                <div class="sticky top-0 bg-gradient-to-r from-primary to-blue-600 px-5 py-4 border-b border-primary/20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="p-2 rounded-lg bg-white/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Notifications</h3>
                                @if($unreadCount > 0)
                                <p class="text-xs text-blue-100">{{ $unreadCount }} unread</p>
                                @endif
                            </div>
                        </div>
                        @if($unreadCount > 0)
                        <button class="text-xs text-white/80 hover:text-white font-semibold px-3 py-1 rounded-lg hover:bg-white/10 transition-colors">
                            Mark all read
                        </button>
                        @endif
                    </div>
                </div>
                
                <!-- Notifications List -->
                <div class="py-2">
                    @forelse($notifications as $notification)
                    <a href="{{ $notification['url'] }}" 
                       class="group relative block px-5 py-4 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-300 border-b border-gray-100/50 dark:border-gray-700/50 {{ !$notification['read'] ? 'bg-blue-50/30 dark:bg-blue-900/20' : '' }}">
                        <div class="flex items-start gap-3">
                            <!-- Icon -->
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="p-2 rounded-lg 
                                    @if($notification['color'] === 'blue') bg-blue-100 text-blue-600
                                    @elseif($notification['color'] === 'green') bg-green-100 text-green-600
                                    @elseif($notification['color'] === 'red') bg-red-100 text-red-600
                                    @elseif($notification['color'] === 'purple') bg-purple-100 text-purple-600
                                    @else bg-gray-100 text-gray-600
                                    @endif">
                                    @if($notification['icon'] === 'task')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    @elseif($notification['icon'] === 'calendar')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    @elseif($notification['icon'] === 'check')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @elseif($notification['icon'] === 'alert')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-primary transition-colors duration-300">
                                            {{ $notification['title'] }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 leading-relaxed transition-colors duration-300">
                                            {{ $notification['message'] }}
                                        </p>
                                    </div>
                                    @if(!$notification['read'])
                                    <div class="flex-shrink-0 w-2 h-2 bg-primary rounded-full mt-1.5 transition-colors duration-300"></div>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 flex items-center gap-1 transition-colors duration-300">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $notification['time'] }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <p class="text-sm font-medium">No notifications</p>
                            <p class="text-xs mt-1">You're all caught up!</p>
                        </div>
                    </div>
                    @endforelse
                </div>
                
                <!-- Footer -->
                @if(count($notifications) > 0)
                <div class="sticky bottom-0 bg-gray-50/80 dark:bg-gray-700/80 backdrop-blur-sm border-t border-gray-200 dark:border-gray-700 px-5 py-3 transition-all duration-300">
                    <a href="/notifications" class="block text-center text-sm font-semibold text-primary dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-300">
                        View All Notifications
                    </a>
                </div>
                @endif
            </div>
        </div>

        <div class="flex items-center relative cursor-pointer" x-data="{ dropdownOpen: false }" @click="dropdownOpen = !dropdownOpen">
             <div class="text-right mr-3 hidden md:block">
                @if(auth()->check())
                <div class="text-sm font-bold text-gray-800 dark:text-white transition-colors duration-300">{{ auth()->user()->name }}</div>
                <div class="text-xs text-primary dark:text-blue-400 font-semibold uppercase tracking-wide bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-full inline-block transition-all duration-300">{{ auth()->user()->role }}</div>
                @endif
             </div>
             <button class="relative block w-10 h-10 overflow-hidden rounded-full border-2 border-white dark:border-gray-700 shadow-md focus:outline-none hover:ring-2 hover:ring-primary hover:ring-offset-2 transition-all duration-300">
                <img class="object-cover w-full h-full" src="https://ui-avatars.com/api/?name={{ auth()->check() ? urlencode(auth()->user()->name) : 'User' }}&background=0652FD&color=fff" alt="Avatar">
             </button>

            <!-- Dropdown -->
            <div x-show="dropdownOpen" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 @click.away="dropdownOpen = false" 
                 class="absolute right-0 w-48 mt-2 top-12 bg-white dark:bg-gray-800 rounded-md shadow-xl z-50 py-1 border border-gray-100 dark:border-gray-700 transition-all duration-300" style="display: none;">
                
                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 md:hidden transition-colors duration-300">
                    @if(auth()->check())
                    <div class="text-sm font-bold text-gray-800 dark:text-white transition-colors duration-300">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 transition-colors duration-300">{{ auth()->user()->role }}</div>
                    @endif
                </div>

                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-primary transition-all duration-300 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Edit Profile
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf 
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-300 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
