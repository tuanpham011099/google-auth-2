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



Route::middleware(['auth'])->group(function(){
    Route::get('/', function () {
        return view('welcome');
    });
    Route::middleware('2fa.check')->group(function(){
        Route::get('/change-password', [\App\Http\Controllers\UserController::class, 'addPasswordView']);

        Route::post('/change-password', [\App\Http\Controllers\UserController::class, 'addPassword']);

        Route::get('/profile', [\App\Http\Controllers\UserController::class, 'profile']);

        Route::get('/add-phone', [\App\Http\Controllers\UserController::class, 'phone']);

        Route::post('/add-phone', [\App\Http\Controllers\UserController::class, 'addPhone']);

        Route::post('/phone-verification', [\App\Http\Controllers\UserController::class, 'verifyPhone']);

        Route::get('/2fa-send', [\App\Http\Controllers\UserController::class, 'enable2FAView']);

        Route::post('/2fa-enable', [\App\Http\Controllers\UserController::class, 'enable2FA']);

        Route::get('/2fa-disable', [\App\Http\Controllers\UserController::class, 'disable2FAView']);

        Route::post('/2fa-disable', [\App\Http\Controllers\UserController::class, 'disable2FA']);

    });

    Route::get('/logout', [\App\Http\Controllers\UserController::class, 'logout']);
    Route::get('/2fa-verification', [\App\Http\Controllers\UserController::class, 'TwoFAVerificationView']);
    Route::post('/2fa-verification', [\App\Http\Controllers\UserController::class, 'TwoFAVerification']);
});

Route::get('/google',[\App\Http\Controllers\GoogleController::class,'redirectGoogle']);
Route::get('/callback/google', [\App\Http\Controllers\GoogleController::class, 'handleGoogleCallback']);
Route::get('/login',function(){
    return view('login');
})->name('login');

Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
