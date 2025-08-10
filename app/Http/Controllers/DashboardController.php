<?php

namespace App\Http\Controllers;

use App\Models\Layout;
use App\Models\Staff;
use App\Services\DiscordWebhookService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller {
    
    protected $webhookService;

    public function __construct()
    {
        $this->webhookService = new DiscordWebhookService();
    }
    
    public function index(Request $request) {
        try {
            $staffId = Session::get("staff_id");
            $dashboardName = $request->get('dashboard', 'main');
            
            // Log dashboard access attempt
            Log::info('Dashboard access attempt', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Get available dashboards for this staff member
            $availableDashboards = Layout::getDashboardNames($staffId);
            
            // If no dashboards exist, create the main dashboard
            if (empty($availableDashboards)) {
                Layout::createDefaultDashboard($staffId, 'main');
                $availableDashboards = ['main'];
                
                Log::info('Default dashboard created for staff', [
                    'staff_id' => $staffId,
                    'dashboard_name' => 'main',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }
            
            // If requested dashboard doesn't exist, create it
            if (!in_array($dashboardName, $availableDashboards)) {
                Layout::createDefaultDashboard($staffId, $dashboardName);
                
                Log::info('New dashboard created for staff', [
                    'staff_id' => $staffId,
                    'dashboard_name' => $dashboardName,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
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
            
            // Log successful dashboard access
            Log::info('Dashboard accessed successfully', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'available_dashboards_count' => count($availableDashboards),
                'widgets_count' => count($layout),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return view('verified/dashboard', array('data' => $data));
            
        } catch (Exception $e) {
            Log::error('Failed to access dashboard', [
                'staff_id' => Session::get("staff_id"),
                'dashboard_name' => $request->get('dashboard', 'main'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Return error view or redirect
            return response()->json(['error' => 'Failed to load dashboard'], 500);
        }
    }

    public function save(Request $request) {
        try {
            // Retrieve the JSON data from the request body
            $jsonData = $request->json()->all();
            $widgetDataList = $jsonData['widgets'] ?? [];
            $dashboardName = $jsonData['dashboard'] ?? 'main';
            $staffId = Session::get("staff_id");
            
            // Log dashboard save attempt
            Log::info('Dashboard save attempt', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'widgets_count' => count($widgetDataList),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            if (empty($widgetDataList)) {
                Log::warning('Dashboard save failed - no widget data provided', [
                    'staff_id' => $staffId,
                    'dashboard_name' => $dashboardName,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return response()->json(['error' => 'No widget data provided'], 400);
            }
            
            $updated = date('Y-m-d H:i:s', time());
            $existingWidgetIds = [];
            $updatedWidgets = 0;
            $createdWidgets = 0;
            
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
                    $createdWidgets++;
                } else {
                    $updatedWidgets++;
                }
                Layout::updateOrInsert($conditions, $data);
            }
            
            // Need to get widgets for staff that do not exist anymore and delete them...
            $deletedWidgets = 0;
            if (sizeof($existingWidgetIds)) {
                $deletedWidgets = Layout::whereNotIn('widget_id', $existingWidgetIds)
                    ->where('staff_id', $staffId)
                    ->where('dashboard_name', $dashboardName)
                    ->delete();
            } else {
                $deletedWidgets = Layout::where('staff_id', $staffId)
                    ->where('dashboard_name', $dashboardName)
                    ->delete();
            }
            
            // Log dashboard save action to Discord webhook
            $staff = Staff::find($staffId);
            if ($staff) {
                $this->webhookService->logAction('dashboard_save', [
                    'dashboard_name' => $dashboardName,
                    'staff_username' => $staff->staff_username,
                    'server_name' => $staff->server->server_name ?? null,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ], 0x00bfff); // Deep sky blue for dashboard actions
            }
            
            // Log successful dashboard save
            Log::info('Dashboard saved successfully', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'widgets_created' => $createdWidgets,
                'widgets_updated' => $updatedWidgets,
                'widgets_deleted' => $deletedWidgets,
                'total_widgets' => count($widgetDataList),
                'save_timestamp' => $updated,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['message' => 'Data saved successfully for staff id: ' . $staffId, 'updated_at' => $updated], 200);
            
        } catch (Exception $exception) {
            Log::error('Dashboard save failed', [
                'staff_id' => Session::get("staff_id"),
                'dashboard_name' => $request->json()->get('dashboard', 'main'),
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'request_data' => $request->json()->all(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['error' => 'Failed to save dashboard: ' . $exception->getMessage()], 500);
        }
    }

    public function add_widget(Request $request) {
        try {
            $datas = $request->all();
            $wt = $datas['widget_type'];
            $dashboardName = $datas['dashboard'] ?? 'main';
            $staffId = Session::get("staff_id");
            
            // Log widget addition attempt
            Log::info('Widget addition attempt', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'widget_type' => $wt,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
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
                default:
                    Log::warning('Unknown widget type requested', [
                        'staff_id' => $staffId,
                        'widget_type' => $wt,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent()
                    ]);
                    break;
            }
            
            $layout = new Layout();
            $layout->store($staffId, $view, $dashboardName, $widget_type, $col, $row, $size_x, $size_y);
            $layout->save();
            
            // Log to Discord webhook
            $staff = Staff::find($staffId);
            if ($staff) {
                $this->webhookService->logWidgetAdd(
                    $widget_type,
                    $staff->staff_username,
                    $staff->server->server_name ?? null
                );
            }
            
            // Log successful widget addition
            Log::info('Widget added successfully', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'widget_type' => $widget_type,
                'widget_short_type' => $wt,
                'position' => ['col' => $col, 'row' => $row],
                'size' => ['x' => $size_x, 'y' => $size_y],
                'addition_timestamp' => now()->format('Y-m-d H:i:s'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return redirect()->route("DASHBOARD", ['dashboard' => $dashboardName]);
            
        } catch (Exception $e) {
            Log::error('Widget addition failed', [
                'staff_id' => Session::get("staff_id"),
                'widget_type' => $request->get('widget_type'),
                'dashboard_name' => $request->get('dashboard', 'main'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Redirect with error or show error message
            return redirect()->back()->withErrors(['error' => 'Failed to add widget']);
        }
    }

    public function createDashboard(Request $request) {
        try {
            $staffId = Session::get("staff_id");
            $dashboardName = $request->get('dashboard_name');
            
            // Log dashboard creation attempt
            Log::info('Dashboard creation attempt', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            if (empty($dashboardName)) {
                Log::warning('Dashboard creation failed - name is required', [
                    'staff_id' => $staffId,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return response()->json(['error' => 'Dashboard name is required'], 400);
            }
            
            if (Layout::dashboardExists($staffId, $dashboardName)) {
                Log::warning('Dashboard creation failed - already exists', [
                    'staff_id' => $staffId,
                    'dashboard_name' => $dashboardName,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return response()->json(['error' => 'Dashboard already exists'], 400);
            }
            
            Layout::createDefaultDashboard($staffId, $dashboardName);
            
            // Log to Discord webhook
            $staff = Staff::find($staffId);
            if ($staff) {
                $this->webhookService->logDashboardCreate(
                    $dashboardName,
                    $staff->staff_username,
                    $staff->server->server_name ?? null
                );
            }
            
            // Log successful dashboard creation
            Log::info('Dashboard created successfully', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'creation_timestamp' => now()->format('Y-m-d H:i:s'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['message' => 'Dashboard created successfully', 'dashboard_name' => $dashboardName]);
            
        } catch (Exception $e) {
            Log::error('Dashboard creation failed', [
                'staff_id' => Session::get("staff_id"),
                'dashboard_name' => $request->get('dashboard_name'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['error' => 'Failed to create dashboard: ' . $e->getMessage()], 500);
        }
    }

    public function deleteDashboard(Request $request) {
        try {
            $staffId = Session::get("staff_id");
            $dashboardName = $request->get('dashboard_name');
            
            // Log dashboard deletion attempt
            Log::info('Dashboard deletion attempt', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            if ($dashboardName === 'main') {
                Log::warning('Dashboard deletion failed - cannot delete main dashboard', [
                    'staff_id' => $staffId,
                    'dashboard_name' => $dashboardName,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                
                return response()->json(['error' => 'Cannot delete main dashboard'], 400);
            }
            
            // Get dashboard info before deletion for logging
            $dashboardInfo = Layout::where('staff_id', $staffId)
                ->where('dashboard_name', $dashboardName)
                ->get(['widget_type', 'created_at', 'updated_at']);
            
            $widgetsCount = $dashboardInfo->count();
            
            // Log to Discord webhook before deletion
            $staff = Staff::find($staffId);
            if ($staff) {
                $this->webhookService->logAction('dashboard_delete', [
                    'dashboard_name' => $dashboardName,
                    'staff_username' => $staff->staff_username,
                    'server_name' => $staff->server->server_name ?? null,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ], 0x00bfff); // Deep sky blue for dashboard actions
            }
            
            $deletedRows = Layout::where('staff_id', $staffId)
                ->where('dashboard_name', $dashboardName)
                ->delete();
            
            // Log successful dashboard deletion
            Log::info('Dashboard deleted successfully', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'widgets_deleted' => $widgetsCount,
                'deletion_timestamp' => now()->format('Y-m-d H:i:s'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['message' => 'Dashboard deleted successfully']);
            
        } catch (Exception $e) {
            Log::error('Dashboard deletion failed', [
                'staff_id' => Session::get("staff_id"),
                'dashboard_name' => $request->get('dashboard_name'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['error' => 'Failed to delete dashboard: ' . $e->getMessage()], 500);
        }
    }

    public function getDashboardLayout(Request $request) {
        try {
            $staffId = Session::get("staff_id");
            $dashboardName = $request->get('dashboard', 'main');
            
            // Log dashboard layout retrieval attempt
            Log::info('Dashboard layout retrieval attempt', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            $layout = Layout::getDashboardLayout($staffId, $dashboardName);
            
            // Log successful layout retrieval
            Log::info('Dashboard layout retrieved successfully', [
                'staff_id' => $staffId,
                'dashboard_name' => $dashboardName,
                'widgets_count' => count($layout),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['layout' => $layout]);
            
        } catch (Exception $e) {
            Log::error('Dashboard layout retrieval failed', [
                'staff_id' => Session::get("staff_id"),
                'dashboard_name' => $request->get('dashboard', 'main'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return response()->json(['error' => 'Failed to retrieve dashboard layout'], 500);
        }
    }
}
