<?php

namespace Tests\Feature\Controllers;

use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolYearControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->createOneQ();
        $this->actingAs($user);
    }

    public function test_index(): void
    {
        //load data
        $schoolYears =  SchoolYear::factory()->count(5)->close()->create();

        //call endpoint
        $response = $this->getJson('/api/school-years');

        //assert status
        $response->assertOk()->assertJsonCount(5);

        //verify records
        $schoolYears->each(fn($schoolYear) => $response->assertJsonFragment($schoolYear->toArray()));
    }

    public function test_show()
    {
        //load data
        $schoolYear =  SchoolYear::factory()->close()->create();

        //call endpoint
        $response = $this->getJson('/api/school-years/' . $schoolYear->id);

        //assert status
        $response->assertOk();

        //verify records
        $response->assertJson($schoolYear->toArray());
    }

    public function test_store()
    {
        //load data
        $schoolYear = SchoolYear::factory()->close()->make();

        //call endpoint
        $response = $this->postJson('/api/school-years', $schoolYear->toArray());

        //assert status
        $response->assertCreated();

        //verify records
        $response->assertJson([
            'data' => $schoolYear->toArray()
        ]);
    }

    public function test_update()
    {
        //load data
        $schoolYear = SchoolYear::factory()->create();

        $schoolYearUpdated = SchoolYear::factory()->close()->make();

        //call endpoint
        $response = $this->putJson('/api/school-years/' . $schoolYear->id, $schoolYearUpdated->toArray());

        //assert status
        $response->assertOk();

        //verify records
        $response->assertJson([
            'data' => $schoolYearUpdated->toArray()
        ]);
    }

    public function test_delete()
    {
        //load data
        $schoolYear = SchoolYear::factory()->create();

        //call endpoint
        $response = $this->deleteJson('/api/school-years/' . $schoolYear->id);

        //assert status
        $response->assertNoContent();

        //verify records
        $this->expectException(ModelNotFoundException::class);
        SchoolYear::query()->findOrFail($schoolYear->id);
    }
}
