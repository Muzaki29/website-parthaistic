<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $table = 'statistik';

    protected $fillable = [
        'total_todo',
        'total_doing',
        'total_done',
        'diperbarui_pada',
        'id_user',
    ];

    protected $casts = [
        'diperbarui_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
