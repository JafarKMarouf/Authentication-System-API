<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\LoginUserAction;
use App\Services\StoreUserAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request, StoreUserAction $storeUserAction): JsonResponse
    {
        try {
            $request->validated();
            $data =  $storeUserAction->execute($request);
            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => 'User created successfully and Send Verify Code'
            ], 201);
        } catch (CustomeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCustomCode());
        }
    }

    public function login(LoginUserRequest $request, LoginUserAction $loginUserAction): JsonResponse
    {
        try {
            $request->validated();
            $data =  $loginUserAction->execute($request);
            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => 'OTP sent to your email for 2FA'
            ], 200);
        } catch (CustomeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCustomCode());
        }
    }

    public function logout(): JsonResponse
    {
        $user = request()->user();
        Cache::store('database')->forget("user_ {$user->email}");
        $user->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User is logged out successfully'
        ], 200);
    }
}
