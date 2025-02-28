<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Jobs\ResetPasswordJob;
use App\Models\User;
use App\Traits\SaveOtpInCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Psr\SimpleCache\InvalidArgumentException;

class ForgetPasswordService
{
    use SaveOtpInCache;

    public function forgetPassword(Request $request)
    {
        $user = User::query()->where('email', $request->email)->first();
        $otp = $this->saveOtpInCache($request, $user->email, 10);
        dispatch(new ResetPasswordJob($user, $otp));

        return $otp;
    }

    /**
     * @throws InvalidArgumentException
     * @throws CustomeException
     */
    public function resetPassword(Request $request): int
    {
        $cache = Cache::store('database');
        $otp =  $cache->get($request->ip())[0] ?? null;
        $email = $cache->get($request->ip())[1] ?? null;

        if ($otp != $request->otp) {
            throw new CustomeException('OTP is invalid', 401);
        }
        $user = User::query()->where('email', $email)
            ->update([
                'password' => Hash::make($request->password)
            ]);

        $cache->forget($request->ip());

        return $user;
    }
}
