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
        Schema::table('staff', function (Blueprint $table) {
            // Add new fields for staff management
            $table->enum('role', ['admin', 'moderator', 'helper'])->default('helper')->after('staff_discord');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('role');
            $table->date('join_date')->nullable()->after('status');
            $table->text('notes')->nullable()->after('join_date');
            $table->timestamp('last_active')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'join_date', 'notes', 'last_active']);
        });
    }
}; 