<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\TokenPerms;
use App\Services\DiscordWebhookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class TokenController extends Controller {
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    const PERMISSIONS = [
        'REGISTER',
        'BAN_CREATE',
        'BAN_DELETE',
        'WARN_CREATE',
        'WARN_DELETE',
        'NOTE_CREATE',
        'NOTE_DELETE',
        'STAFF_CREATE',
        'STAFF_DELETE',
        'KICK_CREATE',
        'KICK_DELETE',
        'COMMEND_CREATE',
        'COMMEND_DELETE',
        'TRUSTSCORE_CREATE',
        'TRUSTSCORE_DELETE',
        'TRUSTSCORE_RESET'
    ];

    /**
     * Generate a secure random token
     * @param int $length Token length in characters
     * @return string Generated token
     */
    function generateToken($length = 32) {
        try {
            $token = bin2hex(random_bytes($length / 2));
            
            // Log token generation for security audit
            Log::info('Token generated', [
                'length' => $length,
                'generated_at' => now()->format('Y-m-d H:i:s'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return $token;
        } catch (\Exception $e) {
            Log::error('Failed to generate token', [
                'error' => $e->getMessage(),
                'length' => $length,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Fallback to alternative generation method
            return bin2hex(openssl_random_pseudo_bytes($length / 2));
        }
    }

    /**
     * Delete a token
     * @param Request $request
     * @param int $tokenId
     * @return array
     */
    function doDeleteToken(Request $request, $tokenId) {
        try {
            $db = Token::find($tokenId);
            
            if (!$db) {
                Log::warning('Attempted to delete non-existent token', [
                    'token_id' => $tokenId,
                    'staff_id' => Session::get("staff_id"),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return ['deleted' => false, 'error' => 'Token not found'];
            }
            
            // Log to Discord webhook before deletion
            $staff = \App\Models\Staff::find(Session::get("staff_id"));
            if ($staff) {
                $this->webhookService->logAction('token_deactivate', [
                    'username' => $db->note ?? 'Unknown',
                    'staff_username' => $staff->staff_username,
                    'server_name' => $staff->server->server_name ?? null,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ], 0x20b2aa); // Light sea green for tokens

                // Enhanced Laravel logging for audit trail
                Log::info('Token deactivated', [
                    'token_id' => $db->token_id,
                    'token_note' => $db->note ?? 'Unknown',
                    'token_expiration' => $db->expiration,
                    'token_created_at' => $db->created_at ?? null,
                    'staff_id' => $staff->staff_id,
                    'staff_username' => $staff->staff_username,
                    'server_id' => $staff->server_id,
                    'server_name' => $staff->server->server_name ?? 'Unknown',
                    'deactivated_by' => $staff->staff_username,
                    'deactivation_reason' => 'Manual deletion by staff',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'request_data' => $request->all()
                ]);
            }
            
            $deleted = $db->delete();
            
            if ($deleted) {
                // Log successful deletion
                Log::info('Token successfully deleted from database', [
                    'token_id' => $tokenId,
                    'deleted_at' => now()->format('Y-m-d H:i:s')
                ]);
            } else {
                // Log deletion failure
                Log::error('Failed to delete token from database', [
                    'token_id' => $tokenId,
                    'staff_id' => Session::get("staff_id")
                ]);
            }
            
            return ['deleted' => $deleted];
            
        } catch (\Exception $e) {
            Log::error('Exception occurred while deleting token', [
                'token_id' => $tokenId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'staff_id' => Session::get("staff_id"),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return ['deleted' => false, 'error' => 'Internal server error'];
        }
    }

    /**
     * Create a new token
     * @param Request $request
     * @return RedirectResponse
     */
    function doCreateToken(Request $request) {
        try {
            $params = $request->all();
            $note = $params['note'];
            $expiration = $params['expiration'] ?? '90';
            $expiration = strval($expiration);
            $custom_exp = $params['custom_exp'] ?? false;
            
            // Log token creation attempt
            Log::info('Token creation initiated', [
                'note' => $note,
                'expiration' => $expiration,
                'custom_exp' => $custom_exp,
                'staff_id' => Session::get("staff_id"),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'request_data' => $params
            ]);
            
            $token = 'BSP_' . $this->generateToken() . '_' . (password_hash(date('Y-m-d H:i:s.', microtime(true)), PASSWORD_DEFAULT));

            $staff_id = Session::get("staff_id");

            $tokenDb = new Token();
            $cur_datetime = date('Y-m-d');
            $expiration_date = $cur_datetime;
            
            // Calculate expiration date
            switch ($expiration) {
                case '7':
                    $expiration_date = date('Y-m-d H:i:s', strtotime('+7 days'));
                    break;
                case '30':
                    $expiration_date = date('Y-m-d H:i:s', strtotime('+30 days'));
                    break;
                case '60':
                    $expiration_date = date('Y-m-d H:i:s', strtotime('+60 days'));
                    break;
                case '90':
                    $expiration_date = date('Y-m-d H:i:s', strtotime('+90 days'));
                    break;
                case 'custom':
                    $expiration_date = $custom_exp;
                    break;
                case 'noexp':
                    $expiration_date = date('Y-m-d H:i:s', strtotime('+100 years'));
                    break;
            }
            
            $hasOneOptOn = false;
            $selectedPermissions = [];
            
            foreach (self::PERMISSIONS as $permission) {
                $valid = $params[strtolower($permission)] ?? false;
                if ($valid) {
                    $hasOneOptOn = true;
                    $selectedPermissions[] = $permission;
                }
            }
            
            if ($hasOneOptOn) {
                $tokenDb->store($staff_id, $token, $note, $expiration_date);
                $tokenDb->save();
                $token_id = $tokenDb->token_id;
                
                // Store token permissions
                foreach (self::PERMISSIONS as $permission) {
                    $tokenPerm = new TokenPerms();
                    $tokenPerm->store($token_id, $permission);
                    $tokenPerm->allowed = ($params[strtolower($permission)] ?? false);
                    $tokenPerm->allowed = $tokenPerm->allowed ? 1 : 0;
                    $tokenPerm->save();
                }
                
                // Log to Discord webhook
                $staff = \App\Models\Staff::find($staff_id);
                if ($staff) {
                    $this->webhookService->logTokenCreate(
                        $staff->staff_username,
                        $note,
                        $staff->staff_username,
                        $staff->server->server_name ?? null
                    );

                    // Enhanced Laravel logging for audit trail
                    Log::info('Token created successfully', [
                        'token_id' => $token_id,
                        'token_note' => $note,
                        'token_expiration' => $expiration_date,
                        'expiration_type' => $expiration,
                        'staff_id' => $staff->staff_id,
                        'staff_username' => $staff->staff_username,
                        'server_id' => $staff->server_id,
                        'server_name' => $staff->server->server_name ?? 'Unknown',
                        'created_by' => $staff->staff_username,
                        'permissions' => $selectedPermissions,
                        'permission_count' => count($selectedPermissions),
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'creation_timestamp' => now()->format('Y-m-d H:i:s')
                    ]);
                }
                
                return redirect()->route('TOKEN_MANAGEMENT');
            } else {
                // Log validation error
                Log::warning('Token creation failed - no permissions selected', [
                    'note' => $note,
                    'expiration' => $expiration,
                    'staff_id' => Session::get("staff_id"),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return redirect()->route('TOKEN_MANAGEMENT')->withErrors(['error' => 'At least one option must be selected.']);
            }
            
        } catch (\Exception $e) {
            // Log exception during token creation
            Log::error('Exception occurred while creating token', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'staff_id' => Session::get("staff_id"),
                'request_data' => $request->all(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return redirect()->route('TOKEN_MANAGEMENT')->withErrors(['error' => 'An error occurred while creating the token.']);
        }
    }
}
