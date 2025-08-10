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
        $recaptcha_resp = $credentials['g-recaptcha-response'];
        $headers = [
            'Content-type: application/x-www-form-urlencoded'
        ];
        $data = [
            'secret' => env('GOOGLE_CAPTCHA_SECRET', ''),
            'response' => $recaptcha_resp
        ];
        $captcha_verify_status = $this->httpReq("https://www.google.com/recaptcha/api/siteverify", $headers, $data, true);

        if (!empty($captcha_verify_status)) {
            if (!$captcha_verify_status['success'])
                return true;
        }
        return false;
    }
    public function authenticateWeb(Request $request): RedirectResponse {
        $credentials = $request->all();
        /** /
        // UNCOMMENT THIS IF YOU WANT TO USE CAPTCHA
        if (!$this->verifyCaptcha($credentials)) {
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
            
            return redirect()->intended(route('DASHBOARD'));
        }
        return back()->withErrors([
            'staff_username' => 'The provided credentials do not match our records...'
        ])->onlyInput('staff_username');
    }

    public function authenticateApi(Request $request): array {
        $credentials = $request->all();
        if (Auth::guard('web')->attempt(['staff_username' => $credentials['username'], 'password' => $credentials['password']])) {
            $user = Staff::where('staff_username', $credentials['username'])->first();
            $user->tokens()->delete();
            $newToken = $user->createToken("access_token")->plainTextToken;
            
            // Log successful API authentication to Discord webhook
            $this->webhookService->logAction('api_login', [
                'username' => $credentials['username'],
                'server_name' => $user->server->server_name ?? null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ], 0x00ff00); // Green for successful actions
            
            return [
                'success' => true,
                'api_token' => $newToken,
                'error' => false
            ];
        }
        return [
            'success' => false,
            'error' => 'Valid credentials for hitting the API were not supplied...'
        ];
    }

    private function httpReq($url, $headers = array(), $data = array(), $post = false): array {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST => $post,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADEROPT => $headers,
            CURLOPT_SSL_VERIFYPEER => !env('APP_DEBUG', false)
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($ch);
        curl_close($ch);
        return json_decode($resp, true);
    }

    private function discordReq($url, $headers = array(), $data = array(), $post = false): array {
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
        curl_close($ch);
        return json_decode($resp, true);
    }

    public function authenticateDiscord(Request $request): RedirectResponse {
        $credentials = $request->all();
        if (!$this->verifyCaptcha($credentials)) {
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
        $discordResp = $this->httpReq('https://discord.com/api/oauth2/authorize', $options['https']);
        $access_token = $discordResp['access_token'];
        $user = $this->httpReq('https://discord.com/api/users/@me', ['Authorization: Bearer ' . $access_token]);
        $staffMemberSelected = Staff::where('staff_discord', $user['id'])->first()->aggregate;
        if (sizeof($staffMemberSelected) > 0) {
            // It's a valid staff member, we need to log them in
            Auth::guard('web')->loginUsingId($staffMemberSelected['staff_id']);
            return redirect()->intended('DASHBOARD');
        }
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records...'
        ]);
    }
}
