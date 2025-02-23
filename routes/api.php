<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Auth\AuthController;
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
    Route::controller(AuthController::class)->group(function () {
            Route::post('register', 'register')
                ->name('register');
            Route::post('login', 'login')
                ->name('login');
            Route::get('logout', 'logout')
                ->middleware('auth:sanctum')
                ->name('logout');
        });

    Route::controller(RefreshTokenController::class)
        ->group(function () {
            Route::get('refresh-token', 'refreshToken')
                ->middleware([
                    'auth:sanctum',
                    'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value
                ])->name('refreshToken');
        });

    Route::controller(EmailVerificationController::class)
        ->group(function () {
            Route::post('verify-email', 'verifyEmail')->name('verifyEmail');
            Route::post('resend-code', 'resendCode')
                ->middleware(['throttle:tenMinutes'])
                ->name('sendCode');
        });

    Route::controller(TwoFactoryAuthenticationController::class)
        ->group(function () {
            Route::post('verify-2FA', 'verify2FaOtp')->name('verify2FaOtp');
            Route::post('resend-2FA', 'resend2FaOtp')->name('resend2FaOtp');
        });

    Route::controller(ForgetPasswordController::class)
        ->group(function () {
            Route::post('forget-password', 'forgetPassword')->name('forget password');
            Route::post('reset-password', 'resetPassword')->name('reset password');
        });
});
