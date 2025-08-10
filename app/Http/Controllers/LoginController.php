<?php

namespace App\Http\Controllers;

use App\Models\ApiUser;
use App\Models\Server;
use App\Models\Staff;
use App\Services\DiscordWebhookService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller {
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }

    private function verifyCaptcha($credentials): bool {
        try {
            $recaptcha_resp = $credentials['g-recaptcha-response'];
            $headers = [
                'Content-type: application/x-www-form-urlencoded'
            ];
            $data = [
                'secret' => env('GOOGLE_CAPTCHA_SECRET', ''),
                'response' => $recaptcha_resp
            ];
            
            // Log captcha verification attempt
            Log::info('Captcha verification attempt', [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'captcha_response_length' => strlen($recaptcha_resp)
            ]);
            
            $captcha_verify_status = $this->httpReq("https://www.google.com/recaptcha/api/siteverify", $headers, $data, true);

            if (!empty($captcha_verify_status)) {
                if (!$captcha_verify_status['success']) {
                    // Log captcha verification failure
                    Log::warning('Captcha verification failed', [
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'captcha_response' => $recaptcha_resp,
                        'verification_response' => $captcha_verify_status
                    ]);
                    return true;
                }
            }
            
            // Log successful captcha verification
            Log::info('Captcha verification successful', [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return false;
            
        } catch (\Exception $e) {
            Log::error('Captcha verification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            return false;
        }
    }
    
    public function authenticateWeb(Request $request): RedirectResponse {
        try {
            $credentials = $request->all();
            
            // Log web authentication attempt
            Log::info('Web authentication attempt', [
                'username' => $credentials['username'] ?? 'unknown',
                'server_id' => $request->get("server_id", 0),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'has_captcha' => isset($credentials['g-recaptcha-response']),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
            /** /
            // UNCOMMENT THIS IF YOU WANT TO USE CAPTCHA
            if (!$this->verifyCaptcha($credentials)) {
                Log::warning('Web authentication failed - captcha verification failed', [
                    'username' => $credentials['username'] ?? 'unknown',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return back()->withErrors([
                    'captcha' => 'You failed the captcha...'
                ]);
            }
            /**/
            
            if (Auth::guard('web')->attempt(['staff_username' => $credentials['username'], 'password' => $credentials['password']])) {
                // Authentication passed, redirect:
                $serverId = $request->get("server_id", 0);
                $staffId = Staff::getIdByUsername($credentials['username']);
                $serverName = Server::getServerNameById($serverId);
                
                Session::put('staff_id', $staffId);
                Session::put("server_id", $serverId);
                Session::put("server_name", $serverName);
                
                // Log successful login to Discord webhook
                $this->webhookService->logAction('staff_login', [
                    'username' => $credentials['username'],
                    'server_name' => $serverName,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ], 0x00ff00); // Green for successful actions
                
                // Log successful web authentication
                Log::info('Web authentication successful', [
                    'username' => $credentials['username'],
                    'staff_id' => $staffId,
                    'server_id' => $serverId,
                    'server_name' => $serverName,
                    'session_id' => Session::getId(),
                    'login_timestamp' => now()->format('Y-m-d H:i:s'),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return redirect()->intended(route('DASHBOARD'));
            }
            
            // Log failed authentication attempt
            Log::warning('Web authentication failed - invalid credentials', [
                'username' => $credentials['username'] ?? 'unknown',
                'server_id' => $request->get("server_id", 0),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'failed_timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
            return back()->withErrors([
                'staff_username' => 'The provided credentials do not match our records...'
            ])->onlyInput('staff_username');
            
        } catch (\Exception $e) {
            Log::error('Web authentication error', [
                'username' => $credentials['username'] ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return back()->withErrors([
                'error' => 'An error occurred during authentication. Please try again.'
            ]);
        }
    }

    public function authenticateApi(Request $request): array {
        try {
            $credentials = $request->all();
            
            // Log API authentication attempt
            Log::info('API authentication attempt', [
                'username' => $credentials['username'] ?? 'unknown',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
            if (Auth::guard('web')->attempt(['staff_username' => $credentials['username'], 'password' => $credentials['password']])) {
                $user = Staff::where('staff_username', $credentials['username'])->first();
                
                // Log token cleanup
                $oldTokensCount = $user->tokens()->count();
                $user->tokens()->delete();
                
                $newToken = $user->createToken("access_token")->plainTextToken;
                
                // Log successful API authentication to Discord webhook
                $this->webhookService->logAction('api_login', [
                    'username' => $credentials['username'],
                    'server_name' => $user->server->server_name ?? null,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ], 0x00ff00); // Green for successful actions
                
                // Log successful API authentication
                Log::info('API authentication successful', [
                    'username' => $credentials['username'],
                    'staff_id' => $user->staff_id,
                    'server_id' => $user->server_id,
                    'server_name' => $user->server->server_name ?? 'Unknown',
                    'old_tokens_cleaned' => $oldTokensCount,
                    'new_token_created' => true,
                    'token_prefix' => substr($newToken, 0, 10) . '...',
                    'login_timestamp' => now()->format('Y-m-d H:i:s'),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return [
                    'success' => true,
                    'api_token' => $newToken,
                    'error' => false
                ];
            }
            
            // Log failed API authentication attempt
            Log::warning('API authentication failed - invalid credentials', [
                'username' => $credentials['username'] ?? 'unknown',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'failed_timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
            return [
                'success' => false,
                'error' => 'Valid credentials for hitting the API were not supplied...'
            ];
            
        } catch (\Exception $e) {
            Log::error('API authentication error', [
                'username' => $credentials['username'] ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return [
                'success' => false,
                'error' => 'An error occurred during API authentication: ' . $e->getMessage()
            ];
        }
    }

    private function httpReq($url, $headers = array(), $data = array(), $post = false): array {
        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_POST => $post,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADEROPT => $headers,
                CURLOPT_SSL_VERIFYPEER => !env('APP_DEBUG', false)
            ));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $resp = curl_exec($ch);
            
            if (curl_errno($ch)) {
                Log::error('HTTP request failed', [
                    'url' => $url,
                    'curl_error' => curl_error($ch),
                    'curl_errno' => curl_errno($ch),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }
            
            curl_close($ch);
            return json_decode($resp, true);
            
        } catch (\Exception $e) {
            Log::error('HTTP request error', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            return [];
        }
    }

    private function discordReq($url, $headers = array(), $data = array(), $post = false): array {
        try {
            $ch = curl_init($url);
            $headers[] = 'Accept: application/json';
            curl_setopt_array($ch, array(
                CURLOPT_POST => $post,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_HEADEROPT => $headers,
                CURLOPT_SSL_VERIFYPEER => !env('APP_DEBUG', false)
            ));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $resp = curl_exec($ch);
            
            if (curl_errno($ch)) {
                Log::error('Discord HTTP request failed', [
                    'url' => $url,
                    'curl_error' => curl_error($ch),
                    'curl_errno' => curl_errno($ch),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }
            
            curl_close($ch);
            return json_decode($resp, true);
            
        } catch (\Exception $e) {
            Log::error('Discord HTTP request error', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            return [];
        }
    }

    public function authenticateDiscord(Request $request): RedirectResponse {
        try {
            $credentials = $request->all();
            
            // Log Discord authentication attempt
            Log::info('Discord authentication attempt', [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'has_captcha' => isset($credentials['g-recaptcha-response']),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
            if (!$this->verifyCaptcha($credentials)) {
                Log::warning('Discord authentication failed - captcha verification failed', [
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return back()->withErrors([
                    'captcha' => 'You failed the captcha...'
                ]);
            }
            
            $discordCode = $credentials['discordCode'];
            $data = [
                'client_id' => DISCORD_CLIENT_ID,
                'client_secret' => DISCORD_CLIENT_SECRET,
                'grant_type' => 'authorization_code',
                'code' => $discordCode,
                'redirect_uri' => DISCORD_REDIRECT_URI,
                'scope' => 'identify email guilds guilds.join'
            ];
            $options =  [
                'https' => [
                    'header' => 'Content-Type: application/x-www-form-urlencoded',
                    'content' => http_build_query($data)
                ],
                'http' => [
                    'header' => 'Content-Type: application/x-www-form-urlencoded',
                    'content' => http_build_query($data)
                ],
            ];
            
            // Log Discord OAuth attempt
            Log::info('Discord OAuth attempt', [
                'discord_code_length' => strlen($discordCode),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            $discordResp = $this->httpReq('https://discord.com/api/oauth2/authorize', $options['https']);
            
            if (empty($discordResp) || !isset($discordResp['access_token'])) {
                Log::warning('Discord OAuth failed - no access token received', [
                    'discord_response' => $discordResp,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return back()->withErrors([
                    'username' => 'Failed to authenticate with Discord. Please try again.'
                ]);
            }
            
            $access_token = $discordResp['access_token'];
            $user = $this->httpReq('https://discord.com/api/users/@me', ['Authorization: Bearer ' . $access_token]);
            
            if (empty($user) || !isset($user['id'])) {
                Log::warning('Discord user info retrieval failed', [
                    'user_response' => $user,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return back()->withErrors([
                    'username' => 'Failed to retrieve Discord user information. Please try again.'
                ]);
            }
            
            $staffMemberSelected = Staff::where('staff_discord', $user['id'])->first();
            
            if ($staffMemberSelected && $staffMemberSelected->aggregate && sizeof($staffMemberSelected->aggregate) > 0) {
                // It's a valid staff member, we need to log them in
                $staffId = $staffMemberSelected->aggregate['staff_id'];
                Auth::guard('web')->loginUsingId($staffId);
                
                // Log successful Discord authentication
                Log::info('Discord authentication successful', [
                    'discord_id' => $user['id'],
                    'discord_username' => $user['username'] ?? 'Unknown',
                    'staff_id' => $staffId,
                    'login_timestamp' => now()->format('Y-m-d H:i:s'),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return redirect()->intended('DASHBOARD');
            }
            
            // Log failed Discord authentication - staff not found
            Log::warning('Discord authentication failed - staff member not found', [
                'discord_id' => $user['id'],
                'discord_username' => $user['username'] ?? 'Unknown',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records...'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Discord authentication error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return back()->withErrors([
                'username' => 'An error occurred during Discord authentication. Please try again.'
            ]);
        }
    }
}
