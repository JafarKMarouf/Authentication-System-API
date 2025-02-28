<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerificationRequest;
use App\Services\EmailVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;

class EmailVerificationController extends Controller
{
    public function __construct(private readonly EmailVerificationService $verificationService){}

    /**
     * @throws InvalidArgumentException
     */
    public function verifyEmail(EmailVerificationRequest $request): JsonResponse
    {
        try {
            $request->validated();
            $this->verificationService->verify($request);
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

    /**
     * @throws InvalidArgumentException
     */
    public function resendCode(Request $request): JsonResponse
    {
        try {
            $this->verificationService->resend($request);
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

}
