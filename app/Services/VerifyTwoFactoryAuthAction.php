<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Class TwoFactoryAuthAction.
 */
class VerifyTwoFactoryAuthAction
{
    public function execute(Request $request)
    {
        $cache = Cache::store('database');

        $otp =  $cache->get($request->ip())[0] ?? null;
        $email = $cache->get($request->ip())[1] ?? null;

        if ($otp != $request->otp) {
            throw new CustomeException('OTP is invalid', 401);
        }

        $cache->forget($request->ip());

        $user = User::where('email', $email)->first();

        $token = $user->createToken('token-name')->plainTextToken;
        $data['user'] = $user;
        $data['token'] = $token;
        return $data;
    }
}
