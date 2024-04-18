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
        Schema::create('servers', function (Blueprint $table) {
            $table->bigIncrements('server_id');
            $table->string('server_name');
            $table->string('server_slug', 128);
            $table->timestamps();
        });

        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('staff_id');
            $table->string('staff_username')->unique();
            $table->string('staff_password');
            $table->string('staff_email')->unique();
            $table->bigInteger('staff_discord');
            $table->unsignedBigInteger('server_id');
            $table->timestamps();

            $table->foreign('server_id')->references('server_id')->on('servers');
        });

        Schema::create('tokens', function (Blueprint $table) {
            $table->bigIncrements('token_id');
            $table->unsignedBigInteger('staff_id');
            $table->string('token');
            $table->boolean('active');
            $table->unsignedBigInteger('deactivated_by')->nullable();
            $table->dateTime('expires')->nullable();
            $table->boolean('expired')->default(false);
            $table->timestamps();

            $table->foreign('staff_id')->references('staff_id')->on('staff');
        });

        Schema::create('players', function (Blueprint $table) {
            $table->unsignedBigInteger('server_id')->unique();
            $table->bigIncrements('player_id');
            $table->bigInteger('discord_id')->nullable();
            $table->string('game_license')->unique();
            $table->string('steam_id', 32)->nullable();
            $table->string('live')->nullable();
            $table->string('xbl')->nullable();
            $table->string('ip')->nullable();
            $table->string('last_player_name')->nullable();
            $table->timestamps();

            $table->foreign('server_id')->references('server_id')->on('servers');
        });

        Schema::create('player_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('server_id')->unique();
            $table->unsignedBigInteger('player_id')->unique();
            $table->integer('playtime');
            $table->integer('trust_score');
            $table->integer('joins');
            $table->dateTime('last_join_date');
            $table->timestamps();

            $table->foreign('server_id')->references('server_id')->on('servers');
            $table->foreign('player_id')->references('player_id')->on('players');
        });

        Schema::create('warns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('player_id');
            $table->string('reason');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('server_id');
            $table->timestamps();

            $table->foreign('player_id')->references('player_id')->on('players');
            $table->foreign('staff_id')->references('staff_id')->on('staff');
            $table->foreign('server_id')->references('server_id')->on('servers');
        });

        Schema::create('layouts', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id');
            $table->string('view', 128);
            $table->string('widget_type', 128);
            $table->integer('col');
            $table->integer('row');
            $table->integer('size_x');
            $table->integer('size_y');
            $table->timestamps();
        });

        Schema::create('kicks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('player_id');
            $table->string('reason', 255);
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('server_id');
            $table->timestamps();

            $table->foreign('player_id')->references('player_id')->on('players');
            $table->foreign('staff_id')->references('staff_id')->on('staff');
            $table->foreign('server_id')->references('server_id')->on('servers');
        });

        Schema::create('bans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('player_id');
            $table->string('reason', 255);
            $table->unsignedBigInteger('staff_id');
            $table->tinyInteger('expires');
            $table->dateTime('expiredDate')->nullable();
            $table->unsignedBigInteger('server_id');
            $table->timestamps();

            $table->foreign('player_id')->references('player_id')->on('players');
            $table->foreign('staff_id')->references('staff_id')->on('staff');
            $table->foreign('server_id')->references('server_id')->on('servers');
        });

        Schema::create('commends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('player_id');
            $table->string('reason', 255);
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('server_id');
            $table->timestamps();

            $table->foreign('player_id')->references('player_id')->on('players');
            $table->foreign('staff_id')->references('staff_id')->on('staff');
            $table->foreign('server_id')->references('server_id')->on('servers');
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('player_id');
            $table->string('note', 255);
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('server_id');
            $table->timestamps();

            $table->foreign('player_id')->references('player_id')->on('players');
            $table->foreign('staff_id')->references('staff_id')->on('staff');
            $table->foreign('server_id')->references('server_id')->on('servers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layouts');
        Schema::dropIfExists('player_data');
        Schema::dropIfExists('warns');
        Schema::dropIfExists('players');
        Schema::dropIfExists('tokens');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('servers');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('commends');
        Schema::dropIfExists('bans');
        Schema::dropIfExists('kicks');
    }
};
