<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Commend;
use App\Models\Kick;
use App\Models\Note;
use App\Models\Player;
use App\Models\PlayerData;
use App\Services\DiscordWebhookService;
use Illuminate\Http\Request;

class PlayerController extends Controller {
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    /**
     * GET methods
     */
    public function getPlayers(Request $request): array {
        return Player::all()->all();
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
        if ($saveSuccess) {
            // Put data into PlayerData
            $playerData = new PlayerData();
            $player_id = $player->player_id;
            $playerData->store($server_id, $player_id, 0, 0, 1, date("Y-m-d H:i:s"));
            $playerData->save();
            
            // Log to Discord webhook
            $this->webhookService->logAction('player_join', [
                'player_name' => $last_player_name,
                'server_id' => $server_id,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0x00bfff); // Deep sky blue for player actions
        }
        return $player->aggregate;
    }
    public function banPlayer(Request $request, $player_id): bool {
        $expires = $request->input('expires');
        $expiredDate = $request->input('expiredDate');
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('reason');
        
        // Get player and staff information for logging
        $player = Player::find($player_id);
        $staff = \App\Models\Staff::find($staff_id);
        
        $banPlayer = new Ban();
        $banPlayer->store($player_id, $reason, $staff_id, $expires, $expiredDate, $server_id);
        $saveSuccess = $banPlayer->save();
        
        if ($saveSuccess && $player && $staff) {
            // Log to Discord webhook
            $this->webhookService->logBanCreate(
                $player->last_player_name ?? 'Unknown',
                $staff->staff_username ?? 'Unknown',
                $reason,
                $expiredDate,
                $staff->server->server_name ?? null
            );
        }
        
        return $saveSuccess;
    }
    public function kickPlayer(Request $request, $player_id): bool {
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('reason');
        
        // Get player and staff information for logging
        $player = Player::find($player_id);
        $staff = \App\Models\Staff::find($staff_id);
        
        $kickPlayer = new Kick();
        $kickPlayer->store($player_id, $reason, $staff_id, $server_id);
        $saveSuccess = $kickPlayer->save();
        
        if ($saveSuccess && $player && $staff) {
            // Log to Discord webhook
            $this->webhookService->logKickCreate(
                $player->last_player_name ?? 'Unknown',
                $staff->staff_username ?? 'Unknown',
                $reason,
                $staff->server->server_name ?? null
            );
        }
        
        return $saveSuccess;
    }
    public function commendPlayer(Request $request, $player_id): bool {
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('reason');
        
        // Get player and staff information for logging
        $player = Player::find($player_id);
        $staff = \App\Models\Staff::find($staff_id);
        
        $commendPlayer = new Commend();
        $commendPlayer->store($player_id, $reason, $staff_id, $server_id);
        $saveSuccess = $commendPlayer->save();
        
        if ($saveSuccess && $player && $staff) {
            // Log to Discord webhook
            $this->webhookService->logCommendCreate(
                $player->last_player_name ?? 'Unknown',
                $staff->staff_username ?? 'Unknown',
                $reason,
                $staff->server->server_name ?? null
            );
        }
        
        return $saveSuccess;
    }
    public function notePlayer(Request $request, $player_id): bool {
        $server_id = $request->input('server_id');
        $staff_id = $request->input('staff_id');
        $reason = $request->input('note');
        
        // Get player and staff information for logging
        $player = Player::find($player_id);
        $staff = \App\Models\Staff::find($staff_id);
        
        $notePlayer = new Note();
        $notePlayer->store($player_id, $reason, $staff_id, $server_id);
        $saveSuccess = $notePlayer->save();
        
        if ($saveSuccess && $player && $staff) {
            // Log to Discord webhook
            $this->webhookService->logNoteCreate(
                $player->last_player_name ?? 'Unknown',
                $staff->staff_username ?? 'Unknown',
                $reason,
                $staff->server->server_name ?? null
            );
        }
        
        return $saveSuccess;
    }
}
