<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class Server extends Model {
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    public function store($server_id, $server_name, $server_slug) {
        $this->server_id = $server_id;
        $this->server_name = $server_name;
        $this->server_slug = $server_slug;
    }
}
