<?php

namespace App\Http\Controllers\records;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Player;
use App\Models\Staff;
use App\Services\DiscordWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    /**
     * Create a new note
     */
    public function createNote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|integer|exists:players,player_id',
            'note' => 'required|string|max:255'
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

            $note = new Note();
            $note->player_id = $request->player_id;
            $note->note = $request->note;
            $note->staff_id = $staff->staff_id;
            $note->server_id = $staff->server_id;
            $note->save();

            // Log to Discord webhook
            $this->webhookService->logNoteCreate(
                $player->last_player_name,
                $staff->staff_username,
                $request->note,
                $staff->server->server_name ?? null
            );

            // Additional Laravel logging for audit trail
            Log::info('Note created', [
                'note_id' => $note->id,
                'player_id' => $request->player_id,
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'note' => $request->note,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Note created successfully',
                'note_id' => $note->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create note: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all notes
     */
    public function getNotes(Request $request)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $notes = Note::with(['player', 'staff'])
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($note) {
                    return [
                        'id' => $note->id,
                        'player_name' => $note->player->last_player_name ?? 'Unknown',
                        'note' => $note->note,
                        'staff_username' => $note->staff->staff_username ?? 'Unknown',
                        'created_at' => $note->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'notes' => $notes
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve notes: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get a specific note
     */
    public function getNote($note_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $note = Note::with(['player', 'staff'])
                ->where('id', $note_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$note) {
                return response()->json(['error' => 'Note not found'], 404);
            }

            return response()->json([
                'success' => true,
                'note' => [
                    'id' => $note->id,
                    'player_name' => $note->player->last_player_name ?? 'Unknown',
                    'note' => $note->note,
                    'staff_username' => $note->staff->staff_username ?? 'Unknown',
                    'created_at' => $note->created_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve note: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update a note
     */
    public function updateNote(Request $request, $note_id)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'sometimes|required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $note = Note::where('id', $note_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$note) {
                return response()->json(['error' => 'Note not found'], 404);
            }

            $player = Player::find($note->player_id);

            // Store old value for logging
            $oldNote = $note->note;

            // Update fields if provided
            if ($request->has('note')) {
                $note->note = $request->note;
            }

            $note->save();

            // Log to Discord webhook
            $this->webhookService->logAction('note_update', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'note' => $note->note,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0xff8c00); // Dark orange for updates

            // Additional Laravel logging for audit trail
            Log::info('Note updated', [
                'note_id' => $note->id,
                'player_id' => $note->player_id,
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'old_note' => $oldNote,
                'new_note' => $note->note,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Note updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update note: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a note
     */
    public function deleteNote($note_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $note = Note::where('id', $note_id)
                ->where('server_id', $staff->server_id)
                ->first();

            if (!$note) {
                return response()->json(['error' => 'Note not found'], 404);
            }

            $player = Player::find($note->player_id);
            
            // Store note details for logging before deletion
            $noteDetails = [
                'note_id' => $note->id,
                'player_id' => $note->player_id,
                'player_name' => $player->last_player_name ?? 'Unknown',
                'note' => $note->note
            ];
            
            $note->delete();

            // Log to Discord webhook
            $this->webhookService->logAction('note_delete', [
                'player_name' => $player->last_player_name ?? 'Unknown',
                'staff_username' => $staff->staff_username,
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0xff0000); // Red for deletions

            // Additional Laravel logging for audit trail
            Log::info('Note deleted', [
                'note_id' => $noteDetails['note_id'],
                'player_id' => $noteDetails['player_id'],
                'player_name' => $noteDetails['player_name'],
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'deleted_note' => $noteDetails['note'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Note deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete note: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get notes by player
     */
    public function getNotesByPlayer($player_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $notes = Note::with(['player', 'staff'])
                ->where('player_id', $player_id)
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($note) {
                    return [
                        'id' => $note->id,
                        'player_name' => $note->player->last_player_name ?? 'Unknown',
                        'note' => $note->note,
                        'staff_username' => $note->staff->staff_username ?? 'Unknown',
                        'created_at' => $note->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'notes' => $notes
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve notes: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get notes by staff member
     */
    public function getNotesByStaff($staff_id)
    {
        try {
            $staff = Staff::find(Auth::id());
            if (!$staff) {
                return response()->json(['error' => 'Staff not found'], 404);
            }

            $notes = Note::with(['player', 'staff'])
                ->where('staff_id', $staff_id)
                ->where('server_id', $staff->server_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($note) {
                    return [
                        'id' => $note->id,
                        'player_name' => $note->player->last_player_name ?? 'Unknown',
                        'note' => $note->note,
                        'staff_username' => $note->staff->staff_username ?? 'Unknown',
                        'created_at' => $note->created_at
                    ];
                });

            return response()->json([
                'success' => true,
                'notes' => $notes
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve notes: ' . $e->getMessage()], 500);
        }
    }
}
