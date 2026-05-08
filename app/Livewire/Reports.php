<?php

namespace App\Livewire;

use App\Exports\TasksExport;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Reports extends Component
{
    use WithPagination;

    public $startDate;

    public $endDate;

    #[Url(as: 'userId', except: '')]
    public $userId;

    public $status;

    public $priority;

    public $search = '';

    public $selectedTasks = [];

    public $selectAll = false;

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingTaskId = null;

    public ?int $deletingTaskId = null;

    public string $formTitle = '';

    public string $formDescription = '';

    public string $formStatus = '';

    public string $formPriority = 'medium';

    public ?string $formDueDate = null;

    public $formAssignedTo = '';

    public function updated($propertyName)
    {
        if ($propertyName === 'selectAll') {
            if ($this->selectAll) {
                $this->selectedTasks = $this->getTasksQuery()->pluck('id')->toArray();
            } else {
                $this->selectedTasks = [];
            }
        } else {
            $this->resetPage();
            $this->selectAll = false;
            $this->selectedTasks = [];
        }
    }

    public function bulkUpdateStatus($status)
    {
        $this->authorizeBulkActions();

        if (! in_array($status, Task::workflowStatuses(), true)) {
            session()->flash('error', 'Status workflow tidak valid.');

            return;
        }

        if (empty($this->selectedTasks)) {
            session()->flash('error', 'Please select at least one task.');

            return;
        }

        Task::whereIn('id', $this->selectedTasks)->update([
            'status_tugas' => $status,
            'diperbarui' => now(),
        ]);

        session()->flash('success', count($this->selectedTasks).' task(s) updated successfully.');
        $this->selectedTasks = [];
        $this->selectAll = false;
    }

    public function bulkDelete()
    {
        $this->authorizeBulkActions();

        if (empty($this->selectedTasks)) {
            session()->flash('error', 'Please select at least one task.');

            return;
        }

        Task::whereIn('id', $this->selectedTasks)->delete();
        session()->flash('success', count($this->selectedTasks).' task(s) deleted successfully.');
        $this->selectedTasks = [];
        $this->selectAll = false;
    }

    protected function getTasksQuery()
    {
        $query = Task::with('user');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('diperbarui', [$this->startDate, $this->endDate]);
        }

        if ($this->userId) {
            $query->where('assigned_to', $this->userId);
        }

        if ($this->status) {
            $query->where('status_tugas', $this->status);
        }

        if ($this->priority) {
            $query->where('priority', $this->priority);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%'.$this->search.'%')
                    ->orWhere('deskripsi', 'like', '%'.$this->search.'%');
            });
        }

        return $query;
    }

    protected function authorizeBulkActions(): void
    {
        Gate::authorize('bulkManage', Task::class);
    }

    public function export()
    {
        return Excel::download(new TasksExport(
            $this->startDate,
            $this->endDate,
            $this->userId,
            $this->status
        ), 'laporan-aktivitas.xlsx');
    }

    public function openCreateModal(): void
    {
        Gate::authorize('create', Task::class);
        $this->resetTaskForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetErrorBag();
    }

    public function createTask(): void
    {
        Gate::authorize('create', Task::class);
        $validated = $this->validateTaskForm();

        Task::create([
            'id_kartu_trello' => 'local-'.\Illuminate\Support\Str::uuid(),
            'judul' => trim($validated['formTitle']),
            'deskripsi' => trim($validated['formDescription']) ?: null,
            'notes' => null,
            'status_tugas' => $validated['formStatus'],
            'priority' => $validated['formPriority'],
            'due_date' => $validated['formDueDate'] ? date('Y-m-d H:i:s', strtotime($validated['formDueDate'])) : null,
            'dibuat' => now(),
            'diperbarui' => now(),
            'id_sinkron' => null,
            'assigned_to' => $validated['formAssignedTo'] !== '' ? $validated['formAssignedTo'] : null,
        ]);

        $this->closeCreateModal();
        $this->resetTaskForm();
        session()->flash('success', 'Task created successfully.');
    }

    public function openEditModal(int $taskId): void
    {
        $task = Task::query()->findOrFail($taskId);
        Gate::authorize('update', $task);

        $this->editingTaskId = $task->id;
        $this->formTitle = $task->judul;
        $this->formDescription = $task->deskripsi ?? '';
        $this->formStatus = $task->status_tugas;
        $this->formPriority = $task->priority ?? 'medium';
        $this->formDueDate = $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : null;
        $this->formAssignedTo = $task->assigned_to ?? '';
        $this->showEditModal = true;
        $this->resetErrorBag();
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingTaskId = null;
        $this->resetErrorBag();
    }

    public function updateTaskFromModal(): void
    {
        if (! $this->editingTaskId) {
            return;
        }

        $task = Task::query()->findOrFail($this->editingTaskId);
        Gate::authorize('update', $task);
        $validated = $this->validateTaskForm();

        $task->update([
            'judul' => trim($validated['formTitle']),
            'deskripsi' => trim($validated['formDescription']) ?: null,
            'status_tugas' => $validated['formStatus'],
            'priority' => $validated['formPriority'],
            'due_date' => $validated['formDueDate'] ? date('Y-m-d H:i:s', strtotime($validated['formDueDate'])) : null,
            'assigned_to' => $validated['formAssignedTo'] !== '' ? $validated['formAssignedTo'] : null,
            'diperbarui' => now(),
        ]);

        $this->closeEditModal();
        session()->flash('success', 'Task updated successfully.');
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

    public function deleteTaskFromModal(): void
    {
        if (! $this->deletingTaskId) {
            return;
        }

        $task = Task::query()->findOrFail($this->deletingTaskId);
        Gate::authorize('update', $task);
        $task->delete();

        $this->cancelDeleteTask();
        session()->flash('success', 'Task deleted successfully.');
    }

    protected function resetTaskForm(): void
    {
        $this->formTitle = '';
        $this->formDescription = '';
        $this->formStatus = Task::workflowStatuses()[0] ?? Task::STATUS_DROP_IDEA;
        $this->formPriority = 'medium';
        $this->formDueDate = null;
        $this->formAssignedTo = '';
    }

    protected function validateTaskForm(): array
    {
        if ($this->formAssignedTo === '') {
            $this->formAssignedTo = null;
        }

        return $this->validate([
            'formTitle' => 'required|string|max:255',
            'formDescription' => 'nullable|string|max:5000',
            'formStatus' => 'required|in:'.implode(',', Task::workflowStatuses()),
            'formPriority' => 'required|in:low,medium,high,urgent',
            'formDueDate' => 'nullable|date',
            'formAssignedTo' => 'nullable|integer|exists:users,id',
        ]);
    }

    public function render()
    {
        $tasks = $this->getTasksQuery()->orderBy('diperbarui', 'desc')->paginate(10);
        $users = User::all();

        return view('livewire.reports', [
            'tasks' => $tasks,
            'users' => $users,
            'statuses' => Task::workflowStatuses(),
            'canCreateTask' => Gate::allows('create', Task::class),
        ])->layout('layouts.dashboard');
    }
}
