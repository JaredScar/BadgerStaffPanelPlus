<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\StaffController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/**
 * GET methods
 */
Route::middleware('auth:sanctum')->get('/players', [PlayerController::class, 'getPlayers']);
Route::middleware('auth:sanctum')->get('/players/{player_id}', [PlayerController::class, 'getPlayerById']);
Route::middleware('auth:sanctum')->get('/players/getPlayerIdFromLicense/{game_license}', [PlayerController::class, 'getPlayerIdFromLicense']);
Route::middleware('auth:sanctum')->get('/players/getPlayerIdFromDiscord/{discord_id}', [PlayerController::class, 'getPlayerIdFromDiscord']);
Route::middleware('auth:sanctum')->get('/players/getPlayerIdFromSteamId/{steam_id}', [PlayerController::class, 'getPlayerIdFromSteamId']);
Route::middleware('auth:sanctum')->post('/players/getPlayerIdFromIP', [PlayerController::class, 'getPlayerIdFromIP']);

Route::middleware('auth:sanctum')->get('/staff', [StaffController::class, 'getStaff']);
Route::middleware('auth:sanctum')->get('/staff/{staff_id}', [StaffController::class, 'getStaffById']);
Route::middleware('auth:sanctum')->get('/staff/getStaffIdFromDiscord/{discord_id}', [StaffController::class, 'getStaffIdFromDiscord']);
Route::middleware('auth:sanctum')->get('/staff/getKickedPlayerCount/{staff_id}', [StaffController::class, 'getKickedPlayerCount']);
Route::middleware('auth:sanctum')->get('/staff/getBannedPlayerCount/{staff_id}', [StaffController::class, 'getBannedPlayerCount']);
/**
 * POST methods
 */
Route::middleware('auth:sanctum')->post('/players/register', [PlayerController::class, 'registerPlayer']);
Route::middleware('auth:sanctum')->post('/players/ban/{player_id}', [PlayerController::class, 'banPlayer']);
Route::middleware('auth:sanctum')->post('/players/kick/{player_id}', [PlayerController::class, 'kickPlayer']);
Route::middleware('auth:sanctum')->post('/players/commend/{player_id}', [PlayerController::class, 'commendPlayer']);
Route::middleware('auth:sanctum')->post('/players/note/{player_id}', [PlayerController::class, 'notePlayer']);

Route::middleware('auth:sanctum')->post('/staff/postLogin', [StaffController::class, 'postLogin']);
Route::middleware('auth:sanctum')->post('/staff/postLoginDiscord', [StaffController::class, 'postLoginDiscord']);

/**
 * PERMISSION ROUTES
 */
Route::get('/not_authenticated', function () {
    return view('api/not_authenticated');
})->name('NOT_AUTHENTICATED');
Route::get('/not_authorized', function () {
    return view('api/not_authorized');
})->name('NOT_AUTHORIZED');

