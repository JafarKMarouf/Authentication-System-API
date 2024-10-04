<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use App\Notifications\EmailVerifyNotification;
use App\Traits\ManageFiles;
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
        $cache = Cache::store('database');
        if ($cache->has("user_{$request->email}")) {
            throw new CustomeException('User already exists', 400);
        }

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
        $cache->put("user_ {$user->email}", $user, 1200);

        $user->notify(new EmailVerifyNotification());

        $data['token'] = $user->createToken('register')->plainTextToken;
        $data['user'] = $user;

        return $data;
    }
}