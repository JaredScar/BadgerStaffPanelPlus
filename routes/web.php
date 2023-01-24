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

Route::post('/auth/login', function () {});
Route::post('/auth/forgot_password', function () {});

Route::get('/bans', function () {
    // Show the bans for all users to see
});

Route::middleware('auth:sanctum')->get('/profile', function () {
    // Show the logged-in user's profile
})->name("SHOW_PROFILE");

Route::middleware('auth:sanctum')->get('/verified/start', function () {});

