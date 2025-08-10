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
            'dashboard_name' => 'required|string',
            'widget_type' => 'required|string',
            'col' => 'required|numeric',
            'row' => 'required|numeric',
            'size_x' => 'required|numeric',
            'size_y' => 'required|numeric',
        ];
    }

    public function store($staff_id, $view, $dashboard_name, $widget_type, $col, $row, $size_x, $size_y) {
        $this->staff_id = $staff_id;
        $this->view = $view;
        $this->dashboard_name = $dashboard_name;
        $this->widget_type = $widget_type;
        $this->col = $col;
        $this->row = $row;
        $this->size_x = $size_x;
        $this->size_y = $size_y;
    }

    /**
     * Get all dashboard names for a staff member
     */
    public static function getDashboardNames($staffId)
    {
        return self::where('staff_id', $staffId)
            ->distinct()
            ->pluck('dashboard_name')
            ->toArray();
    }

    /**
     * Get layout for a specific dashboard
     */
    public static function getDashboardLayout($staffId, $dashboardName)
    {
        return self::where('staff_id', $staffId)
            ->where('dashboard_name', $dashboardName)
            ->orderBy('row')
            ->orderBy('col')
            ->get();
    }

    /**
     * Check if dashboard exists
     */
    public static function dashboardExists($staffId, $dashboardName)
    {
        return self::where('staff_id', $staffId)
            ->where('dashboard_name', $dashboardName)
            ->exists();
    }

    /**
     * Create default dashboard layout
     */
    public static function createDefaultDashboard($staffId, $dashboardName)
    {
        $defaultWidgets = [
            ['widget_type' => 'widget_notes', 'col' => 0, 'row' => 0, 'size_x' => 6, 'size_y' => 8],
            ['widget_type' => 'widget_trust_scores', 'col' => 6, 'row' => 0, 'size_x' => 6, 'size_y' => 8],
            ['widget_type' => 'widget_recent_activity', 'col' => 0, 'row' => 8, 'size_x' => 12, 'size_y' => 10]
        ];

        foreach ($defaultWidgets as $widget) {
            $layout = new self();
            $layout->store(
                $staffId,
                'dashboard',
                $dashboardName,
                $widget['widget_type'],
                $widget['col'],
                $widget['row'],
                $widget['size_x'],
                $widget['size_y']
            );
            $layout->save();
        }
    }
}
