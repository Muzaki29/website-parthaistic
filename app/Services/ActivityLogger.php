<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogger
{
    public static function log(
        string $eventType,
        ?User $user = null,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?array $meta = null,
        ?Request $request = null,
    ): ActivityLog {
        $req = $request ?? request();

        return ActivityLog::create([
            'user_id' => $user?->id,
            'event_type' => $eventType,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'meta' => $meta,
            'ip_address' => $req?->ip(),
            'user_agent' => $req ? substr((string) $req->userAgent(), 0, 2000) : null,
        ]);
    }
}
