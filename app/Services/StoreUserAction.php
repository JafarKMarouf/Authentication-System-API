<?php

namespace App\Services;

use App\Enums\TokenAbility;
use App\Jobs\SendEmailVerificationJob;
use App\Models\User;
use App\Traits\ManageFiles;
use App\Traits\SaveOtpInCache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class StoreUserAction.
 */
class StoreUserAction
{
    use ManageFiles;
    use SaveOtpInCache;

    public function execute($request): array
    {
        if ($request->hasFile('profile_photo')) {
            $photo = $this->uploadFile($request->profile_photo, 'profile_photos');
        }
        /** @var $photo */
        $user = User::query()->create([
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'profile_photo' => $photo
        ]);
        $user->refresh();
        $otp = $this->saveOtpInCache($request, $user->email);

        dispatch(new SendEmailVerificationJob($user, $otp));
        $data['user'] = $user;

        assert($user instanceof User);
        $data['accessToken'] = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.expiration'))
        )->plainTextToken;

        $data['refreshToken'] = $user->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
        )->plainTextToken;

        return $data;
    }
}
