<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;

/**
 * Class ForgetPasswordAction.
 */
class ForgetPasswordAction
{
    public function excetue(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        $otp = new Otp;
        $otp = $otp->generate($user->email, 'alpha_numeric', 6, 10);

        $user->notify(new ResetPasswordNotification($otp->token));
        return $otp->token;
    }
}
