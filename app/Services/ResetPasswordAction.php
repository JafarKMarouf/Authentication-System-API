<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class ResetPasswordAction.
 */
class ResetPasswordAction
{
    /**
     * @throws InvalidArgumentException
     * @throws CustomeException
     */
    public function execute(Request $request): int
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
