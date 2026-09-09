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
 * TRUSTSCORE ROUTES
 */
Route::middleware('authApi:sanctum')->post('/trustscores', [App\Http\Controllers\records\TrustscoreController::class, 'createTrustScore']);
Route::middleware('authApi:sanctum')->get('/trustscores', [App\Http\Controllers\records\TrustscoreController::class, 'getTrustScores']);
Route::middleware('authApi:sanctum')->get('/trustscores/{trust_score_id}', [App\Http\Controllers\records\TrustscoreController::class, 'getTrustScore']);
Route::middleware('authApi:sanctum')->put('/trustscores/{trust_score_id}', [App\Http\Controllers\records\TrustscoreController::class, 'updateTrustScore']);
Route::middleware('authApi:sanctum')->delete('/trustscores/{trust_score_id}', [App\Http\Controllers\records\TrustscoreController::class, 'deleteTrustScore']);
Route::middleware('authApi:sanctum')->post('/trustscores/{player_id}/reset', [App\Http\Controllers\records\TrustscoreController::class, 'resetTrustScore']);
Route::middleware('authApi:sanctum')->get('/trustscores/player/{player_id}', [App\Http\Controllers\records\TrustscoreController::class, 'getTrustScoreByPlayer']);
Route::middleware('authApi:sanctum')->get('/trustscores/staff/{staff_id}', [App\Http\Controllers\records\TrustscoreController::class, 'getTrustScoresByStaff']);

/**
 * BAN ROUTES
 */
Route::middleware('authApi:sanctum')->post('/bans', [App\Http\Controllers\records\BanController::class, 'createBan']);
Route::middleware('authApi:sanctum')->get('/bans', [App\Http\Controllers\records\BanController::class, 'getBans']);
Route::middleware('authApi:sanctum')->get('/bans/{ban_id}', [App\Http\Controllers\records\BanController::class, 'getBan']);
Route::middleware('authApi:sanctum')->put('/bans/{ban_id}', [App\Http\Controllers\records\BanController::class, 'updateBan']);
Route::middleware('authApi:sanctum')->delete('/bans/{ban_id}', [App\Http\Controllers\records\BanController::class, 'deleteBan']);
Route::middleware('authApi:sanctum')->get('/bans/player/{player_id}', [App\Http\Controllers\records\BanController::class, 'getBansByPlayer']);
Route::middleware('authApi:sanctum')->get('/bans/staff/{staff_id}', [App\Http\Controllers\records\BanController::class, 'getBansByStaff']);

/**
 * WARN ROUTES
 */
Route::middleware('authApi:sanctum')->post('/warns', [App\Http\Controllers\records\WarnController::class, 'createWarn']);
Route::middleware('authApi:sanctum')->get('/warns', [App\Http\Controllers\records\WarnController::class, 'getWarns']);
Route::middleware('authApi:sanctum')->get('/warns/{warn_id}', [App\Http\Controllers\records\WarnController::class, 'getWarn']);
Route::middleware('authApi:sanctum')->put('/warns/{warn_id}', [App\Http\Controllers\records\WarnController::class, 'updateWarn']);
Route::middleware('authApi:sanctum')->delete('/warns/{warn_id}', [App\Http\Controllers\records\WarnController::class, 'deleteWarn']);
Route::middleware('authApi:sanctum')->get('/warns/player/{player_id}', [App\Http\Controllers\records\WarnController::class, 'getWarnsByPlayer']);
Route::middleware('authApi:sanctum')->get('/warns/staff/{staff_id}', [App\Http\Controllers\records\WarnController::class, 'getWarnsByStaff']);

/**
 * KICK ROUTES
 */
Route::middleware('authApi:sanctum')->post('/kicks', [App\Http\Controllers\records\KickController::class, 'createKick']);
Route::middleware('authApi:sanctum')->get('/kicks', [App\Http\Controllers\records\KickController::class, 'getKicks']);
Route::middleware('authApi:sanctum')->get('/kicks/{kick_id}', [App\Http\Controllers\records\KickController::class, 'getKick']);
Route::middleware('authApi:sanctum')->put('/kicks/{kick_id}', [App\Http\Controllers\records\KickController::class, 'updateKick']);
Route::middleware('authApi:sanctum')->delete('/kicks/{kick_id}', [App\Http\Controllers\records\KickController::class, 'deleteKick']);
Route::middleware('authApi:sanctum')->get('/kicks/player/{player_id}', [App\Http\Controllers\records\KickController::class, 'getKicksByPlayer']);
Route::middleware('authApi:sanctum')->get('/kicks/staff/{staff_id}', [App\Http\Controllers\records\KickController::class, 'getKicksByStaff']);

/**
 * NOTE ROUTES
 */
Route::middleware('authApi:sanctum')->post('/notes', [App\Http\Controllers\records\NoteController::class, 'createNote']);
Route::middleware('authApi:sanctum')->get('/notes', [App\Http\Controllers\records\NoteController::class, 'getNotes']);
Route::middleware('authApi:sanctum')->get('/notes/{note_id}', [App\Http\Controllers\records\NoteController::class, 'getNote']);
Route::middleware('authApi:sanctum')->put('/notes/{note_id}', [App\Http\Controllers\records\NoteController::class, 'updateNote']);
Route::middleware('authApi:sanctum')->delete('/notes/{note_id}', [App\Http\Controllers\records\NoteController::class, 'deleteNote']);
Route::middleware('authApi:sanctum')->get('/notes/player/{player_id}', [App\Http\Controllers\records\NoteController::class, 'getNotesByPlayer']);
Route::middleware('authApi:sanctum')->get('/notes/staff/{staff_id}', [App\Http\Controllers\records\NoteController::class, 'getNotesByStaff']);

/**
 * COMMEND ROUTES
 */
Route::middleware('authApi:sanctum')->post('/commends', [App\Http\Controllers\records\CommendController::class, 'createCommend']);
Route::middleware('authApi:sanctum')->get('/commends', [App\Http\Controllers\records\CommendController::class, 'getCommends']);
Route::middleware('authApi:sanctum')->get('/commends/{commend_id}', [App\Http\Controllers\records\CommendController::class, 'getCommend']);
Route::middleware('authApi:sanctum')->put('/commends/{commend_id}', [App\Http\Controllers\records\CommendController::class, 'updateCommend']);
Route::middleware('authApi:sanctum')->delete('/commends/{commend_id}', [App\Http\Controllers\records\CommendController::class, 'deleteCommend']);
Route::middleware('authApi:sanctum')->get('/commends/player/{player_id}', [App\Http\Controllers\records\CommendController::class, 'getCommendsByPlayer']);
Route::middleware('authApi:sanctum')->get('/commends/staff/{staff_id}', [App\Http\Controllers\records\CommendController::class, 'getCommendsByStaff']);

/**
 * PERMISSION ROUTES
 */
Route::get('/not_authenticated', function () {
    return view('api/not_authenticated');
})->name('NOT_AUTHENTICATED');
Route::get('/not_authorized', function () {
    return view('api/not_authorized');
})->name('NOT_AUTHORIZED');

