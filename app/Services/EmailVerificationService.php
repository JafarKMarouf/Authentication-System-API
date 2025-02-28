<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Jobs\ResendVerificationJob;
use App\Models\User;
use App\Traits\SaveOtpInCache;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class EmailVerificationService
{
    use SaveOtpInCache;

    /**
     * @throws InvalidArgumentException
     * @throws CustomeException
     */
    public function verify($request): int
    {
        $cache = Cache::store('database');

        $otp =  $cache->get($request->ip())[0] ?? null;
        $email = $cache->get($request->ip())[1] ?? null;

        if ($otp != $request->otp) {
            throw new CustomeException('OTP is invalid', 401);
        }

        $user = User::query()->where('email', $email)->update([
            'email_verified_at' => now()
        ]);

        $cache->forget($request->ip());
        return $user;
    }

    /**
     * @throws InvalidArgumentException
     * @throws CustomeException
     */
    public function resend($request): void{
        $cache = Cache::store('database');
        $email = $cache->get($request->ip())[1] ?? null;

        if (!$email) {
            throw new CustomeException('User for this email is not found', 404);
        }

        $user = User::query()->where('email', $email)->first();

        $otp = $this->saveOtpInCache($request, $email);

        dispatch(new ResendVerificationJob($user, $otp));
    }
}
