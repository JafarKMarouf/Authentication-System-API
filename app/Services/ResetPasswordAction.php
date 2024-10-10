<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

/**
 * Class ResetPasswordAction.
 */
class ResetPasswordAction
{
    public function execute(Request $request)
    {
        $cache = Cache::store('database');
        $otp =  $cache->get($request->ip())[0] ?? null;
        $email = $cache->get($request->ip())[1] ?? null;

        if ($otp != $request->otp) {
            throw new CustomeException('OTP is invalid', 401);
        }
        $user = User::where('email', $email)->update([
            'password' => Hash::make($request->password)
        ]);

        $cache->forget($request->ip());

        return $user;
    }
}
