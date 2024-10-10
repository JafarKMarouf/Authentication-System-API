<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginUserAction.
 */
class LoginUserAction
{
    public function execute(Request $request): array
    {
        $field = filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        if (!Auth::attempt([
            $field => $request->email_or_phone,
            'password' => $request->password
        ])) {
            throw new CustomeException('Invaild Credentials', 403);
        }

        $user = User::where($field, $request->email_or_phone)->first();
        $data['token'] = $user->createToken('login')->plainTextToken;
        $data['user'] = $user;

        return $data;
    }
}
