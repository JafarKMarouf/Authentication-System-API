<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Enums\TokenAbility;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Class RefreshTokenAction.
 */
class RefreshTokenAction
{
    public function exceute(Request $request)
    {
        $request->user()->tokens()->delete();
        $accessToken = $request->user()->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.expiration'))
        )->plainTextToken;

        $refreshToken = $request->user()->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
        )->plainTextToken;

        $data['accessToken'] = $accessToken;
        $data['refreshToken'] = $refreshToken;
        return $data;
    }
}
