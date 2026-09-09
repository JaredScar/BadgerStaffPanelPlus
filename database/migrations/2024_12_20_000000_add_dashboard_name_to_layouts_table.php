<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('layouts') || Schema::hasColumn('layouts', 'dashboard_name')) {
            return;
        }

        Schema::table('layouts', function (Blueprint $table) {
            $table->string('dashboard_name', 128)->default('main')->after('view');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layouts', function (Blueprint $table) {
            $table->dropColumn('dashboard_name');
        });
    }
};
