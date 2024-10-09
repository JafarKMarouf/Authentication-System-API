<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix'  => 'auth'], function () {
    Route::controller(AuthController::class)
        ->group(function () {
            Route::post('register', 'register')->name('register');
            Route::post('login', 'login')->name('login');
            Route::get('logout', 'logout')
                ->middleware('auth:sanctum')
                ->name('logout');
        });
    Route::post('forgetpassword', ForgetPasswordController::class);

    Route::controller(EmailVerificationController::class)
        ->group(function () {
            Route::post('verify', 'verify')->name('verificationcode');
            Route::post('resend', 'resendCode')->name('sendCode');
        });
});
