<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class TwoFactoryAuthAction.
 */
class VerifyTwoFactoryAuthAction
{
    /**
     * @throws InvalidArgumentException
     * @throws CustomeException
     */
    public function execute($request): array
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
}
