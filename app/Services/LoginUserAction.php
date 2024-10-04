<?php

namespace App\Services;

use App\Exceptions\CustomeException;
use App\Exceptions\InvalidPasswordException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

/**
 * Class LoginUserAction.
 */
class LoginUserAction
{
    public function execute(Request $request): array
    {
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        if (!Auth::attempt([
            $field => $request->login,
            'password' => $request->password
        ])) {
            throw new CustomeException('Invaild Credentials', 403);
        }
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        Cache::store('database')->put("user_ {$user->email}", $user, 1200);
        $data['token'] = $user->createToken('login')->plainTextToken;
        $data['user'] = $user;
        return $data;
    }
}
