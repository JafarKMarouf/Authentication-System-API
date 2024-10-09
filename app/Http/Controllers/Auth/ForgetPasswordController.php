<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Services\ForgetPasswordAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function __invoke(ForgetPasswordRequest $request, ForgetPasswordAction $action): JsonResponse
    {
        try {
            $request->validated();
            $action->excetue($request);
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
}
