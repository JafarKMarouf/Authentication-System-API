<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use App\Notifications\EmailVerifyNotification;
use Illuminate\Http\Request;

/**
 * Class SendOtpAction.
 */
class SendOtpAction
{
    public function execute(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            throw new CustomeException('User for this email not found', 404);
        }
        $user->notify(new EmailVerifyNotification());
    }
}
