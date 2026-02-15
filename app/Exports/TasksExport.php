<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TasksExport implements FromQuery, WithHeadings, WithMapping
{
    protected $startDate;

    protected $endDate;

    protected $userId;

    protected $status;

    public function __construct($startDate, $endDate, $userId, $status)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->userId = $userId;
        $this->status = $status;
    }

    public function query()
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

        return $query->orderBy('diperbarui', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul Tugas',
            'Deskripsi',
            'Status',
            'Ditugaskan Ke',
            'Email',
            'Tanggal Dibuat',
            'Terakhir Diperbarui',
        ];
    }

    public function map($task): array
    {
        return [
            $task->id,
            $task->judul,
            $task->deskripsi,
            $task->status_tugas,
            $task->user ? $task->user->name : 'Unassigned',
            $task->user ? $task->user->email : '-',
            $task->dibuat->format('Y-m-d H:i:s'),
            $task->diperbarui->format('Y-m-d H:i:s'),
        ];
    }
}
