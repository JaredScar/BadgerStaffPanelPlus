<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->bigIncrements('server_id')->primary();
            $table->string('server_name');
            $table->string('server_slug', 128);
            $table->timestamps();
        });

        DB::table('servers')->insert([
            [
                'server_name' => 'CollectiveM',
                'server_slug' => 'collectivem'
            ]
        ]);

        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('staff_id')->primary();
            $table->string('staff_username')->unique();
            $table->string('staff_password');
            $table->string('staff_email')->unique();
            $table->bigInteger('staff_discord');
            $table->unsignedBigInteger('server_id');
            $table->timestamps();

            $table->foreign('server_id')->references('server_id')->on('servers');
        });

        DB::table('staff')->insert([
            [
                'staff_username' => 'badger',
                'staff_password' => '$2a$15$ONynqN.bUe7SvpYhVksoqegQTCviThdqzCSsmoN/KmGwR61bmRQ5q',
                'staff_email' => 'thewolfbadger@gmail.com',
                'staff_discord' => 394446211341615104,
                'server_id' => 1
            ]
        ]);

        Schema::create('staff_perms', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id');
            $table->enum('permission', ['TOKEN_MANAGEMENT', 'STAFF_MANAGEMENT', 'SETTINGS_MANAGEMENT']);
            $table->boolean('allowed')->default(false);
            $table->timestamps();

            // Adding foreign key constraint for staff_id
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
            $table->primary(['staff_id', 'permission']);
        });

        Schema::create('tokens', function (Blueprint $table) {
            $table->bigIncrements('token_id')->primary();
            $table->unsignedBigInteger('staff_id');
            $table->string('note', 255);
            $table->string('token');
            $table->boolean('active');
            $table->unsignedBigInteger('deactivated_by')->nullable();
            $table->dateTime('expires')->nullable();
            $table->boolean('active_flg')->default(true);
            $table->timestamps();

            $table->foreign('staff_id')->references('staff_id')->on('staff');
        });

        Schema::create('token_perms', function (Blueprint $table) {
            $table->unsignedBigInteger('token_id');
            $table->set('permission', ['REGISTER', 'BAN_CREATE', 'BAN_DELETE', 'WARN_CREATE', 'WARN_DELETE', 'NOTE_CREATE', 'NOTE_DELETE', 'STAFF_CREATE', 'STAFF_DELETE', 'KICK_CREATE', 'KICK_DELETE', 'COMMEND_CREATE', 'COMMEND_DELETE', 'TRUSTSCORE_CREATE', 'TRUSTSCORE_DELETE', 'TRUSTSCORE_RESET']);
            $table->boolean('allowed')->default(0);
            $table->timestamps();

            $table->primary(['token_id', 'permission']);
        });

        Schema::create('players', function (Blueprint $table) {
            $table->unsignedBigInteger('server_id')->unique();
            $table->bigIncrements('player_id')->primary();
            $table->bigInteger('discord_id')->nullable();
            $table->string('game_license')->unique();
            $table->string('steam_id', 32)->nullable();
            $table->string('live')->nullable();
            $table->string('xbl')->nullable();
            $table->string('ip')->nullable();
            $table->string('last_player_name')->nullable();
            $table->timestamps();

            $table->foreign('server_id')->references('server_id')->on('servers');
            $table->foreign('player_id')->references('player_id')->on('players');
        });

        Schema::create('player_data', function (Blueprint $table) {
            $table->unsignedBigInteger('server_id');
            $table->unsignedBigInteger('player_id');
            $table->integer('playtime');
            $table->integer('trust_score');
            $table->integer('joins');
            $table->dateTime('last_join_date');
            $table->timestamps();

            $table->foreign('server_id')->references('server_id')->on('servers');
            $table->foreign('player_id')->references('player_id')->on('players');
            $table->primary(['server_id', 'player_id']);
        });

        Schema::create('warns', function (Blueprint $table) {
            $table->bigIncrements('warn_id')->primary();
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
            $table->bigIncrements('layout_id')->primary();
            $table->unsignedBigInteger('server_id');
            $table->unsignedBigInteger('staff_id');
            $table->string('view', 128);
            $table->string('widget_type', 128);
            $table->integer('col');
            $table->integer('row');
            $table->integer('size_x');
            $table->integer('size_y');
            $table->timestamps();
            $table->foreign('staff_id')->references('staff_id')->on('staff');
            $table->foreign('server_id')->references('server_id')->on('servers');
        });

        Schema::create('kicks', function (Blueprint $table) {
            $table->bigIncrements('kick_id')->primary();
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
            $table->bigIncrements('ban_id')->primary();
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
            $table->bigIncrements('commend_id')->primary();
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
            $table->bigIncrements('note_id')->primary();
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
        Schema::dropIfExists('notes');
        Schema::dropIfExists('commends');
        Schema::dropIfExists('bans');
        Schema::dropIfExists('kicks');
        Schema::dropIfExists('layouts');
        Schema::dropIfExists('warns');
        Schema::dropIfExists('token_perms');
        Schema::dropIfExists('player_data');
        Schema::dropIfExists('players');
        Schema::dropIfExists('tokens');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('servers');
    }
};
