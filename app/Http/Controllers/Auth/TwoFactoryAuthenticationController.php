<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\_2FARequest;
use App\Services\TwoFactorAuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;

class TwoFactoryAuthenticationController extends Controller
{
    public function __construct(private readonly TwoFactorAuthenticationService $twoFactorAuthenticationService){}

    /**
     * @throws InvalidArgumentException
     */
    public function verify2FAOtp(_2FARequest $request): JsonResponse
    {
        try {
            $request->validated();
            $data = $this->twoFactorAuthenticationService->verify($request);
            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => 'OTP verified Successfully and 2FA is enabled'
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
    public function resend2FAOtp(Request $request): JsonResponse
    {
        try {
            $this->twoFactorAuthenticationService->resendCode($request);
            return response()->json([
                'status' => true,
                'message' => 'Resent Code OTP Successfully for enable 2FA'
            ], 200);
        } catch (CustomeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCustomCode());
        }
    }
}
