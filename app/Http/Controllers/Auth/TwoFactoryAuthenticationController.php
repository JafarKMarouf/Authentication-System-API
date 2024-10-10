<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\_2FARequest;
use App\Services\ResendCodeTwoFactoryAuthAction;
use App\Services\VerifyTwoFactoryAuthAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TwoFactoryAuthenticationController extends Controller
{
    public function verify2FAOTP(_2FARequest $request, VerifyTwoFactoryAuthAction $action): JsonResponse
    {
        try {
            $request->validated();
            $data = $action->execute($request);
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

    public function resend2FAOTP(Request $request, ResendCodeTwoFactoryAuthAction $action): JsonResponse
    {
        try {
            $action->execute($request);
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
