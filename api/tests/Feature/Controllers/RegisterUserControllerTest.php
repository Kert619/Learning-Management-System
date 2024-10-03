<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->verified()->admin()->create();
        $this->actingAs($user);
    }

    public function test_register_instructor()
    {
        //load data;
        $instructor = User::factory()->verified()->instructor()->make()->makeVisible('password');

        //call endpoint
        $response = $this->postJson('/api/register-instructor', $instructor->toArray());
        //assert status
        $response->assertOk();

        //verify records
        $this->assertDatabaseHas('users', [
            'email' => $instructor->email,
        ]);

        $user = User::query()->where('email', $instructor->email)->first();
        $this->assertNotNull($user->email_verified_at);
    }
}
