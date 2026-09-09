<?php

namespace App\Http\Controllers\records;

use App\Http\Controllers\Controller;
use App\Models\Commend;
use App\Models\Player;
use App\Models\Staff;
use App\Services\DiscordWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CommendController extends Controller
{
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    /**
     * Create a new commendation
     */
    public function createCommend(Request $request)
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

            $commend = new Commend();
            $commend->player_id = $request->player_id;
            $commend->reason = $request->reason;
            $commend->staff_id = $staff->staff_id;
            $commend->server_id = $staff->server_id;
            $commend->save();

            // Log to Discord webhook
            $this->webhookService->logCommendCreate(
                $player->last_player_name,
                $staff->staff_username,
                $request->reason,
                $staff->server->server_name ?? null
            );

            // Additional Laravel logging for audit trail
            Log::info('Commendation created', [
                'commend_id' => $commend->id,
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
                'message' => 'Commendation created successfully',
                'commend_id' => $commend->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create commendation: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all commendations
     */
    public function getCommends(Request $request)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $commends = Commend::with(['player', 'staff'])
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($commend) {
                    return [
                        'id' => $commend->id,
                        'player_name' => $commend->player->last_player_name ?? 'Unknown',
                        'reason' => $commend->reason,
                        'staff_username' => $commend->staff->staff_username ?? 'Unknown',
                        'created_at' => $commend->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'commends' => $commends
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve commendations: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get a specific commendation
     */
    public function getCommend($commend_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $commend = Commend::with(['player', 'staff'])
                ->where('id', $commend_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$commend) {
                return response()->json(['error' => 'Commendation not found'], 404);
            }

            return response()->json([
                'success' => true,
                'commend' => [
                    'id' => $commend->id,
                    'player_name' => $commend->player->last_player_name ?? 'Unknown',
                    'reason' => $commend->reason,
                    'staff_username' => $commend->staff->staff_username ?? 'Unknown',
                    'created_at' => $commend->created_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve commendation: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update a commendation
     */
    public function updateCommend(Request $request, $commend_id)
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

            $commend = Commend::where('id', $commend_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$commend) {
                return response()->json(['error' => 'Commendation not found'], 404);
            }

            $player = Player::find($commend->player_id);

            // Store old value for logging
            $oldReason = $commend->reason;

            // Update fields if provided
            if ($request->has('reason')) {
                $commend->reason = $request->reason;
            }

            $commend->save();

            // Log to Discord webhook
            $this->webhookService->logAction('commend_update', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'reason' => $commend->reason,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0xff8c00); // Dark orange for updates

            // Additional Laravel logging for audit trail
            Log::info('Commendation updated', [
                'commend_id' => $commend->id,
                'player_id' => $commend->player_id,
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'old_reason' => $oldReason,
                'new_reason' => $commend->reason,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Commendation updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update commendation: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a commendation
     */
    public function deleteCommend($commend_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $commend = Commend::where('id', $commend_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$commend) {
                return response()->json(['error' => 'Commendation not found'], 404);
            }

            $player = Player::find($commend->player_id);
            
            // Store commend details for logging before deletion
            $commendDetails = [
                'commend_id' => $commend->id,
                'player_id' => $commend->player_id,
                'player_name' => $player->last_player_name ?? 'Unknown',
                'reason' => $commend->reason
            ];
            
            $commend->delete();

            // Log to Discord webhook
            $this->webhookService->logAction('commend_delete', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0xff0000); // Red for deletions

            // Additional Laravel logging for audit trail
            Log::info('Commendation deleted', [
                'commend_id' => $commendDetails['commend_id'],
                'player_id' => $commendDetails['player_id'],
                'player_name' => $commendDetails['player_name'],
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'deleted_reason' => $commendDetails['reason'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Commendation deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete commendation: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get commendations by player
     */
    public function getCommendsByPlayer($player_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $commends = Commend::with(['player', 'staff'])
                ->where('player_id', $player_id)
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($commend) {
                    return [
                        'id' => $commend->id,
                        'player_name' => $commend->player->last_player_name ?? 'Unknown',
                        'reason' => $commend->reason,
                        'staff_username' => $commend->staff->staff_username ?? 'Unknown',
                        'created_at' => $commend->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'commends' => $commends
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve commendations: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get commendations by staff member
     */
    public function getCommendsByStaff($staff_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $commends = Commend::with(['player', 'staff'])
                ->where('staff_id', $staff_id)
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($commend) {
                    return [
                        'id' => $commend->id,
                        'player_name' => $commend->player->last_player_name ?? 'Unknown',
                        'reason' => $commend->reason,
                        'staff_username' => $commend->staff->staff_username ?? 'Unknown',
                        'created_at' => $commend->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'commends' => $commends
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve commendations: ' . $e->getMessage()], 500);
        }
    }
}
