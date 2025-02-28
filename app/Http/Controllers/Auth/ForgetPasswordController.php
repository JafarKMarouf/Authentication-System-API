<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\ForgetPasswordService;
use Illuminate\Http\JsonResponse;
use Psr\SimpleCache\InvalidArgumentException;

class ForgetPasswordController extends Controller
{
    public function __construct(private readonly ForgetPasswordService $forgetPasswordService){}

    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        try {
            $request->validated();
            $this->forgetPasswordService->forgetPassword($request);
            return response()->json([
                'status' => true,
                'message' => 'sent code to your email for reset password'
            ], 200);
        } catch (CustomeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $e->getCustomCode());
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $request->validated();
            $this->forgetPasswordService->resetPassword($request);
            return response()->json([
                'status' => true,
                'message' => 'Your password has been reset'
            ], 200);
        } catch (CustomeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $e->getCustomCode());
        }
    }
}
