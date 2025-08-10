<?php

namespace App\Http\Controllers\records;

use App\Http\Controllers\Controller;
use App\Models\Ban;
use App\Models\Player;
use App\Models\Staff;
use App\Services\DiscordWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BanController extends Controller
{
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    /**
     * Create a new ban
     */
    public function createBan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|integer|exists:players,player_id',
            'reason' => 'required|string|max:255',
            'expires' => 'nullable|integer|min:0',
            'expiredDate' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $staff = Staff::find(Auth::id());
            $player = Player::find($request->player_id);
            
            if (!$staff || !$player) {
                return response()->json(['error' => 'Staff or player not found'], 404);
            }

            $ban = new Ban();
            $ban->player_id = $request->player_id;
            $ban->reason = $request->reason;
            $ban->staff_id = $staff->staff_id;
            $ban->expires = $request->expires ?? 0;
            $ban->expiredDate = $request->expiredDate;
            $ban->server_id = $staff->server_id;
            $ban->save();

            // Log to Discord webhook
            $this->webhookService->logBanCreate(
                $player->last_player_name,
                $staff->staff_username,
                $request->reason,
                $request->expiredDate,
                $staff->server->server_name ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Ban created successfully',
                'ban_id' => $ban->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create ban: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all bans
     */
    public function getBans(Request $request)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $bans = Ban::with(['player', 'staff'])
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($ban) {
                    return [
                        'id' => $ban->id,
                        'player_name' => $ban->player->last_player_name ?? 'Unknown',
                        'reason' => $ban->reason,
                        'staff_username' => $ban->staff->staff_username ?? 'Unknown',
                        'expires' => $ban->expires,
                        'expiredDate' => $ban->expiredDate,
                        'created_at' => $ban->created_at,
                        'is_expired' => $ban->expiredDate && now()->gt($ban->expiredDate)
                    ];
                });

            return response()->json([
                'success' => true,
                'bans' => $bans
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch bans: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get a specific ban
     */
    public function getBan($ban_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $ban = Ban::with(['player', 'staff'])
                ->where('id', $ban_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$ban) {
                return response()->json(['error' => 'Ban not found'], 404);
            }

            return response()->json([
                'success' => true,
                'ban' => [
                    'id' => $ban->id,
                    'player_name' => $ban->player->last_player_name ?? 'Unknown',
                    'reason' => $ban->reason,
                    'staff_username' => $ban->staff->staff_username ?? 'Unknown',
                    'expires' => $ban->expires,
                    'expiredDate' => $ban->expiredDate,
                    'created_at' => $ban->created_at,
                    'is_expired' => $ban->expiredDate && now()->gt($ban->expiredDate)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch ban: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update a ban
     */
    public function updateBan(Request $request, $ban_id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'sometimes|required|string|max:255',
            'expires' => 'sometimes|nullable|integer|min:0',
            'expiredDate' => 'sometimes|nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $ban = Ban::where('id', $ban_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$ban) {
                return response()->json(['error' => 'Ban not found'], 404);
            }

            $player = Player::find($ban->player_id);

            // Update fields if provided
            if ($request->has('reason')) {
                $ban->reason = $request->reason;
            }
            if ($request->has('expires')) {
                $ban->expires = $request->expires;
            }
            if ($request->has('expiredDate')) {
                $ban->expiredDate = $request->expiredDate;
            }

            $ban->save();

            // Log to Discord webhook
            $this->webhookService->logAction('ban_update', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'reason' => $ban->reason,
                'expires' => $ban->expiredDate,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0xff8c00); // Dark orange for updates

            return response()->json([
                'success' => true,
                'message' => 'Ban updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update ban: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a ban
     */
    public function deleteBan($ban_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $ban = Ban::where('id', $ban_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$ban) {
                return response()->json(['error' => 'Ban not found'], 404);
            }

            $player = Player::find($ban->player_id);
            $ban->delete();

            // Log to Discord webhook
            $this->webhookService->logBanDelete(
                $player->last_player_name ?? 'Unknown',
                $staff->staff_username,
                $staff->server->server_name ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Ban deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete ban: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get bans by player
     */
    public function getBansByPlayer($player_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $bans = Ban::with(['player', 'staff'])
                ->where('player_id', $player_id)
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($ban) {
                    return [
                        'id' => $ban->id,
                        'reason' => $ban->reason,
                        'staff_username' => $ban->staff->staff_username ?? 'Unknown',
                        'expires' => $ban->expires,
                        'expiredDate' => $ban->expiredDate,
                        'created_at' => $ban->created_at,
                        'is_expired' => $ban->expiredDate && now()->gt($ban->expiredDate)
                    ];
                });

            return response()->json([
                'success' => true,
                'bans' => $bans
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch player bans: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get bans by staff member
     */
    public function getBansByStaff($staff_id)
    {
        try {
            $currentStaff = Staff::find(Auth::id());
            if (!$currentStaff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $bans = Ban::with(['player', 'staff'])
                ->where('staff_id', $staff_id)
                ->where('server_id', $currentStaff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($ban) {
                    return [
                        'id' => $ban->id,
                        'player_name' => $ban->player->last_player_name ?? 'Unknown',
                        'reason' => $ban->reason,
                        'expires' => $ban->expires,
                        'expiredDate' => $ban->expiredDate,
                        'created_at' => $ban->created_at,
                        'is_expired' => $ban->expiredDate && now()->gt($ban->expiredDate)
                    ];
                });

            return response()->json([
                'success' => true,
                'bans' => $bans
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch staff bans: ' . $e->getMessage()], 500);
        }
    }
}
