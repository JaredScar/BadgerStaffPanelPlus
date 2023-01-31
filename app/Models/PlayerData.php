<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class PlayerData extends Model {
    protected $table = 'player_data';
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';
    public function store($server_id, $player_id, $playtime, $trust_score, $joins, $last_join_date) {
        $this->server_id = $server_id;
        $this->player_id = $player_id;
        $this->playtime = $playtime;
        $this->trust_score = $trust_score;
        $this->joins = $joins;
        $this->last_join_date = $last_join_date;
    }
}
