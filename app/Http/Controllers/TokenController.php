<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\TokenPerms;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TokenController extends Controller {
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
    function generateToken($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }

    function doDeleteToken(Request $request, $tokenId) {
        $db = Token::find($tokenId);
        $deleted = $db->delete();
        return ['deleted' => $deleted];
    }

    function doCreateToken(Request $request) {
        $params = $request->all();
        $note = $params['note'];
        $expiration = $params['expiration'] ?? '90';
        $expiration = strval($expiration);
        $custom_exp = $params['custom_exp'] ?? false;
        $token = 'BSP_' . $this->generateToken() . '_' . (password_hash(date('Y-m-d H:i:s.', microtime(true)), PASSWORD_DEFAULT));

        $staff_id = Session::get("staff_id");

        $tokenDb = new Token();
        $cur_datetime = date('Y-m-d');
        $expiration_date = $cur_datetime;
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
                $expiration_date = date('Y-m-d H:i:s', strtotime('+9999 years'));
                break;
        }
        $hasOneOptOn = false;
        foreach (self::PERMISSIONS as $permission) {
            $valid = $params[strtolower($permission)] ?? false;
            if ($valid) {
                // It's valid
                $hasOneOptOn = true;
            }
        }
        if ($hasOneOptOn) {
            $tokenDb->store($staff_id, $token, $note, $expiration_date);
            $tokenDb->save();
            $token_id = $tokenDb->token_id;
            // We need to store the token permissions
            foreach (self::PERMISSIONS as $permission) {
                $tokenPerm = new TokenPerms();
                $tokenPerm->store($token_id, $permission);
                $tokenPerm->allowed = ($params[strtolower($permission)] ?? false);
                $tokenPerm->allowed = $tokenPerm->allowed ? 1 : 0;
                $tokenPerm->save();
            }
            return redirect()->route('TOKEN_MANAGEMENT');
        } else {
            // TODO Error, needs to turn at least one opt on...
            return redirect()->route('TOKEN_MANAGEMENT')->withErrors(['error' => 'At least one option must be selected.']);
        }
    }
}
