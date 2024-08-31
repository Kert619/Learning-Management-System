<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class CourseController extends Controller
{
    protected static string $modelClass = Course::class;
    protected static array $indexColumns = ['id', 'course_code', 'course_title', 'created_at', 'updated_at'];
    protected static array $orderBy = ['id' => 'asc'];
    protected function storeRequest(): FormRequest
    {
        return app()->make(StoreCourseRequest::class);
    }

    protected function updateRequest(): FormRequest
    {
        return app()->make(UpdateCourseRequest::class);
    }
}
