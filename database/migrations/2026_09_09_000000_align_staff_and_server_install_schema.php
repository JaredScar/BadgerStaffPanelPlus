<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('staff') && Schema::hasColumn('staff', 'staff_password') && !Schema::hasColumn('staff', 'password')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->string('password')->nullable();
            });

            DB::table('staff')->update([
                'password' => DB::raw('staff_password'),
            ]);

            Schema::table('staff', function (Blueprint $table) {
                $table->dropColumn('staff_password');
            });
        }

        if (Schema::hasTable('servers')) {
            Schema::table('servers', function (Blueprint $table) {
                if (!Schema::hasColumn('servers', 'discord_webhook_url')) {
                    $table->string('discord_webhook_url', 500)->nullable();
                }
                if (!Schema::hasColumn('servers', 'webhook_enabled')) {
                    $table->boolean('webhook_enabled')->default(false);
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('staff') && Schema::hasColumn('staff', 'password') && !Schema::hasColumn('staff', 'staff_password')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->string('staff_password')->nullable();
            });

            DB::table('staff')->update([
                'staff_password' => DB::raw('password'),
            ]);

            Schema::table('staff', function (Blueprint $table) {
                $table->dropColumn('password');
            });
        }
    }
};
