<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->dropUniqueIndex('players', 'server_id');
        $this->dropUniqueIndex('player_data', 'server_id');
    }

    public function down(): void
    {
        // Do not restore the broken one-player-per-server unique keys.
    }

    private function dropUniqueIndex(string $table, string $column): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Column_name = ? AND Non_unique = 0", [$column]);
        foreach ($indexes as $index) {
            $name = $index->Key_name ?? null;
            if (!$name || $name === 'PRIMARY') {
                continue;
            }
            try {
                DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$name}`");
            } catch (\Throwable $e) {
                // Already dropped.
            }
        }
    }
};
