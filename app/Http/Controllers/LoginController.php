<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller {
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->only('username', 'password');
        if (Auth::guard('web')->attempt($credentials)) {
            // Authentication passed, redirect:
            return redirect()->intended('DASHBOARD');
        }
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records...'
        ])->onlyInput('username');
    }

    public function authenticateDiscord(Request $request) {
        $discordCode = $request->only('discordCode');
        // TODO need to reach out to Discord API and make sure this user is valid and discord ID exists in DB for staff
    }
}
