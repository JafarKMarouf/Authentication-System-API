<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgetPasswordController;
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

    Route::controller(AuthController::class)
        ->group(function () {
            Route::post('register', 'register')->name('register');
            Route::post('login', 'login')->name('login');

            Route::middleware('auth:sanctum')->group(function () {
                Route::get('logout', 'logout')
                    ->name('logout');

                Route::get('refresh-token', 'refreshToken')
                    ->middleware('ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value)
                    ->name('refreshToken');
            });
            // Route::get('/test', 'test');
        });

    Route::controller(EmailVerificationController::class)
        ->group(function () {
            Route::post('verify-email', 'verifyEmail')->name('verificationcode');
            Route::post('resend-code', 'resendCode')
                ->middleware(['throttle:tenMinutes'])
                ->name('sendCode');
        });

    Route::controller(TwoFactoryAuthenticationController::class)
        ->group(function () {
            Route::post('verify-2FA', 'verify2FAOTP')->name('verify2FAOTP');
            Route::post('resend-2FA', 'resend2FAOTP')->name('resend2FAOTP');
        });

    Route::controller(ForgetPasswordController::class)
        ->group(function () {
            Route::post('forgetpassword', 'forgetPassword')->name('forget password');
            Route::post('resetpassword', 'resetPassword')->name('reset password');
        });
});
