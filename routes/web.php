<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
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

Route::get('/', function () {
    $data = [];
    $data['css_path'] = 'login/start';
    $data['view_name'] = 'START';
    $data['customize'] = false;
    return view('login/start', array('data' => $data));
})->name('START');

Route::get('/forgot_password', function () {
    $data = [];
    $data['css_path'] = 'login/forgot_password';
    $data['view_name'] = 'FORGOT_PASSWORD';
    $data['customize'] = false;
    return view('login/forgot_password', array('data' => $data));
})->name("FORGOT_PASSWORD");

Route::post('/auth/login', [LoginController::class, 'authenticate'])->name("LOGIN_SUBMIT");
Route::post('/auth/login/discord', [LoginController::class, 'authenticateDiscord'])->name("LOGIN_DISCORD_SUBMIT");
Route::post('/auth/forgot_password', function () {})->name("FORGOT_PASSWORD_SUBMIT");

Route::get('/bans', function () {
    // Show the bans for all users to see
})->name("SHOW_BANS");

Route::middleware('auth:web')->get('/profile', function () {
    // Show the logged-in user's profile
})->name("SHOW_PROFILE");

Route::middleware('auth:web')->get('/verified/records/commends', function () {})->name("COMMENDS");
Route::middleware('auth:web')->get('/verified/records/warns', function () {})->name("WARNS");
Route::middleware('auth:web')->get('/verified/records/kicks', function () {})->name("KICKS");
Route::middleware('auth:web')->get('/verified/records/bans', function () {})->name("BANS");
Route::middleware('auth:web')->get('/verified/records/trustscores', function () {})->name("TRUSTSCORES");
Route::middleware('auth:web')->get('/verified/records', function () {})->name("RECORDS");

Route::middleware('auth:web')->get('/verified/players/today', function () {})->name("PLAYERS_TODAY");
Route::middleware('auth:web')->get('/verified/players/week', function () {})->name("PLAYERS_WEEKLY");
Route::middleware('auth:web')->get('/verified/players/month', function () {})->name("PLAYERS_MONTHLY");
Route::middleware('auth:web')->get('/verified/players', function () {})->name("PLAYERS");

Route::middleware('auth:web')->get('/verified/management/settings', function () {})->name("SETTINGS");
Route::middleware('auth:web')->get('/verified/management/manage', function () {})->name("MANAGE_STAFF");
Route::middleware('auth:web')->get('/verified/signout', [LogoutController::class, 'logout'])->name("SIGN_OUT");

Route::get('/verified/dashboard', function () {
    $data = [];
    $data['css_path'] = 'verified/dashboard';
    $data['view_name'] = 'DASHBOARD';
    $data['customize'] = true;
    return view('verified/dashboard', array('data' => $data));
})->name("DASHBOARD");
