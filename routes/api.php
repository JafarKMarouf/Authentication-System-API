<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\Auth\TwoFactoryAuthenticationController;
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
    Route::controller(AuthenticatedController::class)->group(function () {
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
        Route::middleware('auth:sanctum')->get('logout','logout')->name('logout');
    });

    Route::controller(RefreshTokenController::class)->group(function () {
        Route::middleware([
            'auth:sanctum',
            'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value
        ])->get('refresh-token', 'refreshToken')->name('refreshToken');
    });

    Route::controller(EmailVerificationController::class)->group(function () {
        Route::post('verify-email', 'verifyEmail')->name('verifyEmail');
        Route::middleware(['throttle:tenMinutes'])->post('resend-code', 'resendCode')->name('sendCode');
    });

    Route::controller(TwoFactoryAuthenticationController::class)->group(function () {
        Route::post('verify-2FA', 'verify2FAOtp')->name('verify2FAOtp');
        Route::post('resend-2FA', 'resend2FAOtp')->name('resend2FAOtp');
    });

    Route::controller(ForgetPasswordController::class)->group(function () {
        Route::post('forget-password', 'forgetPassword')->name('forget-password');
        Route::post('reset-password', 'resetPassword')->name('reset-password');
    });
});
