<?php

namespace App\Http\Controllers;

use App\Models\Layout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller {
    public function save(Request $request) {
        // Retrieve the JSON data from the request body
        try {
            $widgetDataList = $request->json()->all();
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
                    'updated_at' => $updated,
                    'widget_type' => $widgetType,
                    'col' => $col,
                    'row' => $row,
                    'size_x' => $sizeX,
                    'size_y' => $sizeY
                ];

                // Specify the conditions to search for existing records
                $conditions = [
                    'widget_id' => $widgetId
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
            if (sizeof($existingWidgetIds))
                Layout::whereNotIn('widget_id', $existingWidgetIds)
                    ->where('staff_id', $staffId)->delete();
            else
                Layout::where('staff_id', $staffId)->delete();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
        return response()->json(['message' => 'Data saved successfully for staff id: ' . $staffId, 'updated_at' => $updated], 200);
    }
}
