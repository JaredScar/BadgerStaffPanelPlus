<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Kick;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller {
    /**
     * GET methods
     */
    public function getStaff(): array {
        return Staff::all()->all();
    }
    public function getStaffById(Request $request, $staff_id): array {
        return Staff::where('staff_id', $staff_id)->first()->aggregate;
    }
    public function getBannedPlayerCount(Request $request, $staff_id): int {
        return Ban::where('staff_id', $staff_id)->count();
    }
    public function getKickedPlayerCount(Request $request, $staff_id): int {
        return Kick::where('staff_id', $staff_id)->count();
    }
    public function getStaffIdFromDiscord(Request $request, $discord_id): array {
        return Staff::where('staff_discord', $discord_id)->first()->aggregate;
    }

    /**
     * POST methods
     */
    public function createNewStaff(Request $request): bool {}

}
