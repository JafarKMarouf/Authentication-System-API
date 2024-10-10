<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use App\Notifications\TwoFactoryAuthenticationNotification;
use App\Traits\SaveOtpInCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginUserAction.
 */
class LoginUserAction
{
    use SaveOtpInCache;

    public function execute(Request $request)
    {
        $field = filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        if (!Auth::attempt([
            $field => $request->email_or_phone,
            'password' => $request->password
        ])) {
            throw new CustomeException('Invaild Credentials', 403);
        }

        $user = User::where($field, $request->email_or_phone)->first();

        // make otp code for 2FA
        $Otp2FA = $this->saveOtpInCache($request, $user->email);
        $user->notify(new TwoFactoryAuthenticationNotification($Otp2FA));

        return $user;
    }
}
