<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayerController extends Controller {
    /**
     * GET methods
     */
    public function getPlayers(Request $request): array {
        return [];
    }
    public function getPlayerById(Request $request, $player_id): array {
        return [];
    }
    public function getPlayerIdFromLicense(Request $request, $game_license): array {
        return [];
    }
    public function getPlayerIdFromDiscord(Request $request, $discord_id): array {
        return [];
    }
    public function getPlayerIdFromSteamId(Request $request, $steam_id): array {
        return [];
    }
    public function getPlayerIdFromIP(Request $request): array {
        $ip = $request->input('ip');
        return [];
    }

    /**
     * POST methods
     */
    public function registerPlayer(Request $request): array {
        return [];
    }
    public function banPlayer(Request $request, $player_id): bool {
        $expires = $request->input('expires');
        $expiredDate = $request->input('expiredDate');
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('reason');
        return false;
    }
    public function kickPlayer(Request $request, $player_id): bool {
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('reason');
        return false;
    }
    public function commendPlayer(Request $request, $player_id): bool {
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('reason');
        return false;
    }
    public function notePlayer(Request $request, $player_id): bool {
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('note');
        return false;
    }
}
