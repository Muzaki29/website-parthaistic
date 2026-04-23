<div class="ui-data-dense space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2 transition-colors duration-300">Laporan Aktivitas</h1>
            <p class="text-gray-600 dark:text-gray-400 transition-colors duration-300">Analisis dan laporan tugas tim</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            @if($canCreateTask)
            <button wire:click="openCreateModal" class="ui-btn-primary px-5 py-3">
                + Add Task
            </button>
            @endif
            <button wire:click="export" wire:loading.attr="disabled" wire:target="export" class="group relative flex items-center gap-2 overflow-hidden rounded-xl bg-linear-to-r from-green-600 to-emerald-600 px-6 py-3 font-semibold text-white shadow-lg shadow-green-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-70">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span wire:loading.remove wire:target="export">Export Excel</span>
                <span wire:loading wire:target="export">Exporting...</span>
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="ui-card ui-reveal p-6 transition-all duration-300">
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by title or description..." class="ui-input pl-12 py-3 placeholder-gray-400 dark:placeholder-gray-500">
                </div>
            </div>

            <!-- Filters Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Start Date</label>
                    <input type="date" wire:model.live="startDate" class="ui-input py-3">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">End Date</label>
                    <input type="date" wire:model.live="endDate" class="ui-input py-3">
                </div>

                <!-- User Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Employee</label>
                    <select wire:model.live="userId" class="ui-input py-3">
                        <option value="">All Employees</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Status</label>
                    <select wire:model.live="status" class="ui-input py-3">
                        <option value="">All Status</option>
                        @foreach($statuses as $statusOption)
                            <option value="{{ $statusOption }}">{{ $statusOption }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Priority Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Priority</label>
                    <select wire:model.live="priority" class="ui-input py-3">
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
    <div class="mb-4 rounded-2xl bg-linear-to-r from-primary to-blue-600 p-4 shadow-lg">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold">{{ count($selectedTasks) }} task(s) selected</span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button wire:click="bulkUpdateStatus('Drop idea')" class="px-3 py-2 bg-white/20 hover:bg-white/30 text-white text-xs sm:text-sm font-semibold rounded-lg transition-colors">
                    Move to Drop idea
                </button>
                <button wire:click="bulkUpdateStatus('Production')" class="px-3 py-2 bg-white/20 hover:bg-white/30 text-white text-xs sm:text-sm font-semibold rounded-lg transition-colors">
                    Move to Production
                </button>
                <button wire:click="bulkUpdateStatus('Finished')" class="px-3 py-2 bg-white/20 hover:bg-white/30 text-white text-xs sm:text-sm font-semibold rounded-lg transition-colors">
                    Mark as Finished
                </button>
                <button wire:click="bulkDelete" onclick="return confirm('Are you sure you want to delete {{ count($selectedTasks) }} task(s)?')" class="px-3 py-2 bg-red-500/80 hover:bg-red-500 text-white text-xs sm:text-sm font-semibold rounded-lg transition-colors">
                    Delete
                </button>
                <button wire:click="$set('selectedTasks', [])" class="px-3 py-2 bg-white/20 hover:bg-white/30 text-white text-xs sm:text-sm font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="ui-card ui-reveal-soft overflow-hidden transition-all duration-300" data-reveal-delay="1" wire:loading.class="ui-loading" wire:target="search,startDate,endDate,userId,status,priority,selectAll,selectedTasks">
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors duration-300">
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
                            <a href="{{ route('tasks.show', $task->id) }}" class="group block">
                                <p class="mb-1 font-semibold text-gray-900 transition-colors duration-300 group-hover:text-primary dark:text-white">{{ $task->judul }}</p>
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
                                    'Drop idea' => ['bg' => 'bg-slate-100 dark:bg-slate-700', 'text' => 'text-slate-800 dark:text-slate-300', 'border' => 'border-slate-200 dark:border-slate-600'],
                                    'Script idea' => ['bg' => 'bg-violet-100 dark:bg-violet-900/30', 'text' => 'text-violet-800 dark:text-violet-300', 'border' => 'border-violet-200 dark:border-violet-800'],
                                    'Script written' => ['bg' => 'bg-indigo-100 dark:bg-indigo-900/30', 'text' => 'text-indigo-800 dark:text-indigo-300', 'border' => 'border-indigo-200 dark:border-indigo-800'],
                                    'Script preview' => ['bg' => 'bg-sky-100 dark:bg-sky-900/30', 'text' => 'text-sky-800 dark:text-sky-300', 'border' => 'border-sky-200 dark:border-sky-800'],
                                    'Crew call shooting' => ['bg' => 'bg-amber-100 dark:bg-amber-900/30', 'text' => 'text-amber-800 dark:text-amber-300', 'border' => 'border-amber-200 dark:border-amber-800'],
                                    'Production' => ['bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-800 dark:text-orange-300', 'border' => 'border-orange-200 dark:border-orange-800'],
                                    'Post - Production' => ['bg' => 'bg-pink-100 dark:bg-pink-900/30', 'text' => 'text-pink-800 dark:text-pink-300', 'border' => 'border-pink-200 dark:border-pink-800'],
                                    'Preview' => ['bg' => 'bg-cyan-100 dark:bg-cyan-900/30', 'text' => 'text-cyan-800 dark:text-cyan-300', 'border' => 'border-cyan-200 dark:border-cyan-800'],
                                    'Finished' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-800 dark:text-green-300', 'border' => 'border-green-200 dark:border-green-800'],
                                ];
                                $config = $statusConfig[$task->status_tugas] ?? $statusConfig['Drop idea'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }} transition-all duration-300">
                                {{ $task->status_tugas }}
                            </span>
                        </td>
                        
                        <!-- Due Date -->
                        <td class="px-6 py-4 text-sm">
                            @if($task->due_date)
                                @php
                                    $isOverdue = $task->due_date < now() && $task->status_tugas !== 'Finished';
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
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                @if(auth()->user()->role !== 'employee' || (int) $task->assigned_to === (int) auth()->id())
                                <button wire:click="openEditModal({{ $task->id }})" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-primary transition hover:bg-primary/10">
                                    Edit
                                </button>
                                <button wire:click="confirmDeleteTask({{ $task->id }})" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 dark:hover:bg-red-900/20">
                                    Delete
                                </button>
                                @endif
                                <a href="{{ route('tasks.show', $task->id) }}" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-gray-600 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="ui-empty-state flex flex-col items-center justify-center text-gray-400">
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
        
        <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex flex-col xs:flex-row items-center xs:justify-between transition-colors duration-300">
            {{ $tasks->links() }}
        </div>
    </div>

    @if($showCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="ui-modal-backdrop" wire:click="closeCreateModal"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
            <div class="ui-modal-shell text-left sm:max-w-2xl">
                <div class="bg-linear-to-r from-primary to-blue-600 px-6 py-5">
                    <h3 class="text-xl font-bold text-white">Create Task</h3>
                </div>
                <form wire:submit.prevent="createTask" class="space-y-4 px-6 pb-6 pt-5">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Title</label>
                        <input type="text" wire:model="formTitle" class="ui-input py-3">
                        @error('formTitle') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Description</label>
                        <textarea wire:model="formDescription" rows="3" class="ui-input py-3"></textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                            <select wire:model="formStatus" class="ui-input py-3">
                                @foreach($statuses as $statusOption)
                                    <option value="{{ $statusOption }}">{{ $statusOption }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Priority</label>
                            <select wire:model="formPriority" class="ui-input py-3">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Due Date</label>
                            <input type="datetime-local" wire:model="formDueDate" class="ui-input py-3">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Assign To</label>
                            <select wire:model="formAssignedTo" class="ui-input py-3">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse gap-3 pt-1 sm:flex-row sm:justify-end">
                        <button type="button" wire:click="closeCreateModal" class="ui-btn-secondary px-5 py-2.5">Cancel</button>
                        <button type="submit" class="ui-btn-primary px-5 py-2.5">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if($showEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="ui-modal-backdrop" wire:click="closeEditModal"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
            <div class="ui-modal-shell text-left sm:max-w-2xl">
                <div class="bg-linear-to-r from-primary to-blue-600 px-6 py-5">
                    <h3 class="text-xl font-bold text-white">Edit Task</h3>
                </div>
                <form wire:submit.prevent="updateTaskFromModal" class="space-y-4 px-6 pb-6 pt-5">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Title</label>
                        <input type="text" wire:model="formTitle" class="ui-input py-3">
                        @error('formTitle') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Description</label>
                        <textarea wire:model="formDescription" rows="3" class="ui-input py-3"></textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                            <select wire:model="formStatus" class="ui-input py-3">
                                @foreach($statuses as $statusOption)
                                    <option value="{{ $statusOption }}">{{ $statusOption }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Priority</label>
                            <select wire:model="formPriority" class="ui-input py-3">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Due Date</label>
                            <input type="datetime-local" wire:model="formDueDate" class="ui-input py-3">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Assign To</label>
                            <select wire:model="formAssignedTo" class="ui-input py-3">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse gap-3 pt-1 sm:flex-row sm:justify-end">
                        <button type="button" wire:click="closeEditModal" class="ui-btn-secondary px-5 py-2.5">Cancel</button>
                        <button type="submit" class="ui-btn-primary px-5 py-2.5">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="ui-modal-backdrop" wire:click="cancelDeleteTask"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
            <div class="ui-modal-shell text-left sm:max-w-md">
                <div class="px-6 py-5">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Delete task?</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Task akan dihapus permanen dari laporan.</p>
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="cancelDeleteTask" class="ui-btn-secondary px-4 py-2">Cancel</button>
                    <button type="button" wire:click="deleteTaskFromModal" class="ui-btn-danger px-4 py-2">Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
