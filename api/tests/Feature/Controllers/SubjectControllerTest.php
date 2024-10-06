<?php

namespace Tests\Feature\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->verified()->admin()->create();
        $this->actingAs($user);
    }

    public function test_index()
    {
        //load data
        $subjects = Subject::factory()->count(5)->create();

        //call endpoint
        $response = $this->getJson('/api/subjects');

        //assert status
        $response->assertOk()->assertJsonCount(5);

        //verify records
        $subjects->each(fn($subject) => $response->assertJsonFragment($subject->toArray()));
    }

    public function test_show()
    {
        //load data
        $subject = Subject::factory()->create();

        //call endpoint
        $response = $this->getJson('/api/subjects/' . $subject->id);

        //assert status
        $response->assertOk();

        //verify records
        $response->assertJson($subject->toArray());
    }

    public function test_store()
    {
        //load data
        $subject = Subject::factory()->make();

        //call endpoint
        $response = $this->postJson('/api/subjects', $subject->toArray());

        //assert status
        $response->assertCreated();

        //verify records
        $response->assertJson([
            'data' => $subject->toArray()
        ]);
    }

    public function test_update()
    {
        //load data
        $subject = Subject::factory()->create();

        $programUpdated = Subject::factory()->make();

        //call endpoint
        $response = $this->putJson('/api/subjects/' . $subject->id, $programUpdated->toArray());

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
        $subject = Subject::factory()->create();

        //call endpoint
        $response = $this->deleteJson('/api/subjects/' . $subject->id);

        //assert status
        $response->assertNoContent();

        //verify records
        $this->expectException(ModelNotFoundException::class);
        Subject::query()->findOrFail($subject->id);
    }
}
