<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Kick;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller {
    /**
     * GET methods
     */
    public function getStaff(): array {
        return Staff::getAllStaffWithStats()->toArray();
    }
    
    public function getStaffById(Request $request, $staff_id): array {
        $staff = Staff::withCount(['kicks', 'bans', 'commends', 'notes', 'warns'])
                     ->find($staff_id);
        
        if (!$staff) {
            return ['error' => 'Staff member not found'];
        }
        
        $staff->total_actions = $staff->kicks_count + $staff->bans_count + 
                               $staff->commends_count + $staff->notes_count + $staff->warns_count;
        
        return $staff->toArray();
    }
    
    public function getStaffStatistics(): array {
        return Staff::getStaffStatistics();
    }
    
    public function getBannedPlayerCount(Request $request, $staff_id): int {
        return Ban::where('staff_id', $staff_id)->count();
    }
    
    public function getKickedPlayerCount(Request $request, $staff_id): int {
        return Kick::where('staff_id', $staff_id)->count();
    }
    
    public function getStaffIdFromDiscord(Request $request, $discord_id): array {
        $staff = Staff::where('staff_discord', $discord_id)->first();
        return $staff ? $staff->toArray() : ['error' => 'Staff not found'];
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

            return [
                'success' => true,
                'message' => 'Staff member created successfully',
                'staff_id' => $staff->staff_id
            ];
        } catch (\Exception $e) {
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
            return ['error' => $validator->errors()->first()];
        }

        try {
            $staff = Staff::find($staff_id);
            
            if (!$staff) {
                return ['error' => 'Staff member not found'];
            }

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

            return [
                'success' => true,
                'message' => 'Staff member updated successfully'
            ];
        } catch (\Exception $e) {
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
                return ['error' => 'Staff member not found'];
            }

            // Check if staff has any actions (optional - you might want to prevent deletion if they have actions)
            $actionCount = $staff->getTotalActionsCount();
            
            $staff->delete();

            return [
                'success' => true,
                'message' => 'Staff member deleted successfully',
                'deleted_actions_count' => $actionCount
            ];
        } catch (\Exception $e) {
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
                return ['error' => 'Staff member not found'];
            }

            $staff->last_active = now();
            $staff->save();

            return [
                'success' => true,
                'message' => 'Last active time updated'
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to update last active time: ' . $e->getMessage()];
        }
    }
}
