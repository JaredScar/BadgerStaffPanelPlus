<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller {
    public function authenticateWeb(Request $request): RedirectResponse {
        $credentials = $request->only('username', 'password');
        if (Auth::guard('web')->attempt($credentials)) {
            // Authentication passed, redirect:
            return redirect()->intended('DASHBOARD');
        }
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records...'
        ])->onlyInput('username');
    }

    public function authenticateApi(Request $request): array {
        $credentials = [$request->bearerToken()];
        if (Auth::guard('api')->attempt($credentials)) {
            return [
                'success' => true,
                'error' => false
            ];
        }
        return [
            'success' => false,
            'error' => 'Valid credentials for hitting the API were not supplied...'
        ];
    }

    public function generateToken(): array {
        $token = Str::random(60);
        $token = hash('sha256', $token);
        return ['token' => $token];
    }

    private function discordReq($url, $headers = array(), $data = array(), $post = false) {
        $ch = curl_init($url);
        $headers[] = 'Accept: application/json';
        curl_setopt_array($ch, array(
            CURLOPT_POST => $post,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $resp = curl_exec($ch);
        return json_decode($resp);
    }

    public function authenticateDiscord(Request $request): RedirectResponse {
        $discordCode = $request->only('discordCode');
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
        $discordResp = $this->discordReq('https://discord.com/api/oauth2/authorize', $options['https']);
        $access_token = $discordResp['access_token'];
        $user = $this->discordReq('https://discord.com/api/users/@me', ['Authorization: Bearer ' . $access_token]);
        $staffMemberSelected = Staff::where('staff_discord', $user['id'])->first()->aggregate;
        if (sizeof($staffMemberSelected) > 0) {
            // It's a valid staff member, we need to log them in
            // TODO
        }
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records...'
        ]);
    }
}
