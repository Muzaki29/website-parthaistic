<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class WorkflowBoard extends Component
{
    public string $search = '';
    public ?string $createForStatus = null;
    public string $newTitle = '';
    public string $newDescription = '';
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingTaskId = null;
    public ?int $deletingTaskId = null;
    public string $editTitle = '';
    public string $editDescription = '';
    public string $editStatus = '';
    public string $editPriority = 'medium';
    public ?string $editDueDate = null;
    public ?int $editAssignedTo = null;

    public function openCreateCard(string $status): void
    {
        if (! in_array($status, Task::workflowStatuses(), true)) {
            return;
        }

        $this->createForStatus = $status;
        $this->resetCreateInputs();
        $this->resetErrorBag();
    }

    public function openQuickCreate(): void
    {
        $defaultStatus = Task::workflowStatuses()[0] ?? null;
        if (! $defaultStatus) {
            return;
        }

        $this->openCreateCard($defaultStatus);
    }

    public function cancelCreateCard(): void
    {
        $this->resetCreateInputs();
        $this->createForStatus = null;
        $this->resetErrorBag();
    }

    public function createCardAndClose(string $status): void
    {
        $this->createCard($status, false);
    }

    public function createCardAndAddAnother(string $status): void
    {
        $this->createCard($status, true);
    }

    private function createCard(string $status, bool $keepOpen): void
    {
        Gate::authorize('create', Task::class);

        if (! in_array($status, Task::workflowStatuses(), true)) {
            return;
        }

        $this->validate([
            'newTitle' => ['required', 'string', 'max:255'],
            'newDescription' => ['nullable', 'string', 'max:5000'],
        ]);

        Task::create([
            'id_kartu_trello' => 'local-'.Str::uuid(),
            'judul' => trim($this->newTitle),
            'deskripsi' => trim($this->newDescription) ?: null,
            'notes' => null,
            'status_tugas' => $status,
            'priority' => 'medium',
            'due_date' => null,
            'dibuat' => now(),
            'diperbarui' => now(),
            'id_sinkron' => null,
            'assigned_to' => auth()->id(),
        ]);

        $this->dispatch('board-toast', type: 'success', message: "Card baru ditambahkan ke {$status}.");

        if ($keepOpen) {
            $this->createForStatus = $status;
            $this->resetCreateInputs();
            $this->resetErrorBag();

            return;
        }

        $this->cancelCreateCard();
    }

    public function updateTaskStatus(int $taskId, string $status): void
    {
        if (! in_array($status, Task::workflowStatuses(), true)) {
            return;
        }

        $task = Task::query()->findOrFail($taskId);
        Gate::authorize('updateStatus', $task);
        $oldStatus = $task->status_tugas;

        $task->update([
            'status_tugas' => $status,
            'diperbarui' => now(),
        ]);

        $this->dispatch('board-toast', type: 'success', message: "Task dipindah dari {$oldStatus} ke {$status}.");
    }

    public function openEditModal(int $taskId): void
    {
        $task = Task::query()->findOrFail($taskId);
        Gate::authorize('update', $task);

        $this->editingTaskId = $task->id;
        $this->editTitle = $task->judul;
        $this->editDescription = $task->deskripsi ?? '';
        $this->editStatus = $task->status_tugas;
        $this->editPriority = $task->priority ?? 'medium';
        $this->editDueDate = $task->due_date?->format('Y-m-d\TH:i');
        $this->editAssignedTo = $task->assigned_to ? (int) $task->assigned_to : null;
        $this->showEditModal = true;
        $this->resetErrorBag();
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingTaskId = null;
        $this->resetErrorBag();
    }

    public function saveTaskEdit(): void
    {
        if (! $this->editingTaskId) {
            return;
        }

        $task = Task::query()->findOrFail($this->editingTaskId);
        Gate::authorize('update', $task);

        $this->validate([
            'editTitle' => ['required', 'string', 'max:255'],
            'editDescription' => ['nullable', 'string', 'max:5000'],
            'editStatus' => ['required', 'in:'.implode(',', Task::workflowStatuses())],
            'editPriority' => ['required', 'in:low,medium,high,urgent'],
            'editDueDate' => ['nullable', 'date'],
            'editAssignedTo' => ['nullable', 'exists:users,id'],
        ]);

        $task->update([
            'judul' => trim($this->editTitle),
            'deskripsi' => trim($this->editDescription) ?: null,
            'status_tugas' => $this->editStatus,
            'priority' => $this->editPriority,
            'due_date' => $this->editDueDate ? date('Y-m-d H:i:s', strtotime($this->editDueDate)) : null,
            'assigned_to' => $this->editAssignedTo,
            'diperbarui' => now(),
        ]);

        $this->closeEditModal();
        $this->dispatch('board-toast', type: 'success', message: 'Task berhasil diperbarui.');
    }

    public function confirmDeleteTask(int $taskId): void
    {
        $task = Task::query()->findOrFail($taskId);
        Gate::authorize('update', $task);

        $this->deletingTaskId = $task->id;
        $this->showDeleteModal = true;
    }

    public function cancelDeleteTask(): void
    {
        $this->showDeleteModal = false;
        $this->deletingTaskId = null;
    }

    public function deleteTask(): void
    {
        if (! $this->deletingTaskId) {
            return;
        }

        $task = Task::query()->findOrFail($this->deletingTaskId);
        Gate::authorize('update', $task);
        $task->delete();

        $this->cancelDeleteTask();
        $this->dispatch('board-toast', type: 'success', message: 'Task berhasil dihapus.');
    }

    public function render()
    {
        $statuses = Task::workflowStatuses();

        $tasksQuery = Task::query()
            ->with('user')
            ->when(auth()->user()?->role === 'employee', fn ($query) => $query->where('assigned_to', auth()->id()))
            ->when($this->search !== '', function ($query) {
                $term = trim($this->search);
                $query->where(function ($inner) use ($term) {
                    $inner->where('judul', 'like', "%{$term}%")
                        ->orWhere('deskripsi', 'like', "%{$term}%");
                });
            });

        $tasks = $tasksQuery
            ->orderByDesc('priority')
            ->orderBy('diperbarui')
            ->get();

        $grouped = $tasks->groupBy('status_tugas');

        return view('livewire.workflow-board', [
            'statuses' => $statuses,
            'tasksByStatus' => $grouped,
            'users' => \App\Models\User::query()->select('id', 'name')->orderBy('name')->get(),
        ])->layout('layouts.dashboard');
    }

    private function resetCreateInputs(): void
    {
        $this->newTitle = '';
        $this->newDescription = '';
    }
}

