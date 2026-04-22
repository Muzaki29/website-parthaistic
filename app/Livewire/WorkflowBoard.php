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
        ])->layout('layouts.dashboard');
    }

    private function resetCreateInputs(): void
    {
        $this->newTitle = '';
        $this->newDescription = '';
    }
}

