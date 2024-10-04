<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\LoginUserAction;
use App\Services\StoreUserAction;
use App\Traits\ManageFiles;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'false',
                'data' => [],
                'message' => $e->getMessage()
            ], 500);
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'false',
                'data' => [],
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
