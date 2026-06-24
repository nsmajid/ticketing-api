<?php

namespace App\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $credentials): array
    {
        if (! Auth::attempt($credentials)) {

            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();



        $token = $user
            ->createToken('api-token')
            ->plainTextToken;

        return [
            'user' => $user->load('roles'),
            'permissions' => $user->getAllPermissions(),
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {

        $user->currentAccessToken()?->delete();
    }
}
