<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class Layout extends Model {

    // Define validation rules
    public static function rules()
    {
        return [
            'staff_id' => 'required|numeric',
            'view' => 'required|string',
            'widget_type' => 'required|string',
            'col' => 'required|numeric',
            'row' => 'required|numeric',
            'size_x' => 'required|numeric',
            'size_y' => 'required|numeric',
        ];
    }

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
