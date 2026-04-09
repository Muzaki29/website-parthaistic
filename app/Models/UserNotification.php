<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'action_url',
        'action_label',
        'data',
        'source_key',
        'read_at',
        'dismissed_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
            'dismissed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVisible($query)
    {
        return $query->whereNull('dismissed_at');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
