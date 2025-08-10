<?php

namespace App\Http\Controllers;

use App\Services\DiscordWebhookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller {
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    public function logout(Request $request): RedirectResponse {
        // Log logout action before clearing session
        $staffId = Session::get('staff_id');
        $serverName = Session::get('server_name');
        
        if ($staffId) {
            $staff = \App\Models\Staff::find($staffId);
            if ($staff) {
                $this->webhookService->logAction('staff_logout', [
                    'username' => $staff->staff_username,
                    'server_name' => $serverName,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ], 0xff8c00); // Dark orange for logout
            }
        }

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/web');
    }
}
