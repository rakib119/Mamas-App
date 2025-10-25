<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyRoutine extends Model
{
    protected $fillable = [
        'user_id',
        'time',
        'label',
        'days',
        'remHour',
        'remMin',
        'enabled'
    ];

    protected $casts = [
        'days' => 'array',
    ];
}
