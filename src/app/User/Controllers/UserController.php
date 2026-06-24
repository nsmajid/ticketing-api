<?php

namespace App\User\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\User\Requests\StoreUserRequest;
use App\User\Requests\UpdateUserRequest;
use App\User\Resources\UserResource;
use App\User\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
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
}
