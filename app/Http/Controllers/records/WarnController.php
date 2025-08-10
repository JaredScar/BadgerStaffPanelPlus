<?php

namespace App\Http\Controllers\records;

use App\Http\Controllers\Controller;
use App\Models\Warn;
use App\Models\Player;
use App\Models\Staff;
use App\Services\DiscordWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WarnController extends Controller
{
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    /**
     * Create a new warning
     */
    public function createWarn(Request $request)
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

            $warn = new Warn();
            $warn->player_id = $request->player_id;
            $warn->reason = $request->reason;
            $warn->staff_id = $staff->staff_id;
            $warn->server_id = $staff->server_id;
            $warn->save();

            // Log to Discord webhook
            $this->webhookService->logWarnCreate(
                $player->last_player_name,
                $staff->staff_username,
                $request->reason,
                $staff->server->server_name ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Warning created successfully',
                'warn_id' => $warn->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create warning: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all warnings
     */
    public function getWarns(Request $request)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $warns = Warn::with(['player', 'staff'])
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($warn) {
                    return [
                        'id' => $warn->id,
                        'player_name' => $warn->player->last_player_name ?? 'Unknown',
                        'reason' => $warn->reason,
                        'staff_username' => $warn->staff->staff_username ?? 'Unknown',
                        'created_at' => $warn->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'warns' => $warns
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch warnings: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get a specific warning
     */
    public function getWarn($warn_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $warn = Warn::with(['player', 'staff'])
                ->where('id', $warn_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$warn) {
                return response()->json(['error' => 'Warning not found'], 404);
            }

            return response()->json([
                'success' => true,
                'warn' => [
                    'id' => $warn->id,
                    'player_name' => $warn->player->last_player_name ?? 'Unknown',
                    'reason' => $warn->reason,
                    'staff_username' => $warn->staff->staff_username ?? 'Unknown',
                    'created_at' => $warn->created_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch warning: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update a warning
     */
    public function updateWarn(Request $request, $warn_id)
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

            $warn = Warn::where('id', $warn_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$warn) {
                return response()->json(['error' => 'Warning not found'], 404);
            }

            $player = Player::find($warn->player_id);

            // Update fields if provided
            if ($request->has('reason')) {
                $warn->reason = $request->reason;
            }

            $warn->save();

            // Log to Discord webhook
            $this->webhookService->logAction('warn_update', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'reason' => $warn->reason,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0xffa500); // Orange for warning updates

            return response()->json([
                'success' => true,
                'message' => 'Warning updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update warning: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a warning
     */
    public function deleteWarn($warn_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $warn = Warn::where('id', $warn_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$warn) {
                return response()->json(['error' => 'Warning not found'], 404);
            }

            $player = Player::find($warn->player_id);
            $warn->delete();

            // Log to Discord webhook
            $this->webhookService->logAction('warn_delete', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0x00ff00); // Green for removals

            return response()->json([
                'success' => true,
                'message' => 'Warning deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete warning: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get warnings by player
     */
    public function getWarnsByPlayer($player_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $warns = Warn::with(['player', 'staff'])
                ->where('player_id', $player_id)
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($warn) {
                    return [
                        'id' => $warn->id,
                        'reason' => $warn->reason,
                        'staff_username' => $warn->staff->staff_username ?? 'Unknown',
                        'created_at' => $warn->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'warns' => $warns
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch player warnings: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get warnings by staff member
     */
    public function getWarnsByStaff($staff_id)
    {
        try {
            $currentStaff = Staff::find(Auth::id());
            if (!$currentStaff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $warns = Warn::with(['player', 'staff'])
                ->where('staff_id', $staff_id)
                ->where('server_id', $currentStaff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($warn) {
                    return [
                        'id' => $warn->id,
                        'player_name' => $warn->player->last_player_name ?? 'Unknown',
                        'reason' => $warn->reason,
                        'created_at' => $warn->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'warns' => $warns
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch staff warnings: ' . $e->getMessage()], 500);
        }
    }
}
