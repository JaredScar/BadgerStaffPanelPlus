<?php

namespace App\Http\Controllers;

use App\Support\PanelActivity;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(): View
    {
        $serverId = Session::get('server_id');

        return view('verified.records.activity', [
            'data' => [
                'css_path' => 'players',
                'view_name' => 'ACTIVITY',
                'customize' => false,
            ],
            'activity' => PanelActivity::recent($serverId ? (int) $serverId : null, null, 80),
        ]);
    }
}
