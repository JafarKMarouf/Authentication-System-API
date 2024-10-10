<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use App\Notifications\TwoFactoryAuthenticationNotification;
use App\Traits\SaveOtpInCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Class ResendCodeTwoFactoryAuthAction.
 */
class ResendCodeTwoFactoryAuthAction
{
    use SaveOtpInCache;
    public function execute(Request $request)
    {
        $cache = Cache::store('database');
        $email = $cache->get($request->ip())[1] ?? null;

        if (!$email) {
            throw new CustomeException('User for this email is not found', 404);
        }

        $user = User::where('email', $email)->first();


        $cache->forget($request->ip());
        $OTP2FA = $this->saveOtpInCache($request, $email);

        $user->notify(new TwoFactoryAuthenticationNotification($OTP2FA));
    }
}
