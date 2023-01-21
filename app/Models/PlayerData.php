<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerData extends Model {
    protected $table = 'player_data';
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';
}
