<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class Token extends Model {

    public function store($staff_id, $token, $note, $expires) {
        $this->staff_id = $staff_id;
        $this->token = $token;
        $this->note = $note;
        $this->expires = $expires;
    }
}
