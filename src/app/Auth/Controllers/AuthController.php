<?php

namespace App\Auth\Controllers;

use App\Auth\Requests\LoginRequest;
use App\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function login(
        LoginRequest $request
    ): JsonResponse {

        $result = $this->authService
            ->login($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => $result,
        ]);
    }

    public function logout(
        Request $request
    ): JsonResponse {

        $this->authService
            ->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    public function me(
        Request $request
    ): JsonResponse {

        return response()->json([
            'success' => true,
            'data' => $request->user()
                ->load('roles'),
        ]);
    }
}
