<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class TokenPerms extends Model {

    public function store($token_id, $permission) {
        $this->token_id = $token_id;
        $this->permission = $permission;
    }
}
