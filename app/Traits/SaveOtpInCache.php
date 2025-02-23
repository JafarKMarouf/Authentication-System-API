<?php

namespace App\Traits;

use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

trait SaveOtpInCache
{
    public function saveOtpInCache(Request $request, $email, $validate = 3)
    {
        $otp = new Otp;
        $otp = $otp->generate(
            $email,
            'alpha_numeric',
            6,
            $validate
        );
        $cache = Cache::store('database');
        $cache->put($request->ip(), [$otp->token, $email]);
        return $otp->token;
    }
}
