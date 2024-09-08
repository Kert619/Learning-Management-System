<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;

class RegisterUserController extends Controller
{
    use HttpResponse;

    public function registerInstructor(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user =  User::create(array_merge($validated, [
            'email_verified_at' => now(),
            'role' => Role::INSTRUCTOR
        ]));

        return $this->success($user, 'Created');
    }

    public function registerStudent(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user =  User::create(array_merge($validated, [
            'role' => Role::STUDENT
        ]));

        return $this->success($user, 'Created');
    }
}
