<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Commend;
use App\Models\Kick;
use App\Models\Note;
use App\Models\Player;
use App\Models\PlayerData;
use App\Models\Staff;
use App\Models\Warn;
use App\Support\PanelActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PlayerProfileController extends Controller
{
    public function show(int $playerId): View
    {
        $serverId = Session::get('server_id');
        $player = Player::query()
            ->when($serverId, fn ($query) => $query->where('server_id', $serverId))
            ->where('player_id', $playerId)
            ->firstOrFail();

        $playerData = PlayerData::query()->where('player_id', $player->player_id)->first();
        $staffNames = Staff::query()->pluck('staff_username', 'staff_id');

        $warns = Warn::query()->where('player_id', $player->player_id)->orderByDesc('created_at')->get();
        $kicks = Kick::query()->where('player_id', $player->player_id)->orderByDesc('created_at')->get();
        $bans = Ban::query()->where('player_id', $player->player_id)->orderByDesc('created_at')->get();
        $commends = Commend::query()->where('player_id', $player->player_id)->orderByDesc('created_at')->get();
        $notes = Note::query()->where('player_id', $player->player_id)->orderByDesc('created_at')->get();

        $data = [
            'css_path' => 'players',
            'view_name' => 'PLAYER_PROFILE',
            'customize' => false,
        ];

        return view('verified.players.profile', [
            'data' => $data,
            'player' => $player,
            'playerData' => $playerData,
            'staffNames' => $staffNames,
            'warns' => $warns,
            'kicks' => $kicks,
            'bans' => $bans,
            'commends' => $commends,
            'notes' => $notes,
            'activity' => PanelActivity::recent($serverId ? (int) $serverId : null, $player->player_id, 20),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));
        $serverId = Session::get('server_id');

        $players = Player::query()
            ->when($serverId, fn ($query) => $query->where('server_id', $serverId))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('last_player_name', 'like', '%' . $q . '%')
                        ->orWhere('game_license', 'like', '%' . $q . '%')
                        ->orWhere('steam_id', 'like', '%' . $q . '%')
                        ->orWhere('discord_id', 'like', '%' . $q . '%')
                        ->orWhere('ip', 'like', '%' . $q . '%')
                        ->orWhere('player_id', $q);
                });
            })
            ->orderBy('last_player_name')
            ->limit(12)
            ->get(['player_id', 'last_player_name', 'discord_id', 'game_license']);

        return response()->json([
            'success' => true,
            'players' => $players,
        ]);
    }
}
