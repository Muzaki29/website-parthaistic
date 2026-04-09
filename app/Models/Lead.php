<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_QUALIFIED = 'qualified';

    public const STATUS_CLOSED_WON = 'closed_won';

    public const STATUS_CLOSED_LOST = 'closed_lost';

    public const STATUS_SPAM = 'spam';

    /** @return list<string> */
    public static function statuses(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_CONTACTED,
            self::STATUS_QUALIFIED,
            self::STATUS_CLOSED_WON,
            self::STATUS_CLOSED_LOST,
            self::STATUS_SPAM,
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'company',
        'project_brief',
        'source',
        'source_ip',
        'user_agent',
        'status',
        'notes',
        'assigned_to',
        'contacted_at',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'contacted_at' => 'datetime',
            'last_activity_at' => 'datetime',
        ];
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}

