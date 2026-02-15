<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLog extends Model
{
    protected $table = 'sinkronisasi_api';

    protected $fillable = [
        'waktu_sinkron',
        'status',
        'keterangan',
        'id_user',
    ];

    protected $casts = [
        'waktu_sinkron' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
