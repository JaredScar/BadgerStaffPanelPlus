<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller {
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
        if (!$this->verifyCaptcha($credentials)) {
            return back()->withErrors([
                'captcha' => 'You failed the captcha...'
            ]);
        }
        if (Auth::guard('web')->attempt(['staff_username' => $credentials['username'], 'password' => $credentials['password']])) {
            // Authentication passed, redirect:
            return redirect()->intended('DASHBOARD');
        }
        return back()->withErrors([
            'staff_username' => 'The provided credentials do not match our records...'
        ])->onlyInput('staff_username');
    }

    public function authenticateApi(Request $request): array {
        $credentials = [$request->bearerToken()];
        if (Auth::guard('api')->attempt($credentials)) {
            return [
                'success' => true,
                'api_token' => $this->generateToken(),
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
