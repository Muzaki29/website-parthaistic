<div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black/60 backdrop-blur-sm lg:hidden"></div>

<aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 flex w-72 transform flex-col overflow-y-auto border-r border-gray-200/50 bg-gradient-to-b from-white to-gray-50/50 shadow-2xl transition-all duration-300 dark:border-gray-700/50 dark:from-gray-800 dark:to-gray-900/50 lg:static lg:inset-0 lg:translate-x-0">
    <div class="flex items-center justify-between p-6 border-b border-gray-200/50 dark:border-gray-700/50 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm transition-colors duration-300">
        <div class="flex flex-col items-start space-y-2 flex-1">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center">
                    <img src="{{ asset('img/logo.png') }}" alt="Parthaistic" class="h-12 w-12 object-contain transition-transform duration-300 hover:scale-105">
                </div>
                <div>
                    <span class="text-base font-bold text-gray-900 dark:text-white tracking-tight block transition-colors duration-300">PARTHAISTIC</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium transition-colors duration-300">Digital Agency</span>
                </div>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="ui-btn-secondary p-2 lg:hidden" aria-label="Close sidebar">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="mt-4 flex-1 px-3">
        @php
            $baseLinkClass = 'group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300';
            $activeLinkClass = 'bg-gradient-to-r from-indigo-600 to-blue-600 text-white shadow-lg shadow-indigo-500/25';
            $inactiveLinkClass = 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white';
            $baseIconClass = 'p-2 rounded-lg transition-all duration-300';
            $activeIconClass = 'bg-white/25 text-white';
            $inactiveIconClass = 'bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-200 dark:group-hover:bg-gray-600';
            $baseSvgClass = 'w-5 h-5 transition-colors duration-300';
            $activeSvgClass = 'text-white';
            $inactiveSvgClass = 'text-gray-600 dark:text-gray-300';
        @endphp
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}" class="{{ $baseLinkClass }} {{ request()->routeIs('dashboard') ? $activeLinkClass : $inactiveLinkClass }}">
                <div class="{{ $baseIconClass }} {{ request()->routeIs('dashboard') ? $activeIconClass : $inactiveIconClass }}">
                    <svg class="{{ $baseSvgClass }} {{ request()->routeIs('dashboard') ? $activeSvgClass : $inactiveSvgClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>

            <a href="{{ route('team.overview') }}" class="{{ $baseLinkClass }} {{ request()->routeIs('team.overview') ? $activeLinkClass : $inactiveLinkClass }}">
                <div class="{{ $baseIconClass }} {{ request()->routeIs('team.overview') ? $activeIconClass : $inactiveIconClass }}">
                    <svg class="{{ $baseSvgClass }} {{ request()->routeIs('team.overview') ? $activeSvgClass : $inactiveSvgClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 11a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-sm">Team Overview</span>
            </a>
            
            @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('employees') }}" class="{{ $baseLinkClass }} {{ request()->routeIs('employees') ? $activeLinkClass : $inactiveLinkClass }}">
                <div class="{{ $baseIconClass }} {{ request()->routeIs('employees') ? $activeIconClass : $inactiveIconClass }}">
                    <svg class="{{ $baseSvgClass }} {{ request()->routeIs('employees') ? $activeSvgClass : $inactiveSvgClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-sm">Tim & Karyawan</span>
            </a>

            <a href="{{ route('admin.trello.mapping') }}" class="{{ $baseLinkClass }} {{ request()->routeIs('admin.trello.mapping') ? $activeLinkClass : $inactiveLinkClass }}">
                <div class="{{ $baseIconClass }} {{ request()->routeIs('admin.trello.mapping') ? $activeIconClass : $inactiveIconClass }}">
                    <svg class="{{ $baseSvgClass }} {{ request()->routeIs('admin.trello.mapping') ? $activeSvgClass : $inactiveSvgClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <span class="font-semibold text-sm">Pemetaan Trello</span>
            </a>
            @endif

            <a href="{{ route('reports') }}" class="{{ $baseLinkClass }} {{ request()->routeIs('reports') ? $activeLinkClass : $inactiveLinkClass }}">
                <div class="{{ $baseIconClass }} {{ request()->routeIs('reports') ? $activeIconClass : $inactiveIconClass }}">
                    <svg class="{{ $baseSvgClass }} {{ request()->routeIs('reports') ? $activeSvgClass : $inactiveSvgClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="font-semibold text-sm">Laporan</span>
            </a>

            <a href="{{ route('workflow.board') }}" class="{{ $baseLinkClass }} {{ request()->routeIs('workflow.board') ? $activeLinkClass : $inactiveLinkClass }}">
                <div class="{{ $baseIconClass }} {{ request()->routeIs('workflow.board') ? $activeIconClass : $inactiveIconClass }}">
                    <svg class="{{ $baseSvgClass }} {{ request()->routeIs('workflow.board') ? $activeSvgClass : $inactiveSvgClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2h-2M9 9h6M9 13h6M9 21h6"></path>
                    </svg>
                </div>
                <span class="font-semibold text-sm">Workflow Board</span>
            </a>
            
            <a href="{{ route('notifications') }}" class="{{ $baseLinkClass }} {{ request()->routeIs('notifications') ? $activeLinkClass : $inactiveLinkClass }}">
                <div class="{{ $baseIconClass }} {{ request()->routeIs('notifications') ? $activeIconClass : $inactiveIconClass }}">
                    <svg class="{{ $baseSvgClass }} {{ request()->routeIs('notifications') ? $activeSvgClass : $inactiveSvgClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <span class="font-semibold text-sm">Notifications</span>
            </a>
        </div>
    </nav>

    <div class="p-4 border-t border-gray-200/50 dark:border-gray-700/50 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm transition-colors duration-300">
        <div class="bg-gradient-to-br from-primary/10 dark:from-primary/20 to-blue-50/50 dark:to-blue-900/20 rounded-2xl p-5 border border-primary/20 dark:border-primary/30 shadow-sm transition-all duration-300">
            <div class="flex items-start gap-3 mb-3">
                <div class="p-2 rounded-lg bg-primary/20 dark:bg-primary/30 transition-colors duration-300">
                    <svg class="w-5 h-5 text-primary dark:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1 transition-colors duration-300">Butuh Bantuan?</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed transition-colors duration-300">Hubungi tim support kami untuk bantuan lebih lanjut.</p>
                </div>
            </div>
            <button type="button" @click="showContactModal = true" class="ui-btn-primary w-full py-2.5 px-4 text-xs font-bold">
                Contact Support
            </button>
        </div>
    </div>
</aside>
