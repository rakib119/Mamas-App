<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KickCountMst extends Model
{
    protected $table = 'kick_count_mst';
    protected $fillable = [
        'user_id',
        'total_kick',
    ];
}
