<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\TokenController;
use App\Models\Layout;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
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
    return view('verified/records/commends', array('data' => $data));
})->name("COMMENDS");
Route::middleware('authWeb:web')->get('/verified/records/commends/player/{pid}', function (Request $req, $playerId) {
    $data = [];
    $data['css_path'] = 'widgets/commends';
    $data['view_name'] = 'COMMENDS_SINGLE';
    $data['customize'] = false;
    $data['selected_pid'] = $playerId;
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
    return view('verified/records/warns', array('data' => $data));
})->name("WARNS");
Route::middleware('authWeb:web')->get('/verified/records/warns/player/{pid}', function (Request $req, $playerId) {
    $data = [];
    $data['css_path'] = 'widgets/warns';
    $data['view_name'] = 'WARNS_SINGLE';
    $data['customize'] = false;
    $data['selected_pid'] = $playerId;
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
    return view('verified/records/kicks', array('data' => $data));
})->name("KICKS");
Route::middleware('authWeb:web')->get('/verified/records/kicks/player/{pid}', function (Request $req, $playerId) {
    $data = [];
    $data['css_path'] = 'widgets/kicks';
    $data['view_name'] = 'KICKS_SINGLE';
    $data['customize'] = false;
    $data['selected_pid'] = $playerId;
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
    return view('verified/records/bans', array('data' => $data));
})->name("BANS");
Route::middleware('authWeb:web')->get('/verified/records/bans/player/{pid}', function (Request $req, $playerId) {
    $data = [];
    $data['css_path'] = 'widgets/bans';
    $data['view_name'] = 'BANS_SINGLE';
    $data['customize'] = false;
    $data['selected_pid'] = $playerId;
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
    return view('verified/records/records', array('data' => $data));
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
Route::middleware('authWeb:web')->get('/verified/players', function () {
    $data = [];
    $data['css_path'] = 'players';
    $data['view_name'] = 'ALL_PLAYERS';
    $data['customize'] = false;
    return view('verified/players/all_players', array('data' => $data));
})->name("PLAYERS");

/**
 * MANAGEMENT
 */
Route::middleware('authWeb:web')->get('/verified/management/settings', function () {
    $data = [];
    $data['css_path'] = 'verified/management';
    $data['view_name'] = 'SETTINGS';
    $data['customize'] = false;
    return view('verified/management/settings', array('data' => $data));
})->name("SETTINGS");
Route::middleware('authWeb:web')->get('/verified/management/manage_tokens', function () {
    $data = [];
    $data['css_path'] = 'verified/management';
    $data['view_name'] = 'TOKEN_MANAGEMENT';
    $data['customize'] = false;
    return view('verified/management/manage_tokens', array('data' => $data));
})->name("TOKEN_MANAGEMENT");
Route::middleware('authWeb:web')->get('/verified/management/manage_staff', function () {
    $data = [];
    $data['css_path'] = 'verified/management';
    $data['view_name'] = 'STAFF_MANAGEMENT';
    $data['customize'] = false;
    
    // Get staff statistics and data
    $staffController = new \App\Http\Controllers\StaffController();
    $data['staff_statistics'] = $staffController->getStaffStatistics();
    $data['staff_members'] = $staffController->getStaff();
    
    return view('verified/management/manage_staff', array('data' => $data));
})->name("STAFF_MANAGEMENT");
Route::middleware('authWeb:web')->get('/verified/management/manage_roles', function () {
    $data = [];
    $data['css_path'] = 'verified/management';
    $data['view_name'] = 'ROLE_MANAGEMENT';
    $data['customize'] = false;
    return view('verified/management/manage_roles', array('data' => $data));
})->name("ROLE_MANAGEMENT");
Route::middleware('authWeb:web')->get('/verified/signout', [LogoutController::class, 'logout'])->name("SIGN_OUT");

/**
 * DASHBOARD
 */
Route::middleware('authWeb:web')->get('/verified/dashboard', [DashboardController::class, 'index'])->name("DASHBOARD");
Route::middleware('authWeb:web')->put('/verified/dashboard/save', [DashboardController::class, 'save']);
Route::middleware('authWeb:web')->post('/verified/dashboard/add_widget', [DashboardController::class, 'add_widget']);
Route::middleware('authWeb:web')->post('/verified/dashboard/create', [DashboardController::class, 'createDashboard']);
Route::middleware('authWeb:web')->delete('/verified/dashboard/delete', [DashboardController::class, 'deleteDashboard']);
Route::middleware('authWeb:web')->get('/verified/dashboard/layout', [DashboardController::class, 'getDashboardLayout']);

// Test route for debugging
Route::middleware('authWeb:web')->get('/verified/test-auth', function() {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user() ? auth()->user()->staff_username : null,
        'session_id' => session()->getId(),
        'staff_id' => session()->get('staff_id')
    ]);
});

Route::middleware('authWeb:web')->post('/verified/management/tokens', [TokenController::class, 'doCreateToken']);
Route::middleware('authWeb:web')->delete('/verified/management/tokens/{tokenId}', [TokenController::class, 'doDeleteToken']);
