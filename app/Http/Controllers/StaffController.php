<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller {
    /**
     * GET methods
     */
    public function getStaff(): array {
        return [];
    }
    public function getStaffById(Request $request, $staff_id): array {
        return [];
    }
    public function getBannedPlayerCount(Request $request, $staff_id): int {
        return 0;
    }
    public function getKickedPlayerCount(Request $request, $staff_id): int {
        return 0;
    }
    public function getStaffIdFromDiscord(Request $request, $discord_id): int {
        return 0;
    }

    /**
     * POST methods
     */
    public function postLogin(Request $request): bool {
        $username = $request->input("username");
        $password = $request->input("password");
        return false;
    }
    public function postLoginDiscord(Request $request): bool {
        return false;
    }

}
