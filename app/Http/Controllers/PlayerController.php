<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Commend;
use App\Models\Kick;
use App\Models\Note;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller {
    /**
     * GET methods
     */
    public function getPlayers(Request $request): array {
        return [];
    }
    public function getPlayerById(Request $request, $player_id): array {
        return Player::where('player_id', $player_id)->first()->aggregate;
    }
    public function getPlayerIdFromLicense(Request $request, $game_license): array {
        return Player::where('game_license', $game_license)->first()->aggregate;
    }
    public function getPlayerIdFromDiscord(Request $request, $discord_id): array {
        return Player::where('discord_id', $discord_id)->first()->aggregate;
    }
    public function getPlayerIdFromSteamId(Request $request, $steam_id): array {
        return Player::where('steam_id', $steam_id)->first()->aggregate;
    }
    public function getPlayerIdFromIP(Request $request): array {
        $ip = $request->input('ip');
        return Player::where('ip', $ip)->first()->aggregate;
    }

    /**
     * POST methods
     */
    public function registerPlayer(Request $request): array {
        $server_id = $request->input('server_id');
        $discord_id = $request->input('discord_id');
        $license = $request->input('game_license');
        $steam_id = $request->input('steam_id');
        $live = $request->input('live');
        $xbl = $request->input('xbl');
        $ip = $request->input('ip');
        $last_player_name = $request->input('last_player_name');
        $player = new Player();
        $player->store($server_id, $discord_id, $license, $steam_id, $live, $xbl, $ip, $last_player_name);
        $saveSuccess = $player->save();
        return $player->aggregate;
    }
    public function banPlayer(Request $request, $player_id): bool {
        $expires = $request->input('expires');
        $expiredDate = $request->input('expiredDate');
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('reason');
        $banPlayer = new Ban();
        $banPlayer->store($player_id, $reason, $staff_id, $expires, $expiredDate, $server_id);
        return $banPlayer->save();
    }
    public function kickPlayer(Request $request, $player_id): bool {
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('reason');
        $kickPlayer = new Kick();
        $kickPlayer->store($player_id, $reason, $staff_id, $server_id);
        return $kickPlayer->save();
    }
    public function commendPlayer(Request $request, $player_id): bool {
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('reason');
        $commendPlayer = new Commend();
        $commendPlayer->store($player_id, $reason, $staff_id, $server_id);
        return $commendPlayer->save();
    }
    public function notePlayer(Request $request, $player_id): bool {
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('note');
        $notePlayer = new Note();
        $notePlayer->store($player_id, $reason, $staff_id, $server_id);
        return $notePlayer->save();
    }
}
