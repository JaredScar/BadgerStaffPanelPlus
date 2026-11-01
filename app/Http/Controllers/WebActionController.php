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
use App\Services\DiscordWebhookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Throwable;

class WebActionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:warn,kick,ban,commend,note,trustscore',
            'player_id' => 'required|integer|exists:players,player_id',
            'reason' => 'nullable|string|max:255',
            'duration' => 'nullable|in:perm,1h,1d,7d,30d',
            'trust_score' => 'nullable|integer|min:0|max:100',
        ]);

        $staff = Staff::find(Auth::id());
        $player = Player::find($validated['player_id']);

        if (!$staff || !$player) {
            return redirect()->route('PLAYER_PROFILE', ['playerId' => $validated['player_id'] ?? 0])
                ->with('error', 'Staff or player not found.');
        }

        $serverId = $staff->server_id ?: Session::get('server_id');
        $now = now();
        $reason = trim((string) ($validated['reason'] ?? ''));

        try {
            match ($validated['action']) {
                'warn' => $this->insertRecord('warns', $player, $staff, $serverId, $reason ?: 'No reason provided', $now),
                'kick' => $this->insertRecord('kicks', $player, $staff, $serverId, $reason ?: 'No reason provided', $now),
                'commend' => $this->insertRecord('commends', $player, $staff, $serverId, $reason ?: 'No reason provided', $now),
                'note' => $this->insertNote($player, $staff, $serverId, $reason ?: 'Staff note', $now),
                'ban' => $this->insertBan($player, $staff, $serverId, $reason ?: 'No reason provided', $validated['duration'] ?? 'perm', $now),
                'trustscore' => $this->updateTrust($player, (int) ($validated['trust_score'] ?? 50)),
            };
        } catch (Throwable $e) {
            Log::error('Web staff action failed', [
                'error' => $e->getMessage(),
                'action' => $validated['action'],
                'player_id' => $player->player_id,
                'staff_id' => $staff->staff_id,
            ]);

            return redirect()->route('PLAYER_PROFILE', ['playerId' => $player->player_id])
                ->with('error', 'Could not save that action.');
        }

        $this->notify($validated['action'], $player, $staff, $reason, $validated['duration'] ?? null);

        $labels = [
            'warn' => 'Warning issued',
            'kick' => 'Kick recorded',
            'ban' => 'Ban recorded',
            'commend' => 'Commend recorded',
            'note' => 'Note saved',
            'trustscore' => 'Trust score updated',
        ];

        return redirect()->route('PLAYER_PROFILE', ['playerId' => $player->player_id])
            ->with('status', $labels[$validated['action']] . ' for ' . ($player->last_player_name ?: 'player') . '.');
    }

    public function revoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:ban,warn,kick,commend,note',
            'id' => 'required|integer|min:1',
        ]);

        $tables = [
            'ban' => 'bans',
            'warn' => 'warns',
            'kick' => 'kicks',
            'commend' => 'commends',
            'note' => 'notes',
        ];
        $labels = [
            'ban' => 'Ban lifted',
            'warn' => 'Warning removed',
            'kick' => 'Kick removed',
            'commend' => 'Commend removed',
            'note' => 'Note removed',
        ];

        $serverId = Session::get('server_id');
        if (!$serverId) {
            return back()->with('error', 'No server is selected.');
        }

        $table = $tables[$validated['type']];
        $row = DB::table($table)
            ->where('id', $validated['id'])
            ->where('server_id', $serverId)
            ->first();

        if (!$row) {
            return back()->with('error', 'That record is no longer on this server.');
        }

        DB::table($table)->where('id', $row->id)->where('server_id', $serverId)->delete();

        $player = Player::find($row->player_id);
        $staff = Staff::find(Auth::id());
        $this->notifyRemoved($validated['type'], $player, $staff);

        $name = $player->last_player_name ?? 'player';

        return back()->with('status', $labels[$validated['type']] . ' for ' . $name . '.');
    }

    private function insertRecord(string $table, Player $player, Staff $staff, mixed $serverId, string $reason, $now): void
    {
        DB::table($table)->insert([
            'player_id' => $player->player_id,
            'reason' => $reason,
            'staff_id' => $staff->staff_id,
            'server_id' => $serverId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function insertNote(Player $player, Staff $staff, mixed $serverId, string $note, $now): void
    {
        DB::table('notes')->insert([
            'player_id' => $player->player_id,
            'note' => $note,
            'staff_id' => $staff->staff_id,
            'server_id' => $serverId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function insertBan(Player $player, Staff $staff, mixed $serverId, string $reason, string $duration, $now): void
    {
        $expires = $duration === 'perm' ? 0 : 1;
        $expiredDate = match ($duration) {
            '1h' => $now->copy()->addHour(),
            '1d' => $now->copy()->addDay(),
            '7d' => $now->copy()->addDays(7),
            '30d' => $now->copy()->addDays(30),
            default => null,
        };

        DB::table('bans')->insert([
            'player_id' => $player->player_id,
            'reason' => $reason,
            'staff_id' => $staff->staff_id,
            'expires' => $expires,
            'expiredDate' => $expiredDate,
            'server_id' => $serverId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function updateTrust(Player $player, int $score): void
    {
        $exists = PlayerData::query()->where('player_id', $player->player_id)->exists();
        if ($exists) {
            DB::table('player_data')->where('player_id', $player->player_id)->update([
                'trust_score' => $score,
                'updated_at' => now(),
            ]);
            return;
        }

        DB::table('player_data')->insert([
            'server_id' => $player->server_id,
            'player_id' => $player->player_id,
            'playtime' => 0,
            'trust_score' => $score,
            'joins' => 0,
            'last_join_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function notifyRemoved(string $type, ?Player $player, ?Staff $staff): void
    {
        try {
            $webhook = new DiscordWebhookService(Session::get('server_id') ?: 1);
            $webhook->logAction($type . '_delete', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username ?? 'Staff',
                'server_name' => optional($staff?->server)->server_name ?? Session::get('server_name'),
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ], 0x22c55e);
        } catch (Throwable $e) {
            // Webhooks are optional; the record is already gone.
        }
    }

    private function notify(string $action, Player $player, Staff $staff, string $reason, ?string $duration): void
    {
        try {
            $webhook = new DiscordWebhookService();
            $serverName = optional($staff->server)->server_name;
            $name = $player->last_player_name ?: 'Unknown';
            $staffName = $staff->staff_username;

            match ($action) {
                'warn' => $webhook->logWarnCreate($name, $staffName, $reason, $serverName),
                'kick' => $webhook->logKickCreate($name, $staffName, $reason, $serverName),
                'commend' => $webhook->logCommendCreate($name, $staffName, $reason, $serverName),
                'note' => $webhook->logNoteCreate($name, $staffName, $reason, $serverName),
                'ban' => $webhook->logBanCreate($name, $staffName, $reason, $duration, $serverName),
                default => null,
            };
        } catch (Throwable $e) {
            // Webhooks are optional; the panel action already saved.
        }
    }
}
