<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        return $request->user();
    }

    public function getInstructors()
    {
        return User::query()->whereRole(Role::INSTRUCTOR)->get();
    }

    public function storeInstructor(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user =  User::create([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'email_verified_at' => now(),
            'role' => Role::INSTRUCTOR
        ]);

        return $user;
    }
}
