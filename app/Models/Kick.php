<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class Kick extends Model {
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
    public function getStaffMember() {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    public function getPlayer() {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
