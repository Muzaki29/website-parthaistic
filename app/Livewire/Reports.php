<?php

namespace App\Livewire;

use App\Exports\TasksExport;
use App\Models\Task;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Reports extends Component
{
    use WithPagination;

    public $startDate;

    public $endDate;

    public $userId;

    public $status;

    public $priority;

    public $search = '';

    public $selectedTasks = [];

    public $selectAll = false;

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
        $role = auth()->user()?->role;
        if (! in_array($role, ['admin', 'manager'], true)) {
            abort(403, 'Anda tidak memiliki izin untuk aksi massal.');
        }
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

    public function render()
    {
        $tasks = $this->getTasksQuery()->orderBy('diperbarui', 'desc')->paginate(10);
        $users = User::all();

        return view('livewire.reports', [
            'tasks' => $tasks,
            'users' => $users,
        ])->layout('layouts.dashboard');
    }
}
