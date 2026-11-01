<?php

namespace Database\Seeders;

use App\Models\Layout;
use App\Models\Server;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        $this->seedDemoPlayers($server->server_id, $staff->staff_id);
        $this->seedWatchlist($server->server_id, $staff->staff_id);
    }

    private function seedDemoPlayers(int $serverId, int $staffId): void
    {
        if (!Schema::hasTable('players') || !Schema::hasTable('player_data')) {
            return;
        }

        $now = now();
        $players = [
            [
                'license' => 'abc12license',
                'name' => 'WolfBadger',
                'discord' => '394446211341615104',
                'steam' => 'steam:11000010badger',
                'ip' => '10.0.0.12',
                'playtime' => 1820,
                'trust' => 92,
                'joins' => 48,
                'last_join' => $now->copy()->subMinutes(4),
                'warns' => [['RDM in Legion']],
                'notes' => [['Trusted community member']],
                'commends' => [['Helped a new player']],
            ],
            [
                'license' => 'nhk88license',
                'name' => 'NightHawk',
                'discord' => '112233445566778899',
                'steam' => 'steam:11000010nighthawk',
                'ip' => '10.0.0.18',
                'playtime' => 940,
                'trust' => 41,
                'joins' => 22,
                'last_join' => $now->copy()->subHours(2),
                'warns' => [['Fail RP'], ['Metagame']],
                'kicks' => [['Combat logging']],
                'bans' => [['Exploiting', $now->copy()->addDays(2)]],
                'notes' => [['Watch for vehicle spam']],
            ],
            [
                'license' => 'rly22license',
                'name' => 'RileyV',
                'discord' => '998877665544332211',
                'steam' => 'steam:11000010rileyv',
                'ip' => '10.0.0.23',
                'playtime' => 610,
                'trust' => 78,
                'joins' => 16,
                'last_join' => $now->copy()->subDays(2),
                'commends' => [['Great scene']],
            ],
            [
                'license' => 'gmd09license',
                'name' => 'GhostMedic',
                'discord' => '556677889900112233',
                'steam' => 'steam:11000010ghost',
                'ip' => '10.0.0.31',
                'playtime' => 2210,
                'trust' => 88,
                'joins' => 61,
                'last_join' => $now->copy()->subMinutes(8),
                'notes' => [['EMS lead on weekends']],
                'commends' => [['Mass casualty response']],
            ],
            [
                'license' => 'new91license',
                'name' => 'NewJoin_91',
                'discord' => '123123123123123123',
                'steam' => 'steam:11000010new91',
                'ip' => '10.0.0.44',
                'playtime' => 35,
                'trust' => 50,
                'joins' => 1,
                'last_join' => $now->copy()->subDays(12),
            ],
        ];

        foreach ($players as $row) {
            $existing = DB::table('players')->where('game_license', $row['license'])->first();
            if ($existing) {
                $playerId = $existing->player_id;
                DB::table('players')->where('player_id', $playerId)->update([
                    'server_id' => $serverId,
                    'discord_id' => $row['discord'],
                    'steam_id' => $row['steam'],
                    'ip' => $row['ip'],
                    'last_player_name' => $row['name'],
                    'updated_at' => $now,
                ]);
            } else {
                $playerId = DB::table('players')->insertGetId([
                    'server_id' => $serverId,
                    'discord_id' => $row['discord'],
                    'game_license' => $row['license'],
                    'steam_id' => $row['steam'],
                    'ip' => $row['ip'],
                    'last_player_name' => $row['name'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ], 'player_id');
            }
            $player = (object) ['player_id' => $playerId];

            $exists = DB::table('player_data')->where('player_id', $player->player_id)->exists();
            $payload = [
                'server_id' => $serverId,
                'player_id' => $player->player_id,
                'playtime' => $row['playtime'],
                'trust_score' => $row['trust'],
                'joins' => $row['joins'],
                'last_join_date' => $row['last_join'],
                'updated_at' => $now,
            ];
            if ($exists) {
                DB::table('player_data')->where('player_id', $player->player_id)->update($payload);
            } else {
                $payload['created_at'] = $now;
                DB::table('player_data')->insert($payload);
            }

            $this->seedRecords('warns', $row['warns'] ?? [], $player->player_id, $staffId, $serverId, 'reason');
            $this->seedRecords('kicks', $row['kicks'] ?? [], $player->player_id, $staffId, $serverId, 'reason');
            $this->seedRecords('commends', $row['commends'] ?? [], $player->player_id, $staffId, $serverId, 'reason');
            $this->seedRecords('notes', $row['notes'] ?? [], $player->player_id, $staffId, $serverId, 'note');

            foreach ($row['bans'] ?? [] as $ban) {
                $already = DB::table('bans')->where('player_id', $player->player_id)->where('reason', $ban[0])->exists();
                if ($already) {
                    continue;
                }
                DB::table('bans')->insert([
                    'player_id' => $player->player_id,
                    'reason' => $ban[0],
                    'staff_id' => $staffId,
                    'expires' => 1,
                    'expiredDate' => $ban[1],
                    'server_id' => $serverId,
                    'created_at' => $now->copy()->subDays(3),
                    'updated_at' => $now,
                ]);
            }
        }
    }

    private function seedRecords(string $table, array $items, int $playerId, int $staffId, int $serverId, string $textColumn): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        foreach ($items as $item) {
            $text = $item[0];
            $already = DB::table($table)->where('player_id', $playerId)->where($textColumn, $text)->exists();
            if ($already) {
                continue;
            }
            DB::table($table)->insert([
                'player_id' => $playerId,
                $textColumn => $text,
                'staff_id' => $staffId,
                'server_id' => $serverId,
                'created_at' => now()->subHours(rand(2, 48)),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedWatchlist(int $serverId, int $staffId): void
    {
        if (!Schema::hasTable('watchlist')) {
            return;
        }

        $playerId = DB::table('players')->where('game_license', 'nhk88license')->value('player_id');
        if (!$playerId) {
            return;
        }

        $exists = DB::table('watchlist')->where('server_id', $serverId)->where('player_id', $playerId)->exists();
        if ($exists) {
            return;
        }

        DB::table('watchlist')->insert([
            'server_id' => $serverId,
            'player_id' => $playerId,
            'staff_id' => $staffId,
            'reason' => 'Repeat Fail RP and vehicle spam',
            'created_at' => now()->subHours(6),
            'updated_at' => now(),
        ]);
    }
}
