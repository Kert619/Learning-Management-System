<?php

namespace Tests\Feature\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->verified()->admin()->create();
        $this->actingAs($user);
    }

    public function test_user()
    {
        //load data
        $user =  User::factory()->verified()->admin()->create();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->actingAs($user);

        //call endpoint 
        $response = $this->getJson('/api/user');

        //assert status
        $response->assertOk();
    }

    public function test_get_instructors()
    {
        //load data
        User::factory()->verified()->instructor()->count(5)->create();

        //call endpoint
        $response = $this->getJson('/api/instructors');

        //assert status
        $response->assertOk()->assertJsonCount(5);

        //verify records
        $instructors = collect($response->json());
        $instructors->each(fn($instructor) => $this->assertEquals(Role::INSTRUCTOR->value, $instructor['role']));
        $instructors->each(fn($instructor) => $this->assertNotNull($instructor['email_verified_at']));
    }
}
