<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Services\EmailVerificationAction;
use App\Services\SendOtpAction;
use App\Services\VerifyEmailAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function resendCode(Request $request, SendOtpAction $sendOtpAction): JsonResponse
    {
        try {
            $sendOtpAction->execute($request);
            return response()->json([
                'status' => true,
                'message' => 'Sent Code OTP Successfully'
            ], 200);
        } catch (CustomeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCustomCode());
        }
    }
    public function verifyEmail(EmailVerificationRequest $request, EmailVerificationAction $emailVerificationAction): JsonResponse
    {
        try {
            $request->validated();
            $emailVerificationAction->execute($request);
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
