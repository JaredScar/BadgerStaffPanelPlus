<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    protected $table = 'watchlist';

    protected $fillable = [
        'server_id',
        'player_id',
        'staff_id',
        'reason',
    ];
}
