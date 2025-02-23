<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Jobs\TwoFactoryAuthenticationNotificationJob;
use App\Models\User;
use App\Notifications\TwoFactoryAuthenticationNotification;
use App\Traits\SaveOtpInCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginUserAction.
 */
class LoginUserAction
{
    use SaveOtpInCache;

    /**
     * @throws CustomeException
     */
    public function execute($request): Builder|Model
    {
        $field = filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        if (!Auth::attempt([
            $field => $request->email_or_phone,
            'password' => $request->password
        ])) {
            throw new CustomeException('Invalid Credentials', 403);
        }

        $user = User::query()->where($field, $request->email_or_phone)->first();

        // make otp code for 2FA
        $otp2FA = $this->saveOtpInCache($request, $user->email);

        dispatch(new TwoFactoryAuthenticationNotificationJob($user, $otp2FA));

        return $user;
    }
}
