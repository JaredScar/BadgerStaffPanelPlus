<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model {
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'player_id';
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';
}
