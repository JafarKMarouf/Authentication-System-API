<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomeException;
use App\Http\Controllers\Controller;
use App\Services\RefreshTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RefreshTokenController extends Controller
{
    public function __construct(private readonly RefreshTokenService $service){}

    public function refreshToken(): JsonResponse
    {
        try {
            $data =  $this->service->createRefreshToken();
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
