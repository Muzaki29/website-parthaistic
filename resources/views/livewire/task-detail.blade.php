<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Task Details</h1>
            <p class="text-gray-700 dark:text-gray-400">View and manage task information</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button wire:click="openEditModal" class="ui-btn-secondary border-2 px-4 py-2 text-sm">
                Edit Task
            </button>
            <a href="{{ route('reports') }}" class="ui-btn-primary px-4 py-2 text-sm">
                Back to Reports
            </a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="flex items-center gap-3 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 rounded-xl">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Task Info Card -->
            <div class="ui-card ui-reveal p-6">
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $task->judul }}</h2>
                        @if($task->deskripsi)
                            <p class="leading-relaxed text-gray-700 dark:text-gray-300">{{ $task->deskripsi }}</p>
                        @endif
                    </div>

                    @if($task->notes)
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                        <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">Notes</h3>
                        <p class="text-sm text-blue-800 dark:text-blue-400">{{ $task->notes }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">Status</label>
                            @php
                                $statusConfig = [
                                    'Drop idea' => ['bg' => 'bg-slate-100 dark:bg-slate-700', 'text' => 'text-slate-800 dark:text-slate-300'],
                                    'Script idea' => ['bg' => 'bg-violet-100 dark:bg-violet-900/30', 'text' => 'text-violet-800 dark:text-violet-300'],
                                    'Script written' => ['bg' => 'bg-indigo-100 dark:bg-indigo-900/30', 'text' => 'text-indigo-800 dark:text-indigo-300'],
                                    'Script preview' => ['bg' => 'bg-sky-100 dark:bg-sky-900/30', 'text' => 'text-sky-800 dark:text-sky-300'],
                                    'Crew call shooting' => ['bg' => 'bg-amber-100 dark:bg-amber-900/30', 'text' => 'text-amber-800 dark:text-amber-300'],
                                    'Production' => ['bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-800 dark:text-orange-300'],
                                    'Post - Production' => ['bg' => 'bg-pink-100 dark:bg-pink-900/30', 'text' => 'text-pink-800 dark:text-pink-300'],
                                    'Preview' => ['bg' => 'bg-cyan-100 dark:bg-cyan-900/30', 'text' => 'text-cyan-800 dark:text-cyan-300'],
                                    'Finished' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-800 dark:text-green-300'],
                                ];
                                $config = $statusConfig[$task->status_tugas] ?? $statusConfig['Drop idea'];
                            @endphp
                            <p class="mt-1 inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                {{ $task->status_tugas }}
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">Priority</label>
                            @php
                                $priorityConfig = [
                                    'low' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-700 dark:text-gray-300', 'label' => 'Low'],
                                    'medium' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-700 dark:text-blue-300', 'label' => 'Medium'],
                                    'high' => ['bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-700 dark:text-orange-300', 'label' => 'High'],
                                    'urgent' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-700 dark:text-red-300', 'label' => 'Urgent'],
                                ];
                                $priority = $task->priority ?? 'medium';
                                $priorityData = $priorityConfig[$priority] ?? $priorityConfig['medium'];
                            @endphp
                            <p class="mt-1 inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold {{ $priorityData['bg'] }} {{ $priorityData['text'] }}">
                                {{ $priorityData['label'] }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">Due Date</label>
                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $task->due_date ? $task->due_date->format('d M Y, H:i') : 'No due date' }}
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">Assigned To</label>
                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $task->user ? $task->user->name : 'Unassigned' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Files Section -->
            <div class="ui-card ui-reveal p-6" data-reveal-delay="1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Attachments</h3>
                
                <form wire:submit.prevent="uploadFiles" class="mb-6">
                    <div class="flex items-center gap-3">
                        <input type="file" wire:model="files" multiple class="min-h-[42px] flex-1 rounded-xl border-2 border-gray-300 bg-gray-50 text-sm text-gray-800 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:file:bg-indigo-900/40 dark:file:text-indigo-200">
                        <button type="submit" class="ui-btn-primary px-6 py-2">
                            Upload
                        </button>
                    </div>
                    @error('files.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </form>

                <div class="space-y-2">
                    @forelse($uploadedFiles as $file)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center gap-3 flex-1">
                            <svg class="h-5 w-5 shrink-0 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $file->file_name }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ number_format($file->file_size / 1024, 2) }} KB •
                                    Uploaded by {{ $file->uploader->name ?? 'Unknown' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="rounded-lg p-2 text-primary transition-colors hover:bg-primary/10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>
                            <button wire:click="confirmDeleteFile({{ $file->id }})" class="rounded-lg p-2 text-red-600 transition-colors hover:bg-red-50 dark:hover:bg-red-900/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <p class="py-8 text-center text-sm font-medium text-gray-600 dark:text-gray-400">No files attached</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Task Info -->
            <div class="ui-card ui-reveal p-6" data-reveal-delay="2">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Task Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">Created</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $task->dibuat->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">Last Updated</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $task->diperbarui->format('d M Y, H:i') }}</p>
                    </div>
                    @if($task->user)
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">Assigned To</label>
                        <div class="mt-2 flex items-center gap-2">
                            <img class="w-8 h-8 rounded-lg" src="https://ui-avatars.com/api/?name={{ urlencode($task->user->name) }}&background=0652FD&color=fff" alt="">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->user->name }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $task->user->jabatan ?? 'Team Member' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($showEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="edit-task-modal" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="ui-modal-backdrop" aria-hidden="true" wire:click="closeEditModal"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            <div class="ui-modal-shell text-left sm:max-w-3xl">
                <div class="bg-linear-to-r from-primary to-blue-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white" id="edit-task-modal">Edit Task</h3>
                        <button wire:click="closeEditModal" class="text-white/80 transition-colors hover:text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <form wire:submit.prevent="updateTask" class="space-y-5 px-6 pb-6 pt-5">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Task Title</label>
                        <input type="text" wire:model="judul" class="ui-input py-3">
                        @error('judul') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Description</label>
                        <textarea wire:model="deskripsi" rows="3" class="ui-input py-3"></textarea>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea wire:model="notes" rows="3" class="ui-input py-3"></textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                            <select wire:model="status_tugas" class="ui-input py-3">
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Priority</label>
                            <select wire:model="priority" class="ui-input py-3">
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
                            <input type="datetime-local" wire:model="due_date" class="ui-input py-3">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Assign To</label>
                            <select wire:model="assigned_to" class="ui-input py-3">
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

    @if($showDeleteFileModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="delete-file-modal" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="ui-modal-backdrop" aria-hidden="true" wire:click="cancelDeleteFile"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            <div class="ui-modal-shell text-left sm:max-w-md">
                <div class="px-6 py-5">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="delete-file-modal">Delete attachment?</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">File yang dihapus tidak bisa dikembalikan lagi.</p>
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="cancelDeleteFile" class="ui-btn-secondary px-4 py-2">Cancel</button>
                    <button type="button" wire:click="deleteFile" class="ui-btn-danger px-4 py-2">Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

