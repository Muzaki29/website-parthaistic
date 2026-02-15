<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tugas';

    protected $fillable = [
        'id_kartu_trello',
        'judul',
        'deskripsi',
        'notes',
        'status_tugas',
        'priority',
        'due_date',
        'dibuat',
        'diperbarui',
        'id_sinkron',
        'assigned_to',
    ];

    protected $casts = [
        'dibuat' => 'datetime',
        'diperbarui' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function syncLog()
    {
        return $this->belongsTo(SyncLog::class, 'id_sinkron');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }
}
