<?php

namespace App\Http\Controllers;

use App\Models\Layout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller {
    
    public function index(Request $request) {
        $staffId = Session::get("staff_id");
        $dashboardName = $request->get('dashboard', 'main');
        
        // Get available dashboards for this staff member
        $availableDashboards = Layout::getDashboardNames($staffId);
        
        // If no dashboards exist, create the main dashboard
        if (empty($availableDashboards)) {
            Layout::createDefaultDashboard($staffId, 'main');
            $availableDashboards = ['main'];
        }
        
        // If requested dashboard doesn't exist, create it
        if (!in_array($dashboardName, $availableDashboards)) {
            Layout::createDefaultDashboard($staffId, $dashboardName);
        }
        
        // Get the layout for the requested dashboard
        $layout = Layout::getDashboardLayout($staffId, $dashboardName);
        
        $data = [
            'css_path' => 'verified/dashboard',
            'view_name' => 'DASHBOARD',
            'customize' => false,
            'current_dashboard' => $dashboardName,
            'available_dashboards' => $availableDashboards,
            'layout' => $layout
        ];
        
        return view('verified/dashboard', array('data' => $data));
    }

    public function save(Request $request) {
        // Retrieve the JSON data from the request body
        try {
            $jsonData = $request->json()->all();
            $widgetDataList = $jsonData['widgets'] ?? [];
            $dashboardName = $jsonData['dashboard'] ?? 'main';
            
            if (empty($widgetDataList)) {
                return response()->json(['error' => 'No widget data provided'], 400);
            }
            
            $staffId = Session::get("staff_id");

            $updated = date('Y-m-d H:i:s', time());
            $existingWidgetIds = [];
            
            foreach ($widgetDataList as $wData) {
                // Extract the data from the JSON payload
                $widgetType = $wData['widgetType'] ?? null;
                $widgetId = $wData['widgetId'] ?? null;
                $col = $wData['x'] ?? null;
                $row = $wData['y'] ?? null;
                $sizeX = $wData['w'] ?? null;
                $sizeY = $wData['h'] ?? null;
                
                // Define the data to be updated or inserted
                $data = [
                    'staff_id' => $staffId,
                    'dashboard_name' => $dashboardName,
                    'updated_at' => $updated,
                    'widget_type' => $widgetType,
                    'col' => $col,
                    'row' => $row,
                    'size_x' => $sizeX,
                    'size_y' => $sizeY
                ];

                // Specify the conditions to search for existing records
                $conditions = [
                    'widget_id' => $widgetId,
                    'dashboard_name' => $dashboardName
                ];
                $existingWidgetIds[] = $widgetId;

                // Check if the record already exists
                if (!Layout::where($conditions)->exists()) {
                    // If the record doesn't exist, set the created_at timestamp
                    $data['created_at'] = $updated;
                }
                Layout::updateOrInsert($conditions, $data);
            }
            
            // Need to get widgets for staff that do not exist anymore and delete them...
            if (sizeof($existingWidgetIds)) {
                Layout::whereNotIn('widget_id', $existingWidgetIds)
                    ->where('staff_id', $staffId)
                    ->where('dashboard_name', $dashboardName)
                    ->delete();
            } else {
                Layout::where('staff_id', $staffId)
                    ->where('dashboard_name', $dashboardName)
                    ->delete();
            }
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
        return response()->json(['message' => 'Data saved successfully for staff id: ' . $staffId, 'updated_at' => $updated], 200);
    }

    public function add_widget(Request $request) {
        $datas = $request->all();
        $wt = $datas['widget_type'];
        $dashboardName = $datas['dashboard'] ?? 'main';
        $staffId = Session::get("staff_id");
        
        $col = 1;
        $row = 1;
        $size_x = 12;
        $size_y = 3;
        $view = 'dashboard';
        $widget_type = 'records.widget_bans';
        
        switch ($wt) {
            case "widget_bans":
                $widget_type = 'records.widget_bans';
                break;
            case "widget_kicks":
                $widget_type = 'records.widget_kicks';
                break;
            case "widget_notes":
                $widget_type = 'widget_notes';
                break;
            case "widget_warns":
                $widget_type = 'records.widget_warns';
                break;
            case "widget_commends":
                $widget_type = 'records.widget_commends';
                break;
            case "widget_trustscores":
                $widget_type = 'records.widget_trustscores';
                break;
            case "widget_records":
                $widget_type = 'records.widget_records';
                break;
            case "widget_trust_scores":
                $widget_type = 'widget_trust_scores';
                break;
            case "widget_recent_activity":
                $widget_type = 'widget_recent_activity';
                break;
            case "widget_players":
                $widget_type = 'players.widget_players';
                break;
            case "widget_all_players":
                $widget_type = 'players.widget_all_players';
                break;
        }
        
        $layout = new Layout();
        $layout->store($staffId, $view, $dashboardName, $widget_type, $col, $row, $size_x, $size_y);
        $layout->save();
        
        return redirect()->route("DASHBOARD", ['dashboard' => $dashboardName]);
    }

    public function createDashboard(Request $request) {
        $staffId = Session::get("staff_id");
        $dashboardName = $request->get('dashboard_name');
        
        if (empty($dashboardName)) {
            return response()->json(['error' => 'Dashboard name is required'], 400);
        }
        
        if (Layout::dashboardExists($staffId, $dashboardName)) {
            return response()->json(['error' => 'Dashboard already exists'], 400);
        }
        
        Layout::createDefaultDashboard($staffId, $dashboardName);
        
        return response()->json(['message' => 'Dashboard created successfully', 'dashboard_name' => $dashboardName]);
    }

    public function deleteDashboard(Request $request) {
        $staffId = Session::get("staff_id");
        $dashboardName = $request->get('dashboard_name');
        
        if ($dashboardName === 'main') {
            return response()->json(['error' => 'Cannot delete main dashboard'], 400);
        }
        
        Layout::where('staff_id', $staffId)
            ->where('dashboard_name', $dashboardName)
            ->delete();
        
        return response()->json(['message' => 'Dashboard deleted successfully']);
    }

    public function getDashboardLayout(Request $request) {
        $staffId = Session::get("staff_id");
        $dashboardName = $request->get('dashboard', 'main');
        
        $layout = Layout::getDashboardLayout($staffId, $dashboardName);
        
        return response()->json(['layout' => $layout]);
    }
}
