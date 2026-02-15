<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2 transition-colors duration-300">Laporan Aktivitas</h1>
            <p class="text-gray-600 dark:text-gray-400 transition-colors duration-300">Analisis dan laporan tugas tim</p>
        </div>
        
        <!-- Export Button -->
        <button wire:click="export" class="group relative overflow-hidden bg-linear-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg shadow-green-500/20 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Export Excel</span>
        </button>
    </div>

    <!-- Filter Card -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6 transition-all duration-300">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-primary/10 dark:bg-primary/20 transition-colors duration-300">
                <svg class="w-6 h-6 text-primary dark:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-lg font-bold text-gray-900 dark:text-white transition-colors duration-300">Filter Data</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors duration-300">Sesuaikan kriteria pencarian</p>
            </div>
        </div>
        <div class="space-y-4">
            <!-- Search Bar -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Search Tasks</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by title or description..." class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-xl focus:bg-white dark:focus:bg-gray-600 focus:border-primary dark:focus:border-blue-500 focus:ring-4 focus:ring-primary/10 dark:focus:ring-blue-500/20 transition-all duration-300 outline-none">
                </div>
            </div>

            <!-- Filters Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Start Date</label>
                    <input type="date" wire:model.live="startDate" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl focus:bg-white dark:focus:bg-gray-600 focus:border-primary dark:focus:border-blue-500 focus:ring-4 focus:ring-primary/10 dark:focus:ring-blue-500/20 transition-all duration-300 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">End Date</label>
                    <input type="date" wire:model.live="endDate" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl focus:bg-white dark:focus:bg-gray-600 focus:border-primary dark:focus:border-blue-500 focus:ring-4 focus:ring-primary/10 dark:focus:ring-blue-500/20 transition-all duration-300 outline-none">
                </div>

                <!-- User Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Employee</label>
                    <select wire:model.live="userId" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl focus:bg-white dark:focus:bg-gray-600 focus:border-primary dark:focus:border-blue-500 focus:ring-4 focus:ring-primary/10 dark:focus:ring-blue-500/20 transition-all duration-300 outline-none">
                        <option value="">All Employees</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Status</label>
                    <select wire:model.live="status" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl focus:bg-white dark:focus:bg-gray-600 focus:border-primary dark:focus:border-blue-500 focus:ring-4 focus:ring-primary/10 dark:focus:ring-blue-500/20 transition-all duration-300 outline-none">
                        <option value="">All Status</option>
                        <option value="To Do">To Do</option>
                        <option value="Doing">Doing</option>
                        <option value="Done">Done</option>
                    </select>
                </div>

                <!-- Priority Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Priority</label>
                    <select wire:model.live="priority" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl focus:bg-white dark:focus:bg-gray-600 focus:border-primary dark:focus:border-blue-500 focus:ring-4 focus:ring-primary/10 dark:focus:ring-blue-500/20 transition-all duration-300 outline-none">
                        <option value="">All Priority</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    @if(count($selectedTasks) > 0)
    <div class="bg-linear-to-r from-primary to-blue-600 rounded-2xl shadow-lg p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold">{{ count($selectedTasks) }} task(s) selected</span>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="bulkUpdateStatus('To Do')" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-lg transition-colors">
                    Mark as To Do
                </button>
                <button wire:click="bulkUpdateStatus('Doing')" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-lg transition-colors">
                    Mark as Doing
                </button>
                <button wire:click="bulkUpdateStatus('Done')" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-lg transition-colors">
                    Mark as Done
                </button>
                <button wire:click="bulkDelete" onclick="return confirm('Are you sure you want to delete {{ count($selectedTasks) }} task(s)?')" class="px-4 py-2 bg-red-500/80 hover:bg-red-500 text-white text-sm font-semibold rounded-lg transition-colors">
                    Delete
                </button>
                <button wire:click="$set('selectedTasks', [])" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-linear-to-r from-gray-50 dark:from-gray-700 to-gray-100/50 dark:to-gray-700/50 transition-colors duration-300">
                        <th class="px-6 py-4 text-left">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="selectAll" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-primary focus:ring-primary bg-white dark:bg-gray-700 transition-colors duration-300">
                            </label>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Task Title
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Priority
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Assigned To
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Due Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Last Update
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 transition-colors duration-300">
                    @forelse($tasks as $task)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-300 {{ in_array($task->id, $selectedTasks) ? 'bg-blue-50/50 dark:bg-blue-900/20' : '' }}">
                        <!-- Checkbox -->
                        <td class="px-6 py-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       wire:model.live="selectedTasks" 
                                       value="{{ $task->id }}" 
                                       class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-primary focus:ring-primary bg-white dark:bg-gray-700 transition-colors duration-300">
                            </label>
                        </td>
                        
                        <!-- Task Title -->
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('tasks.show', $task->id) }}" class="group">
                                <p class="font-semibold text-gray-900 dark:text-white group-hover:text-primary transition-colors duration-300 mb-1">{{ $task->judul }}</p>
                                @if($task->deskripsi)
                                    <p class="text-gray-500 dark:text-gray-400 text-xs line-clamp-2 max-w-md transition-colors duration-300">{{ $task->deskripsi }}</p>
                                @endif
                            </a>
                        </td>
                        
                        <!-- Priority -->
                        <td class="px-6 py-4 text-sm">
                            @php
                                $priorityConfig = [
                                    'low' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-700 dark:text-gray-300', 'icon' => '↓', 'label' => 'Low'],
                                    'medium' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-700 dark:text-blue-300', 'icon' => '→', 'label' => 'Medium'],
                                    'high' => ['bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-700 dark:text-orange-300', 'icon' => '↑', 'label' => 'High'],
                                    'urgent' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-700 dark:text-red-300', 'icon' => '⚠', 'label' => 'Urgent'],
                                ];
                                $priority = $task->priority ?? 'medium';
                                $priorityData = $priorityConfig[$priority] ?? $priorityConfig['medium'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold {{ $priorityData['bg'] }} {{ $priorityData['text'] }} border border-current/20 transition-all duration-300">
                                <span>{{ $priorityData['icon'] }}</span>
                                {{ $priorityData['label'] }}
                            </span>
                        </td>
                        
                        <!-- Assigned To -->
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                @if($task->user)
                                    <div class="shrink-0 w-10 h-10">
                                        <img class="w-full h-full rounded-xl ring-2 ring-gray-100 dark:ring-gray-700 transition-all duration-300"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($task->user->name) }}&background=0652FD&color=fff&size=128&bold=true"
                                            alt="" />
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white transition-colors duration-300">
                                            {{ $task->user->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 transition-colors duration-300">{{ $task->user->jabatan ?? 'Team Member' }}</p>
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 italic text-sm transition-colors duration-300">Unassigned</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Status -->
                        <td class="px-6 py-4 text-sm">
                            @php
                                $statusConfig = [
                                    'To Do' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-800 dark:text-gray-300', 'border' => 'border-gray-200 dark:border-gray-600'],
                                    'Doing' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-800 dark:text-blue-300', 'border' => 'border-blue-200 dark:border-blue-800'],
                                    'Done' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-800 dark:text-green-300', 'border' => 'border-green-200 dark:border-green-800'],
                                ];
                                $config = $statusConfig[$task->status_tugas] ?? $statusConfig['To Do'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }} transition-all duration-300">
                                {{ $task->status_tugas }}
                            </span>
                        </td>
                        
                        <!-- Due Date -->
                        <td class="px-6 py-4 text-sm">
                            @if($task->due_date)
                                @php
                                    $isOverdue = $task->due_date < now() && $task->status_tugas !== 'Done';
                                    $isDueSoon = $task->due_date <= now()->addDays(1) && $task->due_date > now();
                                @endphp
                                <div class="flex items-center gap-2 {{ $isOverdue ? 'text-red-600 dark:text-red-400' : ($isDueSoon ? 'text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400') }} transition-colors duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium {{ $isOverdue ? 'font-bold' : '' }}">
                                        {{ $task->due_date->format('d M Y') }}
                                    </span>
                                    @if($isOverdue)
                                        <span class="text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-2 py-0.5 rounded transition-all duration-300">Overdue</span>
                                    @elseif($isDueSoon)
                                        <span class="text-xs bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 px-2 py-0.5 rounded transition-all duration-300">Due Soon</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 dark:text-gray-500 italic text-sm transition-colors duration-300">No due date</span>
                            @endif
                        </td>
                        
                        <!-- Last Update -->
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 transition-colors duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">{{ $task->diperbarui ? $task->diperbarui->format('d M Y') : '-' }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm font-medium">No tasks found matching your criteria</p>
                                <p class="text-xs mt-1">Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 flex flex-col xs:flex-row items-center xs:justify-between transition-colors duration-300">
            {{ $tasks->links() }}
        </div>
    </div>
</div>
