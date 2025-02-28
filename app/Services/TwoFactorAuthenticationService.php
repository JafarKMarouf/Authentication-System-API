<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Jobs\TwoFactoryAuthenticationJob;
use App\Models\User;
use App\Traits\SaveOtpInCache;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class TwoFactorAuthenticationService
{
    use SaveOtpInCache;

    /**
     * @throws InvalidArgumentException
     * @throws CustomeException
     */
    public function verify($request): array
    {
        $cache = Cache::store('database');

        $otp =  $cache->get($request->ip())[0] ?? null;
        $email = $cache->get($request->ip())[1] ?? null;

        if ($otp != $request->otp) {
            throw new CustomeException('OTP is invalid', 401);
        }

        $cache->forget($request->ip());

        $user = User::query()->where('email', $email)->first();

        $token = $user->createToken('token-name')->plainTextToken;
        $data['user'] = $user;
        $data['token'] = $token;
        return $data;
    }

    /**
     * @throws InvalidArgumentException
     * @throws CustomeException
     * @throws \Exception
     */
    public function resendCode($request):void{
        $cache = Cache::store('database');
        $email = $cache->get($request->ip())[1] ?? null;
        if (!$email) {
            throw new CustomeException('User for this email is not found', 404);
        }

        $user = User::query()->where('email', $email)->first();

        $cache->forget(request()->ip());
        $otp2FA = $this->saveOtpInCache(request(), $email);

        dispatch(new TwoFactoryAuthenticationJob($user, $otp2FA));
    }
}
