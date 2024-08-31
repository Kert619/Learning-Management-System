<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('course');
        return [
            'course_code' => ['required', 'string', 'max:255', Rule::unique('courses', 'course_code')->ignore($id)],
            'course_title' => ['required', 'string', 'max:255', Rule::unique('courses', 'course_title')->ignore($id)]
        ];
    }
}
