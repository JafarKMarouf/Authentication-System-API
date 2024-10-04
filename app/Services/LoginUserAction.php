<?php

namespace App\Services;

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
    public function execute(Request $request)
    {
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        if (!Auth::attempt([
            $field => $request->login,
            'password' => $request->password
        ])) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'The provided credentials are incorrect'
            ], 403);
        }
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        Cache::store('database')->put("user_ {$user->email}", $user, 1200);
        $data['token'] = $user->createToken('login')->plainTextToken;
        $data['user'] = $user;
        return $data;
    }
}
