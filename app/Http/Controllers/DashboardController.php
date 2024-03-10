<?php

namespace App\Http\Controllers;

use App\Models\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller {
    public function save(Request $request) {
        // Retrieve the JSON data from the request body
        $widgetDataList = $request->json()->all();
        $staffId = Session::get("staff_id");

        // TODO We eventually want this to actually update the layout tiles...
        // Deleting them and resetting them is not the best way to do this...

        Layout::where('staff_id', $staffId)->where('view', 'dashboard')->delete();
        $updated = date('Y-m-d H:i:s', time());
        foreach ($widgetDataList as $wData) {
            // Extract the data from the JSON payload
            $widgetType = $wData['widgetType'] ?? null;
            $col = $wData['x'] ?? null;
            $row = $wData['y'] ?? null;
            $sizeX = $wData['w'] ?? null;
            $sizeY = $wData['h'] ?? null;
            $layout = new Layout();
            $layout->store($staffId, 'dashboard', $widgetType, $col, $row, $sizeX, $sizeY);
            $layout->setUpdatedAt($updated);
            $layout->setCreatedAt($updated);
            $layout->save();
        }
        return response()->json(['message' => 'Data saved successfully for staff id: ' . $staffId, 'updated_at' => $updated], 200);
    }
}
