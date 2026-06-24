<?php

namespace App\User\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make(
                    $data['password']
                ),
                'phone' => $data['phone'] ?? null,
                'company_name' => $data['company_name'] ?? null,
                'position' => $data['position'] ?? null,
            ]);

            $user->assignRole(
                $data['role']
            );

            return $user;
        });
    }

    public function update(User $user, array $data): User
    {

        return DB::transaction(function () use ($user, $data) {

            $user->update([
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'phone' => $data['phone'] ?? null,
                'company_name' => $data['company_name'] ?? null,
                'position' => $data['position'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (!empty($data['password'])) {

                $user->update([
                    'password' => Hash::make(
                        $data['password']
                    )
                ]);
            }

            $user->syncRoles([
                $data['role']
            ]);

            return $user->fresh()
                ->load('roles');
        });
    }
}
