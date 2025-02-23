<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Jobs\TwoFactoryAuthenticationNotificationJob;
use App\Models\User;
use App\Notifications\TwoFactoryAuthenticationNotification;
use App\Traits\SaveOtpInCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class ResendCodeTwoFactoryAuthAction.
 */
class ResendCodeTwoFactoryAuthAction
{
    use SaveOtpInCache;

    /**
     * @throws InvalidArgumentException
     * @throws CustomeException
     */
    public function execute(Request $request): void
    {
        $cache = Cache::store('database');
        $email = $cache->get($request->ip())[1] ?? null;

        if (!$email) {
            throw new CustomeException('User for this email is not found', 404);
        }

        $user = User::query()->where('email', $email)->first();


        $cache->forget($request->ip());
        $otp2FA = $this->saveOtpInCache($request, $email);

        dispatch(new TwoFactoryAuthenticationNotificationJob($user, $otp2FA));
    }
}
