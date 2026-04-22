<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public const STATUS_DROP_IDEA = 'Drop idea';
    public const STATUS_SCRIPT_IDEA = 'Script idea';
    public const STATUS_SCRIPT_WRITTEN = 'Script written';
    public const STATUS_SCRIPT_PREVIEW = 'Script preview';
    public const STATUS_CREW_CALL_SHOOTING = 'Crew call shooting';
    public const STATUS_PRODUCTION = 'Production';
    public const STATUS_POST_PRODUCTION = 'Post - Production';
    public const STATUS_PREVIEW = 'Preview';
    public const STATUS_FINISHED = 'Finished';

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

    public static function workflowStatuses(): array
    {
        return [
            self::STATUS_DROP_IDEA,
            self::STATUS_SCRIPT_IDEA,
            self::STATUS_SCRIPT_WRITTEN,
            self::STATUS_SCRIPT_PREVIEW,
            self::STATUS_CREW_CALL_SHOOTING,
            self::STATUS_PRODUCTION,
            self::STATUS_POST_PRODUCTION,
            self::STATUS_PREVIEW,
            self::STATUS_FINISHED,
        ];
    }
}
