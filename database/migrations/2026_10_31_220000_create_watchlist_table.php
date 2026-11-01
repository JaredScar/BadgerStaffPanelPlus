<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('watchlist')) {
            return;
        }

        Schema::create('watchlist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('server_id')->nullable();
            $table->unsignedInteger('player_id');
            $table->unsignedInteger('staff_id')->nullable();
            $table->string('reason', 255)->nullable();
            $table->timestamps();
            $table->index(['server_id', 'player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watchlist');
    }
};
