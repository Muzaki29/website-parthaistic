<div
    class="ui-data-dense space-y-6"
    x-data="{
        draggingTaskId: null,
        sourceStatus: null,
        targetStatus: null,
        isSaving: false,
        actionMenuTaskId: null,
        toast: { show: false, message: '', type: 'success' },
        toastTimer: null,
        showToast(message, type = 'success') {
            clearTimeout(this.toastTimer);
            this.toast = { show: true, message, type };
            this.toastTimer = setTimeout(() => this.toast.show = false, 2400);
        },
        startDrag(taskId, status) {
            this.draggingTaskId = taskId;
            this.sourceStatus = status;
        },
        clearDrag() {
            this.draggingTaskId = null;
            this.sourceStatus = null;
            this.targetStatus = null;
        },
        async dropTo(status) {
            if (!this.draggingTaskId || this.isSaving) return;
            if (this.sourceStatus === status) {
                this.clearDrag();
                return;
            }

            this.isSaving = true;
            await $wire.updateTaskStatus(this.draggingTaskId, status);
            this.isSaving = false;
            this.clearDrag();
        }
    }"
    @keydown.window.prevent.n="$wire.openQuickCreate()"
    @board-toast.window="showToast($event.detail.message, $event.detail.type)"
>
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Workflow Board</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola alur kerja konten dari ide sampai selesai dalam 9 tahap.</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Shortcut: tekan <span class="font-semibold">N</span> untuk buka quick add card.</p>
        </div>
        <div class="w-full md:w-80">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari task..." class="ui-input">
        </div>
    </div>

    <div class="overflow-x-auto pb-2 snap-x snap-mandatory">
        <div class="grid min-w-[1800px] grid-cols-9 gap-4">
            @foreach($statuses as $status)
                <section class="ui-card ui-reveal-soft snap-start p-3" data-reveal-delay="{{ $loop->index % 3 }}">
                    <div class="mb-3 flex items-center justify-between gap-2">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $status }}</h2>
                        <span class="inline-flex h-6 min-w-[24px] items-center justify-center rounded-full bg-primary/10 px-2 text-xs font-semibold text-primary dark:bg-primary/20">
                            {{ $tasksByStatus->get($status, collect())->count() }}
                        </span>
                    </div>

                    <div
                        class="space-y-3 rounded-xl p-1 transition-all"
                        :class="targetStatus === @js($status) ? 'bg-primary/5 ring-1 ring-primary/30' : ''"
                        wire:loading.class="ui-loading"
                        wire:target="search,updateTaskStatus,createCardAndClose,createCardAndAddAnother"
                        @dragover.prevent="targetStatus = @js($status)"
                        @dragleave="if (targetStatus === @js($status)) targetStatus = null"
                        @drop.prevent="dropTo(@js($status))"
                    >
                        <template x-if="targetStatus === @js($status) && draggingTaskId">
                            <div class="rounded-xl border border-dashed border-primary/50 bg-primary/5 px-3 py-3 text-center text-[11px] font-semibold text-primary">
                                Drop here to move
                            </div>
                        </template>

                        @forelse($tasksByStatus->get($status, collect()) as $task)
                            <article
                                draggable="true"
                                @dragstart="startDrag({{ $task->id }}, @js($task->status_tugas))"
                                @dragend="clearDrag()"
                                class="ui-card cursor-grab rounded-xl bg-white p-3 transition active:cursor-grabbing dark:bg-gray-900/80"
                                :class="draggingTaskId === {{ $task->id }} ? 'opacity-60 ring-2 ring-primary/30' : 'hover:border-primary/30 hover:shadow'"
                            >
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Drag card</span>
                                    <div class="relative" @click.outside="actionMenuTaskId = null">
                                        <button type="button" class="rounded-md p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 dark:hover:text-gray-200" @click.stop="actionMenuTaskId = actionMenuTaskId === {{ $task->id }} ? null : {{ $task->id }}">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5h.01M12 12h.01M12 19h.01"/>
                                            </svg>
                                        </button>
                                        <div x-show="actionMenuTaskId === {{ $task->id }}" x-cloak x-transition class="absolute right-0 z-20 mt-1 w-36 rounded-lg border border-gray-200 bg-white p-1 shadow-lg dark:border-gray-700 dark:bg-gray-900">
                                            <button type="button" class="block w-full rounded-md px-3 py-2 text-left text-xs font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800" @click="actionMenuTaskId = null; $wire.openEditModal({{ $task->id }})">Edit card</button>
                                            <button type="button" class="block w-full rounded-md px-3 py-2 text-left text-xs font-semibold text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20" @click="actionMenuTaskId = null; $wire.confirmDeleteTask({{ $task->id }})">Delete card</button>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('tasks.show', $task->id) }}" class="block">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $task->judul }}</h3>
                                    <p class="mt-1 line-clamp-2 text-xs text-gray-600 dark:text-gray-400">{{ $task->deskripsi ?: 'Tanpa deskripsi' }}</p>
                                </a>

                                <div class="mt-2 flex items-center justify-between text-[11px] text-gray-500 dark:text-gray-400">
                                    <span>{{ $task->user?->name ?? 'Unassigned' }}</span>
                                    <span>{{ optional($task->due_date)->format('d M') ?: '-' }}</span>
                                </div>
                            </article>
                        @empty
                            <div class="ui-empty-state rounded-xl px-3 py-6 text-xs text-gray-500 dark:text-gray-400">
                                Belum ada task
                            </div>
                        @endforelse

                        @if($createForStatus === $status)
                            <div
                                class="ui-card rounded-xl border-primary/30 bg-white p-3 dark:bg-gray-900/80"
                                x-data
                                @keydown.escape.prevent.stop="$wire.cancelCreateCard()"
                            >
                                <input
                                    type="text"
                                    wire:model.defer="newTitle"
                                    @keydown.enter.prevent="$wire.createCardAndClose(@js($status))"
                                    x-init="$nextTick(() => $el.focus())"
                                    placeholder="Card title..."
                                    class="ui-input rounded-lg px-2 py-1.5 text-xs"
                                >
                                @error('newTitle')
                                    <p class="mt-1 text-[11px] text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror

                                <textarea
                                    wire:model.defer="newDescription"
                                    rows="3"
                                    @keydown.ctrl.enter.prevent="$wire.createCardAndClose(@js($status))"
                                    placeholder="Description (optional)"
                                    class="ui-input mt-2 rounded-lg px-2 py-1.5 text-xs text-gray-700"
                                ></textarea>
                                @error('newDescription')
                                    <p class="mt-1 text-[11px] text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror

                                <div class="mt-2 flex items-center gap-2">
                                    <button
                                        type="button"
                                        wire:click="createCardAndClose(@js($status))"
                                        wire:loading.attr="disabled"
                                        wire:target="createCardAndClose,createCardAndAddAnother"
                                        class="ui-btn-primary rounded-lg px-3 py-1.5 text-xs disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        <span wire:loading.remove wire:target="createCardAndClose,createCardAndAddAnother">Add card</span>
                                        <span wire:loading wire:target="createCardAndClose,createCardAndAddAnother">Saving...</span>
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="createCardAndAddAnother(@js($status))"
                                        wire:loading.attr="disabled"
                                        wire:target="createCardAndClose,createCardAndAddAnother"
                                        class="rounded-lg bg-indigo-100 px-3 py-1.5 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-200 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-indigo-900/40 dark:text-indigo-200 dark:hover:bg-indigo-900/60"
                                    >
                                        Add another card
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="cancelCreateCard"
                                        class="ui-btn-secondary rounded-lg px-3 py-1.5 text-xs"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        @else
                            <button
                                type="button"
                                wire:click="openCreateCard(@js($status))"
                                class="w-full rounded-lg border border-dashed border-gray-300 px-3 py-2 text-left text-xs font-semibold text-gray-600 hover:border-primary/40 hover:bg-primary/5 hover:text-primary dark:border-gray-600 dark:text-gray-300 dark:hover:bg-primary/10"
                            >
                                + Add a new card
                            </button>
                        @endif
                    </div>
                </section>
            @endforeach
        </div>
    </div>

    <p class="text-xs text-gray-500 dark:text-gray-400">
        Tips: drag kartu ke kolom lain untuk mengubah status. Enter pada judul untuk submit cepat, Ctrl+Enter dari deskripsi, Esc untuk batal.
    </p>

    <div
        x-show="toast.show"
        x-transition
        x-cloak
        class="fixed bottom-5 right-5 z-[60] max-w-xs rounded-xl border border-emerald-200 bg-white/95 px-4 py-3 text-sm font-medium text-emerald-700 shadow-xl dark:border-emerald-800 dark:bg-gray-900 dark:text-emerald-300"
    >
        <div class="flex items-start gap-2">
            <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span x-text="toast.message"></span>
        </div>
    </div>

    @if($showEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div class="ui-modal-backdrop" wire:click="closeEditModal"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
            <div class="ui-modal-shell text-left sm:max-w-2xl">
                <div class="bg-linear-to-r from-primary to-blue-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white">Edit Card</h3>
                        <button wire:click="closeEditModal" class="text-white/80 hover:text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <form wire:submit.prevent="saveTaskEdit" class="space-y-4 px-6 pb-6 pt-5">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Title</label>
                        <input type="text" wire:model="editTitle" class="ui-input py-3">
                        @error('editTitle') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Description</label>
                        <textarea wire:model="editDescription" rows="3" class="ui-input py-3"></textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                            <select wire:model="editStatus" class="ui-input py-3">
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Priority</label>
                            <select wire:model="editPriority" class="ui-input py-3">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Due date</label>
                            <input type="datetime-local" wire:model="editDueDate" class="ui-input py-3">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Assign to</label>
                            <select wire:model="editAssignedTo" class="ui-input py-3">
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
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Task akan dihapus permanen dari board.</p>
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="cancelDeleteTask" class="ui-btn-secondary px-4 py-2">Cancel</button>
                    <button type="button" wire:click="deleteTask" class="ui-btn-danger px-4 py-2">Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

