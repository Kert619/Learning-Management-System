<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_instructor()
    {
        //load data;
        $instructor = ['email' => 'kert@car.info', 'password' => 'password'];

        //call endpoint
        $response = $this->post('/api/register-instructor', $instructor);

        //assert status
        $response->assertOk();

        //verify records
        $this->assertDatabaseHas('users', [
            'email' => 'kert@car.info',
        ]);

        $user = User::query()->where('email', 'kert@car.info')->first();
        $this->assertNotNull($user->email_verified_at);
    }
}
