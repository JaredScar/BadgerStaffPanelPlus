<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
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
    public function store($server_id, $discord_id, $game_license, $steam_id, $live, $xbl, $ip, $last_player_name) {
        $this->server_id = $server_id;
        $this->discord_id = $discord_id;
        $this->game_license = $game_license;
        $this->steam_id = $steam_id;
        $this->live = $live;
        $this->xbl = $xbl;
        $this->ip = $ip;
        $this->last_player_name = $last_player_name;
    }

    public function getPlayerData() {
        return $this->hasOne(PlayerData::class, 'player_id');
    }
    public function kicks() {
        return $this->hasMany(Kick::class, 'player_id');
    }

    public function bans() {
        return $this->hasMany(Ban::class, 'player_id');
    }

    public function warns() {
        return $this->hasMany(Warn::class, 'player_id');
    }

    public function notes() {
        return $this->hasMany(Note::class, 'player_id');
    }

    public function commends() {
        return $this->hasMany(Commend::class, 'player_id');
    }

    public function getKickCount() {
        return $this->kicks()->count();
    }

    public function getBanCount() {
        return $this->bans()->count();
    }

    public function getWarnCount() {
        return $this->warns()->count();
    }

    public function getNoteCount() {
        return $this->notes()->count();
    }

    public function getCommendCount() {
        return $this->commends()->count();
    }
}
