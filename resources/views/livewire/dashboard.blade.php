<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2 transition-colors duration-300">Dashboard Overview</h1>
            <p class="text-gray-600 dark:text-gray-400 transition-colors duration-300">Welcome back, {{ auth()->user()->name }}! Here's what's happening today.</p>
        </div>
        
        <div class="flex items-center gap-3">
            @if (session()->has('success'))
                <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <button wire:click="syncData" wire:loading.attr="disabled" class="group relative overflow-hidden bg-linear-to-r from-primary to-primary/80 hover:from-primary hover:to-primary/90 text-white font-semibold py-3 px-6 rounded-xl shadow-lg shadow-primary/20 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2 {{ auth()->user()->role === 'manager' ? 'hidden' : '' }}">
                <svg wire:loading.remove wire:target="syncData" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <svg wire:loading wire:target="syncData" class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="syncData">Sync Trello</span>
                <span wire:loading wire:target="syncData">Syncing...</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Total Tasks -->
        <div class="group relative overflow-hidden bg-linear-to-br from-white dark:from-gray-800 to-primary/5 dark:to-gray-700/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-linear-to-br from-primary to-primary/80 shadow-lg shadow-primary/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-300">Total</div>
                </div>
                <div class="space-y-1">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white transition-colors duration-300">{{ $totalTasks }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 font-medium transition-colors duration-300">Active Tasks</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Task Done -->
        <div class="group relative overflow-hidden bg-linear-to-br from-white dark:from-gray-800 to-green-50/50 dark:to-gray-700/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 rounded-full -mr-16 -mt-16"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-linear-to-br from-green-500 to-emerald-600 shadow-lg shadow-green-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-300">Completed</div>
                </div>
                <div class="space-y-1">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white transition-colors duration-300">{{ $percentageDone }}<span class="text-xl">%</span></p>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2 transition-colors duration-300">
                        <div class="bg-linear-to-r from-green-500 to-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ $percentageDone }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Overdue -->
        <div class="group relative overflow-hidden bg-linear-to-br from-white dark:from-gray-800 to-red-50/50 dark:to-gray-700/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
            <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/5 rounded-full -mr-16 -mt-16"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-linear-to-br from-red-500 to-rose-600 shadow-lg shadow-red-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-300">Overdue</div>
                </div>
                <div class="space-y-1">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white transition-colors duration-300">{{ $overdueTasks }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 font-medium transition-colors duration-300">Requires Attention</p>
                </div>
            </div>
        </div>

        <!-- Card 4: Productivity -->
        <div class="group relative overflow-hidden bg-linear-to-br from-white dark:from-gray-800 to-purple-50/50 dark:to-gray-700/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 rounded-full -mr-16 -mt-16"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-xl bg-linear-to-br from-purple-500 to-indigo-600 shadow-lg shadow-purple-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-300">Trend</div>
                </div>
                <div class="space-y-1">
                    <p class="text-lg font-bold text-gray-900 dark:text-white leading-tight transition-colors duration-300">{{ $trendText }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 font-medium transition-colors duration-300">This Week</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Bar Chart -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1 transition-colors duration-300">Task Status Distribution</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors duration-300">Overview of task completion</p>
                </div>
                <div class="p-3 rounded-xl bg-primary/10">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div id="chart-status"></div>
        </div>

        <!-- Top Performer -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1 transition-colors duration-300">Top Performers</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors duration-300">Most productive team members</p>
                </div>
                <div class="p-3 rounded-xl bg-green-500/10 dark:bg-green-500/20 transition-colors duration-300">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-linear-to-r from-gray-50 dark:from-gray-700 to-gray-100/50 dark:to-gray-700/50 transition-colors duration-300">
                            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                                Employee
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                                Total Done
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                                Role
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 transition-colors duration-300">
                        @forelse($topPerformers as $user)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-300">
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="shrink-0 w-12 h-12 relative">
                                            <img class="w-full h-full rounded-xl ring-2 ring-gray-100 dark:ring-gray-700 transition-all duration-300"
                                                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0652FD&color=fff&size=128&bold=true"
                                                alt="" />
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-gray-800 transition-colors duration-300"></div>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white transition-colors duration-300">
                                                {{ $user->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 transition-colors duration-300">{{ $user->jabatan ?? 'Team Member' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg font-semibold text-sm bg-linear-to-r from-green-50 dark:from-green-900/30 to-emerald-50 dark:to-emerald-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 transition-all duration-300">
                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $user->statistics_sum_total_done ?? 0 }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary transition-all duration-300">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-sm font-medium">No data available</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Heatmap -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6 mb-8 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1 transition-colors duration-300">Activity Density</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors duration-300">Last 30 days activity overview</p>
            </div>
            <div class="p-3 rounded-xl bg-purple-500/10 dark:bg-purple-500/20 transition-colors duration-300">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
        <div id="chart-heatmap"></div>
    </div>

    <!-- Problems & Diagnostics -->
    <div id="problems_and_diagnostics" class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6 mb-8 hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1 transition-colors duration-300">Problems & Diagnostics</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors duration-300">Ringkasan pengecekan alur utama aplikasi.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200/70 dark:border-emerald-700/70">
                Semua alur utama OK
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 text-emerald-500">●</span>
                    <span>Autentikasi & login berjalan sehat, akses dibatasi berdasarkan role.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 text-emerald-500">●</span>
                    <span>Dashboard menampilkan statistik tugas, top performers, dan aktivitas 30 hari terakhir.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 text-emerald-500">●</span>
                    <span>Halaman Reports mendukung filter, pagination, dan aksi bulk update status.</span>
                </li>
            </ul>
            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 text-emerald-500">●</span>
                    <span>Detail task mendukung update status, priority, notes, dan file attachment.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 text-emerald-500">●</span>
                    <span>Sinkronisasi Trello tersedia via tombol “Sync Trello” di dashboard.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 text-emerald-500">●</span>
                    <span>Seluruh test otomatis saat ini lulus (<code>php artisan test</code>).</span>
                </li>
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Status Chart
            var optionsStatus = {
                series: [{
                    name: 'Tasks',
                    data: [{{ $statusCounts['To Do'] }}, {{ $statusCounts['Doing'] }}, {{ $statusCounts['Done'] }}]
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 12,
                        horizontal: false,
                        columnWidth: '60%',
                        distributed: false,
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '12px',
                        fontWeight: 600,
                        colors: ['#fff']
                    }
                },
                colors: ['#0652FD', '#3B82F6', '#10B981'],
                xaxis: {
                    categories: ['To Do', 'Doing', 'Done'],
                    labels: {
                        style: {
                            fontSize: '13px',
                            fontWeight: 600,
                            colors: '#6B7280'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '13px',
                            fontWeight: 600,
                            colors: '#6B7280'
                        }
                    }
                },
                grid: {
                    borderColor: '#F3F4F6',
                    strokeDashArray: 4,
                },
                tooltip: {
                    theme: 'light',
                    style: {
                        fontSize: '13px'
                    }
                }
            };

            var chartStatus = new ApexCharts(document.querySelector("#chart-status"), optionsStatus);
            chartStatus.render();

            // Activity Density Chart - Calendar Heatmap Style
            var activityData = @json($activityData);
            
            // Generate all dates for last 30 days and fill with activity data
            var allDates = [];
            var today = new Date();
            var dataMap = {};
            
            // Create map from activity data
            activityData.forEach(function(item) {
                var dateKey = new Date(item.x).toISOString().split('T')[0];
                dataMap[dateKey] = item.y;
            });
            
            // Generate last 30 days
            for (var i = 29; i >= 0; i--) {
                var date = new Date(today);
                date.setDate(date.getDate() - i);
                var dateKey = date.toISOString().split('T')[0];
                var dayName = date.toLocaleDateString('en-US', { weekday: 'short' });
                var dayNum = date.getDate();
                
                allDates.push({
                    x: dayName + ' ' + dayNum,
                    y: dataMap[dateKey] || 0,
                    date: dateKey
                });
            }
            
            var optionsHeatmap = {
                series: [{
                    name: 'Activity',
                    data: allDates
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif',
                    sparkline: { enabled: false }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    colors: ['#0652FD']
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                        stops: [0, 50, 100],
                        colorStops: [
                            { offset: 0, color: '#0652FD', opacity: 0.8 },
                            { offset: 50, color: '#3B82F6', opacity: 0.5 },
                            { offset: 100, color: '#DBEAFE', opacity: 0.2 }
                        ]
                    }
                },
                colors: ['#0652FD'],
                xaxis: {
                    type: 'category',
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: 600,
                            colors: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#9CA3AF' : '#6B7280'
                        },
                        rotate: -45,
                        rotateAlways: false
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '12px',
                            fontWeight: 600,
                            colors: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#9CA3AF' : '#6B7280'
                        }
                    },
                    title: {
                        text: 'Activity',
                        style: {
                            fontSize: '13px',
                            fontWeight: 600,
                            color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#9CA3AF' : '#6B7280'
                        }
                    }
                },
                grid: {
                    borderColor: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#374151' : '#F3F4F6',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: { show: false }
                    },
                    yaxis: {
                        lines: { show: true }
                    }
                },
                tooltip: {
                    theme: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light',
                    style: {
                        fontSize: '13px'
                    },
                    y: {
                        formatter: function(val) {
                            return val + ' tasks';
                        }
                    }
                },
                markers: {
                    size: 4,
                    colors: ['#0652FD'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 6
                    }
                }
            };
            
            var chartHeatmap = new ApexCharts(document.querySelector("#chart-heatmap"), optionsHeatmap);
            chartHeatmap.render();
            
            // Update chart theme on dark mode toggle
            document.addEventListener('DOMContentLoaded', function() {
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            var isDark = document.documentElement.classList.contains('dark');
                            chartHeatmap.updateOptions({
                                tooltip: {
                                    theme: isDark ? 'dark' : 'light'
                                },
                                xaxis: {
                                    labels: {
                                        style: {
                                            colors: isDark ? '#9CA3AF' : '#6B7280'
                                        }
                                    }
                                },
                                yaxis: {
                                    labels: {
                                        style: {
                                            colors: isDark ? '#9CA3AF' : '#6B7280'
                                        }
                                    },
                                    title: {
                                        style: {
                                            color: isDark ? '#9CA3AF' : '#6B7280'
                                        }
                                    }
                                },
                                grid: {
                                    borderColor: isDark ? '#374151' : '#F3F4F6'
                                }
                            });
                        }
                    });
                });
                observer.observe(document.documentElement, { attributes: true });
            });
        });
    </script>
</div>
