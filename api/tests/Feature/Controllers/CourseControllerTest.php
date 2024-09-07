<?php

namespace Tests\Feature\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->createOne();
        $this->actingAs($user);
    }

    public function test_index()
    {
        //load data
        $courses = Course::factory()->count(5)->create();

        //call endpoint
        $response = $this->getJson('/api/courses');

        //assert status
        $response->assertOk()->assertJsonCount(5);

        //verify records
        $courses->each(fn($course) => $response->assertJsonFragment($course->toArray()));
    }

    public function test_show()
    {
        //load data
        $course = Course::factory()->create();

        //call endpoint
        $response = $this->getJson('/api/courses/' . $course->id);

        //assert status
        $response->assertOk();

        //verify records
        $response->assertJson($course->toArray());
    }

    public function test_store()
    {
        //load data
        $course = Course::factory()->make();

        //call endpoint
        $response = $this->postJson('/api/courses', $course->toArray());

        //assert status
        $response->assertCreated();

        //verify records
        $response->assertJson([
            'data' => $course->toArray()
        ]);
    }

    public function test_update()
    {
        //load data
        $course = Course::factory()->create();

        $courseUpdated = Course::factory()->make();

        //call endpoint
        $response = $this->putJson('/api/courses/' . $course->id, $courseUpdated->toArray());

        //assert status
        $response->assertOk();

        //verify records
        $response->assertJson([
            'data' => $courseUpdated->toArray()
        ]);
    }

    public function test_delete()
    {
        //load data
        $course = Course::factory()->create();

        //call endpoint
        $response = $this->deleteJson('/api/courses/' . $course->id);

        //assert status
        $response->assertNoContent();

        //verify records
        $this->expectException(ModelNotFoundException::class);
        Course::query()->findOrFail($course->id);
    }
}
