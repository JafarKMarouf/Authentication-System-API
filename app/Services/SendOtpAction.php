<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use App\Notifications\EmailVerifyNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Class SendOtpAction.
 */
class SendOtpAction
{
    public function execute(Request $request)
    {
        $cache = Cache::store('database');
        $email = $cache->get($request->ip())[1] ?? null;

        if (!$email) {
            throw new CustomeException('User for this email is not found', 404);
        }

        //generate otp
        $otp = new Otp;
        $otp = $otp->generate($email, 'alpha_numeric', 6, 3);

        $cache->forget($request->ip());
        $cache->put($request->ip(), [$otp->token, $email]);

        $user = User::where('email', $email)->first();
        $user->notify(new EmailVerifyNotification($otp->token));
    }
}
