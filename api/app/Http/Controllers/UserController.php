<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function user(Request $request)
    {
        return $request->user();
    }
    public function getInstructors()
    {
        return User::query()->whereRole(Role::INSTRUCTOR)->get();
    }

    public function getStudents()
    {
        return User::query()->whereRole(Role::STUDENT)->get();
    }
}
