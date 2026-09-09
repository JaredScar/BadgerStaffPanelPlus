<?php

namespace App\Http\Controllers;

use App\Services\DiscordWebhookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller {
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    public function logout(Request $request): RedirectResponse {
        try {
            // Log logout action before clearing session
            $staffId = Session::get('staff_id');
            $serverId = Session::get('server_id');
            $serverName = Session::get('server_name');
            $sessionId = Session::getId();
            
            // Log logout attempt
            Log::info('Logout attempt initiated', [
                'staff_id' => $staffId,
                'server_id' => $serverId,
                'server_name' => $serverName,
                'session_id' => $sessionId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
            if ($staffId) {
                $staff = \App\Models\Staff::find($staffId);
                if ($staff) {
                    // Log to Discord webhook
                    $this->webhookService->logAction('staff_logout', [
                        'username' => $staff->staff_username,
                        'server_name' => $serverName,
                        'timestamp' => now()->format('Y-m-d H:i:s')
                    ], 0xff8c00); // Dark orange for logout
                    
                    // Log successful logout with staff details
                    Log::info('Staff logout successful', [
                        'staff_id' => $staffId,
                        'staff_username' => $staff->staff_username,
                        'staff_email' => $staff->staff_email,
                        'staff_discord' => $staff->staff_discord,
                        'role' => $staff->role,
                        'server_id' => $serverId,
                        'server_name' => $serverName,
                        'session_id' => $sessionId,
                        'logout_timestamp' => now()->format('Y-m-d H:i:s'),
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent()
                    ]);
                } else {
                    // Log logout with invalid staff ID
                    Log::warning('Logout with invalid staff ID', [
                        'staff_id' => $staffId,
                        'server_id' => $serverId,
                        'server_name' => $serverName,
                        'session_id' => $sessionId,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent()
                    ]);
                }
            } else {
                // Log logout without staff ID (anonymous session)
                Log::info('Anonymous logout', [
                    'server_id' => $serverId,
                    'server_name' => $serverName,
                    'session_id' => $sessionId,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }

            // Perform logout operations
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Log session cleanup
            Log::info('Session cleanup completed', [
                'staff_id' => $staffId,
                'session_id' => $sessionId,
                'new_session_id' => Session::getId(),
                'cleanup_timestamp' => now()->format('Y-m-d H:i:s'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return redirect('/web');
            
        } catch (\Exception $e) {
            // Log logout error
            Log::error('Logout error occurred', [
                'staff_id' => Session::get('staff_id'),
                'server_id' => Session::get('server_id'),
                'session_id' => Session::getId(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Force logout even if error occurs
            try {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            } catch (\Exception $cleanupError) {
                Log::error('Session cleanup failed after logout error', [
                    'original_error' => $e->getMessage(),
                    'cleanup_error' => $cleanupError->getMessage(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }
            
            return redirect('/web');
        }
    }
}
