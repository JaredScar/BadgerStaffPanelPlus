<?php

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
    return view('login/start', array('data' => $data));
})->name('START');

Route::get('/forgot_password', function () {
    $data = [];
    $data['css_path'] = 'login/forgot_password';
    $data['view_name'] = 'FORGOT_PASSWORD';
    return view('login/forgot_password', array('data' => $data));
})->name("FORGOT_PASSWORD");

Route::post('/auth/login', function () {})->name("LOGIN_SUBMIT");
Route::post('/auth/forgot_password', function () {})->name("FORGOT_PASSWORD_SUBMIT");

Route::get('/bans', function () {
    // Show the bans for all users to see
})->name("SHOW_BANS");

Route::middleware('auth:sanctum')->get('/profile', function () {
    // Show the logged-in user's profile
})->name("SHOW_PROFILE");

Route::get('/verified/dashboard', function () {
    $data = [];
    $data['css_path'] = 'verified/dashboard';
    $data['view_name'] = 'DASHBOARD_PAGE';
    return view('verified/dashboard', array('data' => $data));
})->name("DASHBOARD_PAGE");
