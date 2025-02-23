<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Services\RefreshTokenAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RefreshTokenController extends Controller
{
    public function refreshToken(Request $request, RefreshTokenAction $refreshTokenAction): JsonResponse
    {
        try {
            $data =  $refreshTokenAction->execute($request);
            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => 'Tokens created Successfully'
            ], 200);
        } catch (CustomeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCustomCode());
        }
    }
}
