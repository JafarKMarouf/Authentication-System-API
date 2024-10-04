<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

/**
 * Class VerifyEmailAction.
 */
class VerifyEmailAction
{
    protected $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }
    public function execute(Request $request)
    {
        $valid = $this->otp->validate($request->email, $request->otp);

        if (!$valid->status) {
            throw new CustomeException('OTP is invaild', 401);
        }
        $user = User::where('email', $request->email)->first();
        $user->email_verified_at = now();
        $user->save();
        return $user;
    }
}
