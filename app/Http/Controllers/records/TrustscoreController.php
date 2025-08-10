<?php

namespace App\Http\Controllers\records;

use App\Http\Controllers\Controller;
use App\Models\Trustscore;
use App\Models\Player;
use App\Models\Staff;
use App\Services\DiscordWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TrustscoreController extends Controller
{
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    /**
     * Create or update a trust score
     */
    public function createTrustScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|integer|exists:players,player_id',
            'trust_score' => 'required|integer|min:0|max:100'
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

            // Check if trust score already exists for this player
            $existingTrustScore = Trustscore::where('player_id', $request->player_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if ($existingTrustScore) {
                // Update existing trust score
                $oldScore = $existingTrustScore->trust_score;
                $existingTrustScore->trust_score = $request->trust_score;
                $existingTrustScore->staff_id = $staff->staff_id;
                $existingTrustScore->save();

                $action = 'trustscore_update';
                $message = 'Trust score updated successfully';
            } else {
                // Create new trust score
                $existingTrustScore = new Trustscore();
                $existingTrustScore->player_id = $request->player_id;
                $existingTrustScore->trust_score = $request->trust_score;
                $existingTrustScore->staff_id = $staff->staff_id;
                $existingTrustScore->server_id = $staff->server_id;
                $existingTrustScore->save();

                $action = 'trustscore_create';
                $message = 'Trust score created successfully';
            }

            // Log to Discord webhook
            $this->webhookService->logAction($action, [
                'player_name' => $player->last_player_name,
                'staff_username' => $staff->staff_username,
                'trust_score' => $request->trust_score,
                'old_score' => $oldScore ?? null,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0x32cd32); // Lime green for trust scores

            // Additional Laravel logging for audit trail
            Log::info("Trust score {$action}", [
                'player_id' => $request->player_id,
                'player_name' => $player->last_player_name,
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'trust_score' => $request->trust_score,
                'old_score' => $oldScore ?? null,
                'action' => $action,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'trust_score_id' => $existingTrustScore->id,
                'trust_score' => $request->trust_score
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create/update trust score: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all trust scores
     */
    public function getTrustScores(Request $request)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $trustScores = Trustscore::with(['player', 'staff'])
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($trustScore) {
                    return [
                        'id' => $trustScore->id,
                        'player_name' => $trustScore->player->last_player_name ?? 'Unknown',
                        'trust_score' => $trustScore->trust_score,
                        'staff_username' => $trustScore->staff->staff_username ?? 'Unknown',
                        'created_at' => $trustScore->created_at,
                        'updated_at' => $trustScore->updated_at
                    ];
                });

            return response()->json([
                'success' => true,
                'trust_scores' => $trustScores
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve trust scores: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get a specific trust score
     */
    public function getTrustScore($trust_score_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $trustScore = Trustscore::with(['player', 'staff'])
                ->where('id', $trust_score_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$trustScore) {
                return response()->json(['error' => 'Trust score not found'], 404);
            }

            return response()->json([
                'success' => true,
                'trust_score' => [
                    'id' => $trustScore->id,
                    'player_name' => $trustScore->player->last_player_name ?? 'Unknown',
                    'trust_score' => $trustScore->trust_score,
                    'staff_username' => $trustScore->staff->staff_username ?? 'Unknown',
                    'created_at' => $trustScore->created_at,
                    'updated_at' => $trustScore->updated_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve trust score: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update a trust score
     */
    public function updateTrustScore(Request $request, $trust_score_id)
    {
        $validator = Validator::make($request->all(), [
            'trust_score' => 'required|integer|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $trustScore = Trustscore::where('id', $trust_score_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$trustScore) {
                return response()->json(['error' => 'Trust score not found'], 404);
            }

            $player = Player::find($trustScore->player_id);
            $oldScore = $trustScore->trust_score;

            // Update trust score
            $trustScore->trust_score = $request->trust_score;
            $trustScore->staff_id = $staff->staff_id;
            $trustScore->save();

            // Log to Discord webhook
            $this->webhookService->logAction('trustscore_update', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'trust_score' => $request->trust_score,
                'old_score' => $oldScore,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0xff8c00); // Dark orange for updates

            // Additional Laravel logging for audit trail
            Log::info('Trust score updated', [
                'trust_score_id' => $trust_score_id,
                'player_id' => $trustScore->player_id,
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'old_score' => $oldScore,
                'new_score' => $request->trust_score,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trust score updated successfully',
                'old_score' => $oldScore,
                'new_score' => $request->trust_score
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update trust score: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a trust score
     */
    public function deleteTrustScore($trust_score_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $trustScore = Trustscore::where('id', $trust_score_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$trustScore) {
                return response()->json(['error' => 'Trust score not found'], 404);
            }

            $player = Player::find($trustScore->player_id);
            $oldScore = $trustScore->trust_score;
            $trustScore->delete();

            // Log to Discord webhook
            $this->webhookService->logAction('trustscore_delete', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'deleted_score' => $oldScore,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0xff0000); // Red for deletions

            // Additional Laravel logging for audit trail
            Log::info('Trust score deleted', [
                'trust_score_id' => $trust_score_id,
                'player_id' => $trustScore->player_id,
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'deleted_score' => $oldScore,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trust score deleted successfully',
                'deleted_score' => $oldScore
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete trust score: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reset a player's trust score to default
     */
    public function resetTrustScore(Request $request, $player_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $trustScore = Trustscore::where('player_id', $player_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$trustScore) {
                return response()->json(['error' => 'Trust score not found for this player'], 404);
            }

            $player = Player::find($player_id);
            $oldScore = $trustScore->trust_score;
            $defaultScore = 50; // Default trust score

            // Reset to default score
            $trustScore->trust_score = $defaultScore;
            $trustScore->staff_id = $staff->staff_id;
            $trustScore->save();

            // Log to Discord webhook
            $this->webhookService->logAction('trustscore_reset', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'old_score' => $oldScore,
                'trust_score' => $defaultScore,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0xffff00); // Yellow for resets

            // Additional Laravel logging for audit trail
            Log::info('Trust score reset', [
                'player_id' => $player_id,
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'old_score' => $oldScore,
                'new_score' => $defaultScore,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trust score reset to default successfully',
                'old_score' => $oldScore,
                'new_score' => $defaultScore
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to reset trust score: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get trust score by player
     */
    public function getTrustScoreByPlayer($player_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $trustScore = Trustscore::with(['player', 'staff'])
                ->where('player_id', $player_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$trustScore) {
                return response()->json([
                    'success' => true,
                    'trust_score' => null,
                    'message' => 'No trust score found for this player'
                ]);
            }

            return response()->json([
                'success' => true,
                'trust_score' => [
                    'id' => $trustScore->id,
                    'player_name' => $trustScore->player->last_player_name ?? 'Unknown',
                    'trust_score' => $trustScore->trust_score,
                    'staff_username' => $trustScore->staff->staff_username ?? 'Unknown',
                    'created_at' => $trustScore->created_at,
                    'updated_at' => $trustScore->updated_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve trust score: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get trust scores by staff member
     */
    public function getTrustScoresByStaff($staff_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $trustScores = Trustscore::with(['player', 'staff'])
                ->where('staff_id', $staff_id)
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($trustScore) {
                    return [
                        'id' => $trustScore->id,
                        'player_name' => $trustScore->player->last_player_name ?? 'Unknown',
                        'trust_score' => $trustScore->trust_score,
                        'staff_username' => $trustScore->staff->staff_username ?? 'Unknown',
                        'created_at' => $trustScore->created_at,
                        'updated_at' => $trustScore->updated_at
                    ];
                });

            return response()->json([
                'success' => true,
                'trust_scores' => $trustScores
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve trust scores: ' . $e->getMessage()], 500);
        }
    }
}
