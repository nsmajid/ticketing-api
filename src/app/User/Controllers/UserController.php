<?php

namespace App\User\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\User\Requests\StoreUserRequest;
use App\User\Requests\UpdateUserRequest;
use App\User\Resources\UserResource;
use App\User\Services\UserService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Http\Request;


class UserController extends Controller implements HasMiddleware
{
    public function index()
    {
        return UserResource::collection(
            User::latest()->paginate()
        );
    }

    public function store(StoreUserRequest $request, UserService $service)
    {
        $user = $service->create(
            $request->validated()
        );

        return new UserResource(
            $user
        );
    }


    public function show(User $user)
    {
        return new UserResource(
            $user->load('roles')
        );
    }

    public function update(
        UpdateUserRequest $request,
        User $user,
        UserService $service
    ) {
        $user = $service->update(
            $user,
            $request->validated()
        );

        return new UserResource(
            $user
        );
    }

    public static function middleware(): array
    {
        return [

            new Middleware(
                'permission:user.view',
                only: ['index', 'show']
            ),

            new Middleware(
                'permission:user.create',
                only: ['store']
            ),

            new Middleware(
                'permission:user.update',
                only: ['update']
            ),

        ];
    }
}
