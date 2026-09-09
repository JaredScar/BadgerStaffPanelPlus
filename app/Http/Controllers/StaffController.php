<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Kick;
use App\Models\Staff;
use App\Services\DiscordWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller {
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    /**
     * GET methods
     */
    public function getStaff(): array {
        try {
            $staff = Staff::getAllStaffWithStats()->toArray();
            
            // Log staff list retrieval
            Log::info('Staff list retrieved', [
                'staff_count' => count($staff),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return $staff;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve staff list', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return ['error' => 'Failed to retrieve staff list'];
        }
    }
    
    public function getStaffById(Request $request, $staff_id): array {
        try {
            $staff = Staff::withCount(['kicks', 'bans', 'commends', 'notes', 'warns'])
                         ->find($staff_id);
            
            if (!$staff) {
                Log::warning('Attempted to retrieve non-existent staff member', [
                    'staff_id' => $staff_id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return ['error' => 'Staff member not found'];
            }
            
            $staff->total_actions = $staff->kicks_count + $staff->bans_count + 
                                   $staff->commends_count + $staff->notes_count + $staff->warns_count;
            
            // Log staff retrieval
            Log::info('Staff member retrieved by ID', [
                'staff_id' => $staff_id,
                'staff_username' => $staff->staff_username,
                'total_actions' => $staff->total_actions,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return $staff->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to retrieve staff member by ID', [
                'staff_id' => $staff_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return ['error' => 'Failed to retrieve staff member'];
        }
    }
    
    public function getStaffStatistics(): array {
        try {
            $stats = Staff::getStaffStatistics();
            
            // Log statistics retrieval
            Log::info('Staff statistics retrieved', [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return $stats;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve staff statistics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return ['error' => 'Failed to retrieve staff statistics'];
        }
    }
    
    public function getBannedPlayerCount(Request $request, $staff_id): int {
        try {
            $count = Ban::where('staff_id', $staff_id)->count();
            
            // Log ban count retrieval
            Log::info('Banned player count retrieved for staff', [
                'staff_id' => $staff_id,
                'ban_count' => $count,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return $count;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve banned player count', [
                'staff_id' => $staff_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return 0;
        }
    }
    
    public function getKickedPlayerCount(Request $request, $staff_id): int {
        try {
            $count = Kick::where('staff_id', $staff_id)->count();
            
            // Log kick count retrieval
            Log::info('Kicked player count retrieved for staff', [
                'staff_id' => $staff_id,
                'kick_count' => $count,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return $count;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve kicked player count', [
                'staff_id' => $staff_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return 0;
        }
    }
    
    public function getStaffIdFromDiscord(Request $request, $discord_id): array {
        try {
            $staff = Staff::where('staff_discord', $discord_id)->first();
            
            if (!$staff) {
                Log::warning('Attempted to find staff by non-existent Discord ID', [
                    'discord_id' => $discord_id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return ['error' => 'Staff not found'];
            }
            
            // Log Discord ID lookup
            Log::info('Staff found by Discord ID', [
                'discord_id' => $discord_id,
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return $staff->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to find staff by Discord ID', [
                'discord_id' => $discord_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return ['error' => 'Failed to find staff member'];
        }
    }

    /**
     * POST methods
     */
    public function createNewStaff(Request $request): array {
        $validator = Validator::make($request->all(), [
            'staff_username' => 'required|string|max:255|unique:staff,staff_username',
            'staff_email' => 'required|email|unique:staff,staff_email',
            'staff_discord' => 'required|string|max:255',
            'role' => 'required|in:admin,moderator,helper',
            'join_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            // Log validation failure
            Log::warning('Staff creation failed - validation errors', [
                'validation_errors' => $validator->errors()->toArray(),
                'request_data' => $request->all(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return ['error' => $validator->errors()->first()];
        }

        try {
            $staff = new Staff();
            $staff->staff_username = $request->staff_username;
            $staff->staff_email = $request->staff_email;
            $staff->staff_discord = $request->staff_discord;
            $staff->role = $request->role;
            $staff->status = 'active';
            $staff->join_date = $request->join_date ?? now();
            $staff->notes = $request->notes;
            $staff->password = Hash::make($request->password);
            $staff->server_id = 1; // Default server ID
            $staff->save();

            // Log to Discord webhook
            $this->webhookService->logStaffCreate(
                $staff->staff_username,
                $staff->role,
                $staff->staff_email,
                'System', // The system created this staff member
                $staff->server->server_name ?? null
            );

            // Enhanced Laravel logging for audit trail
            Log::info('Staff member created successfully', [
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'staff_email' => $staff->staff_email,
                'staff_discord' => $staff->staff_discord,
                'role' => $staff->role,
                'status' => $staff->status,
                'join_date' => $staff->join_date,
                'notes' => $staff->notes,
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'created_by' => 'System',
                'creation_timestamp' => now()->format('Y-m-d H:i:s'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return [
                'success' => true,
                'message' => 'Staff member created successfully',
                'staff_id' => $staff->staff_id
            ];
        } catch (\Exception $e) {
            // Log creation failure
            Log::error('Failed to create staff member', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return ['error' => 'Failed to create staff member: ' . $e->getMessage()];
        }
    }

    /**
     * PUT methods
     */
    public function updateStaff(Request $request, $staff_id): array {
        $validator = Validator::make($request->all(), [
            'staff_username' => 'sometimes|required|string|max:255|unique:staff,staff_username,' . $staff_id . ',staff_id',
            'staff_email' => 'sometimes|required|email|unique:staff,staff_email,' . $staff_id . ',staff_id',
            'staff_discord' => 'sometimes|required|string|max:255',
            'role' => 'sometimes|required|in:admin,moderator,helper',
            'status' => 'sometimes|required|in:active,inactive,suspended',
            'join_date' => 'sometimes|nullable|date',
            'notes' => 'sometimes|nullable|string',
            'password' => 'sometimes|nullable|string|min:6'
        ]);

        if ($validator->fails()) {
            // Log validation failure
            Log::warning('Staff update failed - validation errors', [
                'staff_id' => $staff_id,
                'validation_errors' => $validator->errors()->toArray(),
                'request_data' => $request->all(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return ['error' => $validator->errors()->first()];
        }

        try {
            $staff = Staff::find($staff_id);
            
            if (!$staff) {
                Log::warning('Attempted to update non-existent staff member', [
                    'staff_id' => $staff_id,
                    'request_data' => $request->all(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return ['error' => 'Staff member not found'];
            }

            // Store old values for logging
            $oldValues = [
                'staff_username' => $staff->staff_username,
                'staff_email' => $staff->staff_email,
                'staff_discord' => $staff->staff_discord,
                'role' => $staff->role,
                'status' => $staff->status,
                'join_date' => $staff->join_date,
                'notes' => $staff->notes
            ];

            // Update fields if provided
            if ($request->has('staff_username')) {
                $staff->staff_username = $request->staff_username;
            }
            if ($request->has('staff_email')) {
                $staff->staff_email = $request->staff_email;
            }
            if ($request->has('staff_discord')) {
                $staff->staff_discord = $request->staff_discord;
            }
            if ($request->has('role')) {
                $staff->role = $request->role;
            }
            if ($request->has('status')) {
                $staff->status = $request->status;
            }
            if ($request->has('join_date')) {
                $staff->join_date = $request->join_date;
            }
            if ($request->has('notes')) {
                $staff->notes = $request->notes;
            }
            if ($request->has('password') && $request->password) {
                $staff->password = Hash::make($request->password);
            }

            $staff->save();

            // Log to Discord webhook
            $this->webhookService->logAction('staff_update', [
                'username' => $staff->staff_username,
                'role' => $staff->role,
                'email' => $staff->staff_email,
                'staff_username' => 'System', // The system updated this staff member
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0x9370db); // Medium purple for staff actions

            // Enhanced Laravel logging for audit trail
            Log::info('Staff member updated successfully', [
                'staff_id' => $staff->staff_id,
                'old_values' => $oldValues,
                'new_values' => [
                    'staff_username' => $staff->staff_username,
                    'staff_email' => $staff->staff_email,
                    'staff_discord' => $staff->staff_discord,
                    'role' => $staff->role,
                    'status' => $staff->status,
                    'join_date' => $staff->join_date,
                    'notes' => $staff->notes
                ],
                'server_id' => $staff->server_id,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'updated_by' => 'System',
                'password_changed' => $request->has('password') && $request->password,
                'update_timestamp' => now()->format('Y-m-d H:i:s'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return [
                'success' => true,
                'message' => 'Staff member updated successfully'
            ];
        } catch (\Exception $e) {
            // Log update failure
            Log::error('Failed to update staff member', [
                'staff_id' => $staff_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return ['error' => 'Failed to update staff member: ' . $e->getMessage()];
        }
    }

    /**
     * DELETE methods
     */
    public function deleteStaff($staff_id): array {
        try {
            $staff = Staff::find($staff_id);
            
            if (!$staff) {
                Log::warning('Attempted to delete non-existent staff member', [
                    'staff_id' => $staff_id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return ['error' => 'Staff member not found'];
            }

            // Check if staff has any actions (optional - you might want to prevent deletion if they have actions)
            $actionCount = $staff->getTotalActionsCount();
            
            // Store staff details for logging before deletion
            $staffDetails = [
                'staff_id' => $staff->staff_id,
                'staff_username' => $staff->staff_username,
                'staff_email' => $staff->staff_email,
                'staff_discord' => $staff->staff_discord,
                'role' => $staff->role,
                'status' => $staff->status,
                'join_date' => $staff->join_date,
                'notes' => $staff->notes,
                'server_id' => $staff->server_id,
                'total_actions' => $actionCount
            ];
            
            // Log to Discord webhook before deletion
            $this->webhookService->logAction('staff_delete', [
                'username' => $staff->staff_username,
                'role' => $staff->role,
                'email' => $staff->staff_email,
                'staff_username' => 'System', // The system deleted this staff member
                'server_name' => $staff->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0x9370db); // Medium purple for staff actions
            
            $staff->delete();

            // Enhanced Laravel logging for audit trail
            Log::info('Staff member deleted successfully', [
                'deleted_staff_details' => $staffDetails,
                'server_name' => $staff->server->server_name ?? 'Unknown',
                'deleted_by' => 'System',
                'deletion_timestamp' => now()->format('Y-m-d H:i:s'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return [
                'success' => true,
                'message' => 'Staff member deleted successfully',
                'deleted_actions_count' => $actionCount
            ];
        } catch (\Exception $e) {
            // Log deletion failure
            Log::error('Failed to delete staff member', [
                'staff_id' => $staff_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return ['error' => 'Failed to delete staff member: ' . $e->getMessage()];
        }
    }

    /**
     * PATCH methods
     */
    public function updateLastActive($staff_id): array {
        try {
            $staff = Staff::find($staff_id);
            
            if (!$staff) {
                Log::warning('Attempted to update last active for non-existent staff member', [
                    'staff_id' => $staff_id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return ['error' => 'Staff member not found'];
            }

            $oldLastActive = $staff->last_active;
            $staff->last_active = now();
            $staff->save();

            // Log last active update
            Log::info('Staff last active time updated', [
                'staff_id' => $staff_id,
                'staff_username' => $staff->staff_username,
                'old_last_active' => $oldLastActive,
                'new_last_active' => $staff->last_active,
                'update_timestamp' => now()->format('Y-m-d H:i:s'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return [
                'success' => true,
                'message' => 'Last active time updated'
            ];
        } catch (\Exception $e) {
            // Log update failure
            Log::error('Failed to update last active time', [
                'staff_id' => $staff_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return ['error' => 'Failed to update last active time: ' . $e->getMessage()];
        }
    }
}
