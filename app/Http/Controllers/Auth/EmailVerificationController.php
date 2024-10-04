<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Services\SendOtpAction;
use App\Services\VerifyEmailAction;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
    public function sendOtp(SendOtpRequest $request, SendOtpAction $sendOtpAction): JsonResponse
    {
        try {
            $request->validated();
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
    public function verifyEmail(VerifyEmailRequest $request, VerifyEmailAction $verifyEmailAction)
    {
        try {
            $request->validated();
            $data =  $verifyEmailAction->execute($request);
            return response()->json([
                'status' => true,
                'data' => $data,
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
