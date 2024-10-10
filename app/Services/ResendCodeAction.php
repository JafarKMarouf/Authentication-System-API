<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Traits\SaveOtpInCache;
use Carbon\Carbon;
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

        $user = User::where('email', $email)->first();

        $attempts = $user->verification_attempts;
        $sent_at = $user->verification_sent_at;

        if ($attempts >= 2 && now()->diffInMinutes($sent_at) < 10) {
            throw new CustomeException('Too many attempts. Please try again after 10 minutes', 429);
        }

        if ($attempts >= 2) {
            $user->verification_attempts = 0;
        }

        $user->verification_attempts++;
        $user->verification_sent_at = now();

        $user->save();

        $cache->forget($request->ip());
        $otp = $this->saveOtpInCache($request, $email);

        $user->notify(new EmailVerificationNotification($otp));
    }
}
