<?php

namespace Tests\Feature\Controllers;

use App\Models\SchoolYear;
use App\Models\User;
use Database\Factories\SchoolYearFactory;
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
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_index(): void
    {
        //load data
        $schoolYears =  SchoolYear::factory()->count(5)->close()->create()->map(fn($schoolYear) => $schoolYear->only(['id', 'school_year', 'status']));

        //call endpoint
        $response = $this->getJson('/api/school-years');

        //assert status
        $response->assertOk()->assertJsonCount(5);

        //verify records
        $schoolYears->each(fn($schoolYear) => $response->assertJsonFragment($schoolYear));
    }

    public function test_show()
    {
        //load data
        $schoolYear =  SchoolYear::factory()->close()->create()->only(['id', 'school_year', 'status']);

        //call endpoint
        $response = $this->getJson('/api/school-years/' . $schoolYear['id']);

        //assert status
        $response->assertOk();

        //verify records
        $response->assertJsonFragment($schoolYear);
    }

    public function test_store()
    {
        //load data
        $schoolYear = SchoolYear::factory()->close()->make()->toArray();

        //call endpoint
        $response = $this->postJson('/api/school-years', $schoolYear);

        //assert status
        $response->assertCreated();

        //verify records
        $response->assertJsonFragment($schoolYear);
    }

    public function test_update()
    {
        //load data
        $schoolYear = SchoolYear::factory()->create();

        $schoolYearUpdated = SchoolYear::factory()->close()->make()->toArray();

        //call endpoint
        $response = $this->putJson('/api/school-years/' . $schoolYear->id, $schoolYearUpdated);

        //assert status
        $response->assertOk();

        //verify records
        $response->assertJsonFragment($schoolYearUpdated);
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

    public function test_store_has_invalid_data()
    {
        //load data
        $data = [
            'school_year' => '',
            'status' => ''
        ];

        //call endpoint
        $response = $this->postJson('/api/school-years', $data);

        //assert status
        $response->assertUnprocessable();
    }

    public function test_update_has_invalid_data()
    {
        //load data
        $schoolYear = SchoolYear::factory()->create();

        $data = [
            'school_year' => '',
            'status' => ''
        ];

        //call endpoint
        $response = $this->putJson('/api/school-years/' . $schoolYear->id, $data);

        //assert status
        $response->assertUnprocessable();
    }
}
