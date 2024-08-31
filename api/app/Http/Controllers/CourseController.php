<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    protected static string $modelClass = Course::class;
    protected static array $indexColumns = ['course_title', 'course_code'];
    protected static array $orderBy = ['course_title'];

    protected function storeValidations(): array
    {
        return [
            'course_title' => ['required', 'string', 'max:255', Rule::unique('courses', 'course_title')],
            'course_code' => ['required', 'string', 'max:255', Rule::unique('courses', 'course_code')]
        ];
    }

    protected function updateValidations(string $id): array
    {
        return [
            'course_title' => ['required', 'string', 'max:255', Rule::unique('courses', 'course_title')->ignore($id)],
            'course_code' => ['required', 'string', 'max:255', Rule::unique('courses', 'course_code')->ignore($id)]
        ];
    }

    protected function validationAttributes(): array
    {
        return [];
    }
}
