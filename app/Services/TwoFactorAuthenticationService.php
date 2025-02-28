<?php

namespace App\Services;

use App\Enums\TokenAbility;
use App\Exceptions\CustomeException;
use App\Jobs\TwoFactoryAuthenticationJob;
use App\Models\User;
use App\Traits\SaveOtpInCache;
use Carbon\Carbon;
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

        $data['user'] = $user;
        $data['accessToken'] = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.expiration'))
        )->plainTextToken;

        $data['refreshToken'] = $user->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
        )->plainTextToken;

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
