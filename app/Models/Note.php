<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class Note extends Model {
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    public function store($player_id, $reason, $staff_id, $server_id) {
        $this->player_id = $player_id;
        $this->reason = $reason;
        $this->staff_id = $staff_id;
        $this->server_id = $server_id;
    }
    public function getStaff() {
        return $this->hasOne(Staff::class, 'staff_id');
    }
    public function getPlayer() {
        return $this->hasOne(Player::class, 'player_id');
    }
}
