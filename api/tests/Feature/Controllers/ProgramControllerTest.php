<?php

namespace Tests\Feature\Controllers;

use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_index()
    {
        //load data
        $programs = Program::factory()->count(5)->create();

        //call endpoint
        $response = $this->getJson('/api/programs');

        //assert status
        $response->assertOk()->assertJsonCount(5);

        //verify records
        $programs->each(fn($program) => $response->assertJsonFragment($program->toArray()));
    }

    public function test_show()
    {
        //load data
        $program = Program::factory()->create();

        //call endpoint
        $response = $this->getJson('/api/programs/' . $program->id);

        //assert status
        $response->assertOk();

        //verify records
        $response->assertJson($program->toArray());
    }

    public function test_store()
    {
        //load data
        $program = Program::factory()->make();

        //call endpoint
        $response = $this->postJson('/api/programs', $program->toArray());

        //assert status
        $response->assertCreated();

        //verify records
        $response->assertJson([
            'data' => $program->toArray()
        ]);
    }

    public function test_update()
    {
        //load data
        $program = Program::factory()->create();

        $programUpdated = Program::factory()->make();

        //call endpoint
        $response = $this->putJson('/api/programs/' . $program->id, $programUpdated->toArray());

        //assert status
        $response->assertOk();

        //verify records
        $response->assertJson([
            'data' => $programUpdated->toArray()
        ]);
    }

    public function test_delete()
    {
        //load data
        $program = Program::factory()->create();

        //call endpoint
        $response = $this->deleteJson('/api/programs/' . $program->id);

        //assert status
        $response->assertNoContent();

        //verify records
        $this->expectException(ModelNotFoundException::class);
        Program::query()->findOrFail($program->id);
    }
}
