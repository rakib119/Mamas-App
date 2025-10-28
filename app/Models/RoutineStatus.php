<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutineStatus extends Model
{
    protected $fillable = [
        'alarm_id',
        'user_id',
        'date',
        'completed',
        'alarm_id',
        'user_id'
    ];
}
