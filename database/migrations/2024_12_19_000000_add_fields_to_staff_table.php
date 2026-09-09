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
        if (!Schema::hasTable('staff')) {
            return;
        }

        Schema::table('staff', function (Blueprint $table) {
            if (!Schema::hasColumn('staff', 'role')) {
                $table->enum('role', ['admin', 'moderator', 'helper'])->default('helper')->after('staff_discord');
            }
            if (!Schema::hasColumn('staff', 'status')) {
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            }
            if (!Schema::hasColumn('staff', 'join_date')) {
                $table->date('join_date')->nullable();
            }
            if (!Schema::hasColumn('staff', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('staff', 'last_active')) {
                $table->timestamp('last_active')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('staff')) {
            return;
        }

        Schema::table('staff', function (Blueprint $table) {
            $columns = array_filter(['role', 'status', 'join_date', 'notes', 'last_active'], function ($column) {
                return Schema::hasColumn('staff', $column);
            });

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
