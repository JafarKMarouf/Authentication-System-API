<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Traits\ManageFiles;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

/**
 * Class StoreUserAction.
 */
class StoreUserAction
{
    use ManageFiles;

    public function execute(Request $request): array
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

        // save otp token and email for user in cache table
        $cache = Cache::store('database');
        $cache->put($request->ip(), [$otp->token, $user->email]);

        $user->notify(new EmailVerificationNotification($otp->token));

        $data['token'] = $user->createToken('register')->plainTextToken;
        $data['user'] = $user;

        return $data;
    }
}
