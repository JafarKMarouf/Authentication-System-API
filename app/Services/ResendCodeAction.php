<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use App\Notifications\ResendVerificationNotification;
use App\Traits\SaveOtpInCache;
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

        $user = User::where('email', $email)->first();

        $otp = $this->saveOtpInCache($request, $email);

        $user->notify(new ResendVerificationNotification($otp));
    }
}
