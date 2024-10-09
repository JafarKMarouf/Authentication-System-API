<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerificationRequest;
use App\Services\EmailVerificationAction;
use App\Services\ResendCodeAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function resendCode(Request $request, ResendCodeAction $action): JsonResponse
    {
        try {
            $action->execute($request);
            return response()->json([
                'status' => true,
                'message' => 'Resent Code OTP Successfully'
            ], 200);
        } catch (CustomeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCustomCode());
        }
    }
    public function verifyEmail(EmailVerificationRequest $request, EmailVerificationAction $action): JsonResponse
    {
        try {
            $request->validated();
            $action->execute($request);
            return response()->json([
                'status' => true,
                'message' => 'Email Verified Successfully'
            ], 200);
        } catch (CustomeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCustomCode());
        }
    }
}
