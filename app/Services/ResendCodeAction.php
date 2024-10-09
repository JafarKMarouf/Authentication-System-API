<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Traits\SaveOtpInCache;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Class SendOtpAction.
 */
class ResendCodeAction
{
    use SaveOtpInCache;

    public function execute(Request $request)
    {
        $cache = Cache::store('database');
        $email = $cache->get($request->ip())[1] ?? null;

        if (!$email) {
            throw new CustomeException('User for this email is not found', 404);
        }

        $cache->forget($request->ip());
        $otp = $this->saveOtpInCache($request, $email);

        $user = User::where('email', $email)->first();
        $user->notify(new EmailVerificationNotification($otp));
    }
}
