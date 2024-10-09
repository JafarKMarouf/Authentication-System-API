<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Traits\SaveOtpInCache;
use Illuminate\Http\Request;

/**
 * Class ForgetPasswordAction.
 */
class ForgetPasswordAction
{
    use SaveOtpInCache;

    public function execute(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        $otp = $this->saveOtpInCache($request, $user->email, 10);

        $user->notify(new ResetPasswordNotification($otp));
        return $otp;
    }
}
