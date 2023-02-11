<?php

namespace App\Http\Controllers;

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

    public function authenticateDiscord(Request $request) {
        $discordCode = $request->only('discordCode');
        // TODO need to reach out to Discord API and make sure this user is valid and discord ID exists in DB for staff
    }
}
