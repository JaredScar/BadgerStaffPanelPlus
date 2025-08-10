<?php

namespace App\Http\Controllers\records;

use App\Http\Controllers\Controller;
use App\Models\Kick;
use App\Models\Player;
use App\Models\Staff;
use App\Services\DiscordWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KickController extends Controller
{
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    /**
     * Create a new kick
     */
    public function createKick(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|integer|exists:players,player_id',
            'reason' => 'required|string|max:255'
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

            $kick = new Kick();
            $kick->player_id = $request->player_id;
            $kick->reason = $request->reason;
            $kick->staff_id = $staff->staff_id;
            $kick->server_id = $staff->server_id;
            $kick->save();

            // Log to Discord webhook
            $this->webhookService->logKickCreate(
                $player->last_player_name,
                $staff->staff_username,
                $request->reason,
                $staff->server->server_name ?? null
            );

            // Additional Laravel logging for audit trail
            Log::info('Kick created', [
                'kick_id' => $kick->id,
                'player_id' => $request->player_id,
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'reason' => $request->reason,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kick created successfully',
                'kick_id' => $kick->id
            ]);

        } catch (\Exception $e) {
            // Log error with detailed information
            Log::error('Failed to create kick', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'player_id' => $request->player_id,
                'staff_id' => Auth::id(),
                'reason' => $request->reason,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['error' => 'Failed to create kick: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all kicks
     */
    public function getKicks(Request $request)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $kicks = Kick::with(['player', 'staff'])
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($kick) {
                    return [
                        'id' => $kick->id,
                        'player_name' => $kick->player->last_player_name ?? 'Unknown',
                        'reason' => $kick->reason,
                        'staff_username' => $kick->staff->staff_username ?? 'Unknown',
                        'created_at' => $kick->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'kicks' => $kicks
            ]);

        } catch (\Exception $e) {
            // Log error with detailed information
            Log::error('Failed to retrieve kicks', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'staff_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['error' => 'Failed to retrieve kicks: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get a specific kick
     */
    public function getKick($kick_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $kick = Kick::with(['player', 'staff'])
                ->where('id', $kick_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$kick) {
                return response()->json(['error' => 'Kick not found'], 404);
            }

            return response()->json([
                'success' => true,
                'kick' => [
                    'id' => $kick->id,
                    'player_name' => $kick->player->last_player_name ?? 'Unknown',
                    'reason' => $kick->reason,
                    'staff_username' => $kick->staff->staff_username ?? 'Unknown',
                    'created_at' => $kick->created_at
                ]
            ]);

        } catch (\Exception $e) {
            // Log error with detailed information
            Log::error('Failed to retrieve kick', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'kick_id' => $kick_id,
                'staff_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['error' => 'Failed to retrieve kick: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update a kick
     */
    public function updateKick(Request $request, $kick_id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'sometimes|required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $kick = Kick::where('id', $kick_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$kick) {
                return response()->json(['error' => 'Kick not found'], 404);
            }

            $player = Player::find($kick->player_id);

            // Store old value for logging
            $oldReason = $kick->reason;

            // Update fields if provided
            if ($request->has('reason')) {
                $kick->reason = $request->reason;
            }

            $kick->save();

            // Log to Discord webhook
            $this->webhookService->logAction('kick_update', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'reason' => $kick->reason,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0xff8c00); // Dark orange for updates

            // Additional Laravel logging for audit trail
            Log::info('Kick updated', [
                'kick_id' => $kick->id,
                'player_id' => $kick->player_id,
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'old_reason' => $oldReason,
                'new_reason' => $kick->reason,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kick updated successfully'
            ]);

        } catch (\Exception $e) {
            // Log error with detailed information
            Log::error('Failed to update kick', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'kick_id' => $kick_id,
                'staff_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['error' => 'Failed to update kick: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a kick
     */
    public function deleteKick($kick_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $kick = Kick::where('id', $kick_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$kick) {
                return response()->json(['error' => 'Kick not found'], 404);
            }

            $player = Player::find($kick->player_id);
            
            // Store kick details for logging before deletion
            $kickDetails = [
                'kick_id' => $kick->id,
                'player_id' => $kick->player_id,
                'player_name' => $player->last_player_name ?? 'Unknown',
                'reason' => $kick->reason
            ];
            
            $kick->delete();

            // Log to Discord webhook
            $this->webhookService->logAction('kick_delete', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0xff0000); // Red for deletions

            // Additional Laravel logging for audit trail
            Log::info('Kick deleted', [
                'kick_id' => $kickDetails['kick_id'],
                'player_id' => $kickDetails['player_id'],
                'player_name' => $kickDetails['player_name'],
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'deleted_reason' => $kickDetails['reason'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kick deleted successfully'
            ]);

        } catch (\Exception $e) {
            // Log error with detailed information
            Log::error('Failed to delete kick', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'kick_id' => $kick_id,
                'staff_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['error' => 'Failed to delete kick: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get kicks by player
     */
    public function getKicksByPlayer($player_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $kicks = Kick::with(['player', 'staff'])
                ->where('player_id', $player_id)
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($kick) {
                    return [
                        'id' => $kick->id,
                        'player_name' => $kick->player->last_player_name ?? 'Unknown',
                        'reason' => $kick->reason,
                        'staff_username' => $kick->staff->staff_username ?? 'Unknown',
                        'created_at' => $kick->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'kicks' => $kicks
            ]);

        } catch (\Exception $e) {
            // Log error with detailed information
            Log::error('Failed to retrieve kicks by player', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'player_id' => $player_id,
                'staff_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['error' => 'Failed to retrieve kicks: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get kicks by staff member
     */
    public function getKicksByStaff($staff_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $kicks = Kick::with(['player', 'staff'])
                ->where('staff_id', $staff_id)
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($kick) {
                    return [
                        'id' => $kick->id,
                        'player_name' => $kick->player->last_player_name ?? 'Unknown',
                        'reason' => $kick->reason,
                        'staff_username' => $kick->staff->staff_username ?? 'Unknown',
                        'created_at' => $kick->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'kicks' => $kicks
            ]);

        } catch (\Exception $e) {
            // Log error with detailed information
            Log::error('Failed to retrieve kicks by staff', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'target_staff_id' => $staff_id,
                'current_staff_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['error' => 'Failed to retrieve kicks: ' . $e->getMessage()], 500);
        }
    }
}
