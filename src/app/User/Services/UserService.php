<?php

namespace App\User\Services;

use App\Models\User;
use App\Shared\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{

    public function index(Request $request): LengthAwarePaginator
    {
        return User::query()

            ->with([
                'roles',
                'permissions',
            ])

            ->latest()

            ->paginate(
                $request->integer('per_page', 10)
            );
    }

    public function show(User $user): User
    {
        return User::query()

            ->with([
                'roles',
                'permissions',
            ])

            ->findOrFail($user->id);
    }

    public function create(array $data): User
    {
        return $this->transaction(function () use ($data) {

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

        return $this->transaction(function () use (
            $user,
            $data
        ) {

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
