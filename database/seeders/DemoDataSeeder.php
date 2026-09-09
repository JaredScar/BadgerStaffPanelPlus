<?php

namespace Database\Seeders;

use App\Models\Layout;
use App\Models\Server;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('servers') || !Schema::hasTable('staff')) {
            return;
        }

        $server = Server::query()->firstOrCreate(
            ['server_slug' => 'demo'],
            [
                'server_name' => 'Demo Server',
                'webhook_enabled' => false,
            ]
        );

        $attributes = [
            'staff_email' => 'admin@example.com',
            'staff_discord' => 0,
            'password' => Hash::make('password'),
            'server_id' => $server->server_id,
        ];

        if (Schema::hasColumn('staff', 'role')) {
            $attributes['role'] = 'admin';
        }
        if (Schema::hasColumn('staff', 'status')) {
            $attributes['status'] = 'active';
        }
        if (Schema::hasColumn('staff', 'join_date')) {
            $attributes['join_date'] = now();
        }
        if (Schema::hasColumn('staff', 'notes')) {
            $attributes['notes'] = 'Seeded demo administrator';
        }

        $staff = Staff::query()->firstOrCreate(
            ['staff_username' => 'admin'],
            $attributes
        );

        if ((int) $staff->server_id !== (int) $server->server_id) {
            $staff->server_id = $server->server_id;
            $staff->save();
        }

        if (Schema::hasTable('layouts') && !Layout::dashboardExists($staff->staff_id, 'main')) {
            Layout::createDefaultDashboard($staff->staff_id, $server->server_id, 'main');
        }
    }
}
