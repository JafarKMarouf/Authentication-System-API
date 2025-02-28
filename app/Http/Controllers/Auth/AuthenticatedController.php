<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Services\AuthenticatedService;
use Illuminate\Http\JsonResponse;

class AuthenticatedController extends Controller
{
    public function __construct(private readonly AuthenticatedService $authService){}

    public function register(StoreUserRequest $request): JsonResponse
    {
        try {
            $request->validated();
            $data =  $this->authService->storeUser($request);
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

    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            $request->validated();
            $data =  $this->authService->loginUser($request);
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
        $this->authService->logoutUser();
        return response()->json([
            'status' => true,
            'message' => 'User is logged out successfully'
        ], 200);
    }
}
