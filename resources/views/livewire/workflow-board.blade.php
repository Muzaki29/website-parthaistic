<div
    class="space-y-6"
    x-data="{
        draggingTaskId: null,
        sourceStatus: null,
        targetStatus: null,
        isSaving: false,
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
                <section class="ui-card snap-start p-3">
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
                                    <svg class="h-4 w-4 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h8M8 12h8M8 18h8"/>
                                    </svg>
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
</div>

