<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller {
    function generateToken($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }

    function doCreateToken(Request $request) {
        $params = $request->all();
        $note = $params['note'];
        $expiration = $params['expiration'] ?? '90';
        $register_flg = $params['register'] ?? false;

        $staff_create = $params['staff_create'] ?? false;
        $staff_delete = $params['staff_delete'] ?? false;

        $ban_create = $params['ban_create'] ?? false;
        $ban_delete = $params['ban_delete'] ?? false;

        $kick_create = $params['kick_create'] ?? false;
        $kick_delete = $params['kick_delete'] ?? false;

        $warn_create = $params['warn_create'] ?? false;
        $warn_delete = $params['warn_delete'] ?? false;

        $commend_create = $params['commend_create'] ?? false;
        $commend_delete = $params['commend_delete'] ?? false;

        $note_create = $params['note_create'] ?? false;
        $note_delete = $params['note_delete'] ?? false;

        $trustscore_create = $params['trustscore_create'] ?? false;
        $trustscore_delete = $params['trustscore_delete'] ?? false;
        $trustscore_reset = $params['trustscore_reset'] ?? false;
        $token = 'BSP_' . $this->generateToken() . '_' . (password_hash(date('Y-m-d H:i:s.', microtime(true)), PASSWORD_DEFAULT));
    }
}
