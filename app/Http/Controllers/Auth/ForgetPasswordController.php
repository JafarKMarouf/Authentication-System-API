<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\ForgetPasswordAction;
use App\Services\ResetPasswordAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $request, ForgetPasswordAction $action): JsonResponse
    {
        try {
            $request->validated();
            $action->execute($request);
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
    public function resetPassword(ResetPasswordRequest $request, ResetPasswordAction $action): JsonResponse
    {
        try {
            $request->validated();
            $action->execute($request);
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
