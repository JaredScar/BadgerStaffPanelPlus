<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class Layout extends Model {
    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    public function store($staff_id, $view, $widget_type, $col, $row, $size_x, $size_y) {
        $this->staff_id = $staff_id;
        $this->view = $view;
        $this->widget_type = $widget_type;
        $this->col = $col;
        $this->row = $row;
        $this->size_x = $size_x;
        $this->size_y = $size_y;
    }
}
