<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Services\LoginUserAction;
use App\Services\StoreUserAction;
use Illuminate\Http\JsonResponse;

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
                'message' => 'User created successfully and Send Verfiy Code'
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
                'message' => 'User Logged successfully'
            ], 200);
        } catch (CustomeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCustomCode());
        }
    }
}
