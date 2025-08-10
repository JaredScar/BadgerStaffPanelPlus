<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
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
Route::middleware('authApi:sanctum')->get('/players', [PlayerController::class, 'getPlayers']);
Route::middleware('authApi:sanctum')->get('/players/{player_id}', [PlayerController::class, 'getPlayerById']);
Route::middleware('authApi:sanctum')->get('/players/getPlayerIdFromLicense/{game_license}', [PlayerController::class, 'getPlayerIdFromLicense']);
Route::middleware('authApi:sanctum')->get('/players/getPlayerIdFromDiscord/{discord_id}', [PlayerController::class, 'getPlayerIdFromDiscord']);
Route::middleware('authApi:sanctum')->get('/players/getPlayerIdFromSteamId/{steam_id}', [PlayerController::class, 'getPlayerIdFromSteamId']);
Route::middleware('authApi:sanctum')->post('/players/getPlayerIdFromIP', [PlayerController::class, 'getPlayerIdFromIP']);

Route::middleware('authApi:sanctum')->get('/staff', [StaffController::class, 'getStaff']);
Route::middleware('authApi:sanctum')->get('/staff/statistics', [StaffController::class, 'getStaffStatistics']);
Route::middleware('authApi:sanctum')->get('/staff/{staff_id}', [StaffController::class, 'getStaffById']);
Route::middleware('authApi:sanctum')->get('/staff/getStaffIdFromDiscord/{discord_id}', [StaffController::class, 'getStaffIdFromDiscord']);
Route::middleware('authApi:sanctum')->get('/staff/getKickedPlayerCount/{staff_id}', [StaffController::class, 'getKickedPlayerCount']);
Route::middleware('authApi:sanctum')->get('/staff/getBannedPlayerCount/{staff_id}', [StaffController::class, 'getBannedPlayerCount']);

/**
 * POST methods
 */
Route::post('/auth', [LoginController::class, 'authenticateApi']);

Route::middleware('authApi:sanctum')->post('/players/register', [PlayerController::class, 'registerPlayer']);
Route::middleware('authApi:sanctum')->post('/players/ban/{player_id}', [PlayerController::class, 'banPlayer']);
Route::middleware('authApi:sanctum')->post('/players/kick/{player_id}', [PlayerController::class, 'kickPlayer']);
Route::middleware('authApi:sanctum')->post('/players/commend/{player_id}', [PlayerController::class, 'commendPlayer']);
Route::middleware('authApi:sanctum')->post('/players/note/{player_id}', [PlayerController::class, 'notePlayer']);

Route::middleware('authApi:sanctum')->post('/staff/create', [StaffController::class, 'createNewStaff']);

/**
 * PUT methods
 */
Route::middleware('authApi:sanctum')->put('/staff/{staff_id}', [StaffController::class, 'updateStaff']);

/**
 * DELETE methods
 */
Route::middleware('authApi:sanctum')->delete('/staff/{staff_id}', [StaffController::class, 'deleteStaff']);

/**
 * PATCH methods
 */
Route::middleware('authApi:sanctum')->patch('/staff/{staff_id}/last-active', [StaffController::class, 'updateLastActive']);

/**
 * PERMISSION ROUTES
 */
Route::get('/not_authenticated', function () {
    return view('api/not_authenticated');
})->name('NOT_AUTHENTICATED');
Route::get('/not_authorized', function () {
    return view('api/not_authorized');
})->name('NOT_AUTHORIZED');

