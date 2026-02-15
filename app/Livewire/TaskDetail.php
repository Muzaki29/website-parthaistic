<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaskDetail extends Component
{
    use WithFileUploads;

    public $task;

    public $taskId;

    public $showModal = false;

    public $editing = false;

    // Edit fields
    public $judul;

    public $deskripsi;

    public $notes;

    public $status_tugas;

    public $priority;

    public $due_date;

    public $assigned_to;

    // File upload
    public $files = [];

    public $uploadedFiles = [];

    public function mount($id)
    {
        $this->taskId = $id;
        $this->loadTask();
    }

    public function loadTask()
    {
        $this->task = Task::with(['user', 'files.uploader'])->findOrFail($this->taskId);
        $this->judul = $this->task->judul;
        $this->deskripsi = $this->task->deskripsi;
        $this->notes = $this->task->notes;
        $this->status_tugas = $this->task->status_tugas;
        $this->priority = $this->task->priority ?? 'medium';
        $this->due_date = $this->task->due_date ? $this->task->due_date->format('Y-m-d\TH:i') : null;
        $this->assigned_to = $this->task->assigned_to;
        $this->uploadedFiles = $this->task->files;
    }

    public function toggleEdit()
    {
        $this->editing = ! $this->editing;
        if (! $this->editing) {
            $this->loadTask();
        }
    }

    public function updateTask()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'notes' => 'nullable|string',
            'status_tugas' => 'required|in:To Do,Doing,Done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $this->task->update([
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'notes' => $this->notes,
            'status_tugas' => $this->status_tugas,
            'priority' => $this->priority,
            'due_date' => $this->due_date ? date('Y-m-d H:i:s', strtotime($this->due_date)) : null,
            'assigned_to' => $this->assigned_to,
            'diperbarui' => now(),
        ]);

        session()->flash('success', 'Task updated successfully!');
        $this->editing = false;
        $this->loadTask();
    }

    public function uploadFiles()
    {
        $this->validate([
            'files.*' => 'file|max:10240', // 10MB max
        ]);

        foreach ($this->files as $file) {
            $path = $file->store('task-files', 'public');

            TaskFile::create([
                'task_id' => $this->task->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
        }

        $this->files = [];
        session()->flash('success', 'Files uploaded successfully!');
        $this->loadTask();
    }

    public function deleteFile($fileId)
    {
        $file = TaskFile::findOrFail($fileId);
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        $file->delete();

        session()->flash('success', 'File deleted successfully!');
        $this->loadTask();
    }

    public function render()
    {
        return view('livewire.task-detail', [
            'users' => \App\Models\User::all(),
        ]);
    }
}
