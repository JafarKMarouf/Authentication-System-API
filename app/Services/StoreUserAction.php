<?php

namespace App\Services;

use App\Mail\WelcomeMail;
use App\Models\User;
use App\Notifications\EmailVerifyNotification;
use App\Traits\ManageFiles;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Class StoreUserAction.
 */
class StoreUserAction
{
    use ManageFiles;
    protected $otp;

    public function execute(Request $request)
    {
        if ($request->hasFile('profile_photo')) {
            $photo = $this->uploadFile($request->profile_photo, 'profile_photos');
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'profile_photo' => $photo
        ]);

        //generate otp
        $otp = new Otp;
        $otp = $otp->generate($user->email, 'alpha_numeric', 6, 3);
        $user->notify(new EmailVerifyNotification($otp->token));

        $data['token'] = $user->createToken('register')->plainTextToken;
        $data['user'] = $user;

        return $data;
    }
}
