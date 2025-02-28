<?php

namespace App\Services;

use App\Enums\TokenAbility;
use App\Exceptions\CustomeException;
use App\Jobs\SendWelcomeEmailVerificationJob;
use App\Jobs\TwoFactoryAuthenticationJob;
use App\Models\User;
use App\Traits\ManageFiles;
use App\Traits\SaveOtpInCache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use \Exception;

class AuthenticatedService
{
    use ManageFiles;
    use SaveOtpInCache;

    /**
     * @throws Exception
     */
    public function storeUser($request){
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

        dispatch(new SendWelcomeEmailVerificationJob($user, $otp));
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

    /**
     * @throws CustomeException
     * @throws Exception
     */
    public function loginUser($request): Builder|Model
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

        dispatch(new TwoFactoryAuthenticationJob($user, $otp2FA));
        return $user;
    }

    public function logoutUser(): void
    {
        /** @var $user */
        $user = request()->user();
        Cache::store('database')->forget("user_ {$user->email}");
        $user->tokens()->delete();
    }
}
