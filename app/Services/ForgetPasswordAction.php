<?php

namespace App\Services;

use App\Jobs\ResetPasswordJob;
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
        $user = User::query()->where('email', $request->email)->first();

        $otp = $this->saveOtpInCache($request, $user->email, 10);

        dispatch(new ResetPasswordJob($user, $otp));

        return $otp;
    }
}
