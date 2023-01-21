<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model {
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    public function getStaffMember() {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    public function getPlayer() {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
