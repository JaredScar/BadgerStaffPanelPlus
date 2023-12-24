<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Models\Ban;
use App\Models\Commend;
use App\Models\Kick;
use Illuminate\Console\View\Components\Warn;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
 * @mixin Builder
 */
Route::get('/', function () {
    $data = [];
    $data['css_path'] = 'login/start';
    $data['view_name'] = 'START';
    $data['customize'] = false;
    $data['captcha'] = env('USE_CAPTCHA', false);
    return view('login/start', array('data' => $data));
})->name('START');

Route::get('/forgot_password', function () {
    $data = [];
    $data['css_path'] = 'login/forgot_password';
    $data['view_name'] = 'FORGOT_PASSWORD';
    $data['customize'] = false;
    $data['captcha'] = env('USE_CAPTCHA', false);
    return view('login/forgot_password', array('data' => $data));
})->name("FORGOT_PASSWORD");

Route::post('/auth/login', [LoginController::class, 'authenticateWeb'])->name("LOGIN_SUBMIT");
Route::post('/auth/login/discord', [LoginController::class, 'authenticateDiscord'])->name("LOGIN_DISCORD_SUBMIT");
Route::post('/auth/forgot_password', function () {})->name("FORGOT_PASSWORD_SUBMIT");

Route::get('/bans', function () {
    // Show the bans for all users to see
    if (env('PUBLIC_BANS')) {
        // They get to see the bans list
    } else {
        // Take them back to not authorized page
    }
    // TODO
})->name("SHOW_BANS");

Route::middleware('authWeb:web')->get('/profile', function () {
    // Show the logged-in user's profile
})->name("SHOW_PROFILE");

/**
 * COMMENDS
 */
Route::middleware('authWeb:web')->get('/verified/records/commends', function () {
    $data = [];
    $data['css_path'] = 'widgets/commends';
    $data['view_name'] = 'COMMENDS';
    $data['customize'] = false;
    $commendData = Commend::with('getPlayer')->with('getStaff')->get();
    $data['data'] = $commendData;
    return view('verified/records/commends', array('data' => $data));
})->name("COMMENDS");
Route::middleware('authWeb:web')->get('/verified/records/commends/player/{pid}', function (Request $req, $playerId) {
    $data = [];
    $data['css_path'] = 'widgets/commends';
    $data['view_name'] = 'COMMENDS_SINGLE';
    $data['customize'] = false;
    $data['selected_pid'] = $playerId;
    $commendData = Commend::with('getPlayer')->with('getStaff')->where('player_id', $playerId)->get();
    $data['data'] = $commendData;
    return view('verified/records/commends', array('data' => $data));
})->name('COMMENDS_SINGLE');

/**
 * WARNS
 */
Route::middleware('authWeb:web')->get('/verified/records/warns', function () {
    $data = [];
    $data['css_path'] = 'widgets/warns';
    $data['view_name'] = 'WARNS';
    $data['customize'] = false;
    $warnData = Warn::with('getPlayer')->with('getStaff')->get();
    $data['data'] = $warnData;
    return view('verified/records/warns', array('data' => $data));
})->name("WARNS");
Route::middleware('authWeb:web')->get('/verified/records/warns/player/{pid}', function (Request $req, $playerId) {
    $data = [];
    $data['css_path'] = 'widgets/warns';
    $data['view_name'] = 'WARNS_SINGLE';
    $data['customize'] = false;
    $data['selected_pid'] = $playerId;
    $warnData = Warn::with('getPlayer')->with('getStaff')->where('player_id', $playerId)->get();
    $data['data'] = $warnData;
    return view('verified/records/warns', array('data' => $data));
})->name('WARNS_SINGLE');

/**
 * KICKS
 */
Route::middleware('authWeb:web')->get('/verified/records/kicks', function () {
    $data = [];
    $data['css_path'] = 'widgets/kicks';
    $data['view_name'] = 'KICKS';
    $data['customize'] = false;
    $kickData = Kick::with('getPlayer')->with('getStaff')->get();
    $data['data'] = $kickData;
    return view('verified/records/kicks', array('data' => $data));
})->name("KICKS");
Route::middleware('authWeb:web')->get('/verified/records/kicks/player/{pid}', function (Request $req, $playerId) {
    $data = [];
    $data['css_path'] = 'widgets/kicks';
    $data['view_name'] = 'KICKS_SINGLE';
    $data['customize'] = false;
    $data['selected_pid'] = $playerId;
    $kickData = Kick::with('getPlayer')->with('getStaff')->where('player_id', $playerId)->get();
    $data['data'] = $kickData;
    return view('verified/records/kicks', array('data' => $data));
})->name("KICKS_SINGLE");

/**
 * BANS
 */
Route::middleware('authWeb:web')->get('/verified/records/bans', function () {
    $data = [];
    $data['css_path'] = 'widgets/bans';
    $data['view_name'] = 'BANS';
    $data['customize'] = false;
    $banData = Ban::with('getPlayer')->with('getStaff')->get();
    $data['data'] = $banData;
    return view('verified/records/bans', array('data' => $data));
})->name("BANS");
Route::middleware('authWeb:web')->get('/verified/records/bans/player/{pid}', function (Request $req, $playerId) {
    $data = [];
    $data['css_path'] = 'widgets/bans';
    $data['view_name'] = 'BANS_SINGLE';
    $data['customize'] = false;
    $data['selected_pid'] = $playerId;
    $banData = Ban::with('getPlayer')->with('getStaff')->where('player_id', $playerId)->get();
    $data['data'] = $banData;
    return view('verified/records/bans', array('data' => $data));
})->name("BANS_SINGLE");

/**
 * TRUSTSCORES
 */
Route::middleware('authWeb:web')->get('/verified/records/trustscores', function () {
    $data = [];
    $data['css_path'] = 'widgets/trustscores';
    $data['view_name'] = 'TRUSTSCORES';
    $data['customize'] = false;
    return view('verified/records/trustscores', array('data' => $data));
})->name("TRUSTSCORES");
Route::middleware('authWeb:web')->get('/verified/records/trustscores/player/{pid}', function (Request $req, $playerId) {
    $data = [];
    $data['css_path'] = 'widgets/trustscores';
    $data['view_name'] = 'TRUSTSCORES_SINGLE';
    $data['customize'] = false;
    $data['selected_pid'] = $playerId;
    return view('verified/records/trustscores', array('data' => $data));
})->name("TRUSTSCORES_SINGLE");

/**
 * RECORDS
 */
Route::middleware('authWeb:web')->get('/verified/records', function () {
    $data = [];
    $data['css_path'] = 'widgets/records';
    $data['view_name'] = 'RECORDS';
    $data['customize'] = false;
    return view('verified/records', array('data' => $data));
})->name("RECORDS");
Route::middleware('authWeb:web')->get('/verified/records/player/{pid}', function (Request $req, $playerId) {
    $data = [];
    $data['css_path'] = 'widgets/records';
    $data['view_name'] = 'RECORDS_SINGLE';
    $data['customize'] = false;
    $data['selected_pid'] = $playerId;
    return view('verified/records', array('data' => $data));
})->name("RECORDS_SINGLE");

/**
 * PLAYERS
 */
Route::middleware('authWeb:web')->get('/verified/players/today', function () {
    $data = [];
    $data['css_path'] = 'players';
    $data['view_name'] = 'PLAYERS_TODAY';
    $data['customize'] = false;
    return view('verified/players/today', array('data' => $data));
})->name("PLAYERS_TODAY");
Route::middleware('authWeb:web')->get('/verified/players/week', function () {
    $data = [];
    $data['css_path'] = 'players';
    $data['view_name'] = 'PLAYERS_WEEKLY';
    $data['customize'] = false;
    return view('verified/players/weekly', array('data' => $data));
})->name("PLAYERS_WEEKLY");
Route::middleware('authWeb:web')->get('/verified/players/month', function () {
    $data = [];
    $data['css_path'] = 'players';
    $data['view_name'] = 'PLAYERS_MONTHLY';
    $data['customize'] = false;
    return view('verified/players/monthly', array('data' => $data));
})->name("PLAYERS_MONTHLY");
Route::middleware('authWeb:web')->get('/verified/players', function () {})->name("PLAYERS");

/**
 * MANAGEMENT
 */
Route::middleware('authWeb:web')->get('/verified/management/settings', function () {})->name("SETTINGS");
Route::middleware('authWeb:web')->get('/verified/management/manage', function () {})->name("MANAGE_STAFF");
Route::middleware('authWeb:web')->get('/verified/signout', [LogoutController::class, 'logout'])->name("SIGN_OUT");

/**
 * DASHBOARD
 */
Route::middleware('authWeb:web')->get('/verified/dashboard', function () {
    $data = [];
    $data['css_path'] = 'verified/dashboard';
    $data['view_name'] = 'DASHBOARD';
    $data['customize'] = true;
    return view('verified/dashboard', array('data' => $data));
})->name("DASHBOARD");
