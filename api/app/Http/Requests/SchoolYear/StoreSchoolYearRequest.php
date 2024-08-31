<?php

namespace App\Http\Requests\SchoolYear;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSchoolYearRequest extends FormRequest
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
        return [
            'school_year' => ['required', 'string', 'max:255', Rule::unique('school_years', 'school_year')],
            'status' => ['nullable', 'string', Rule::in(['open', 'close'])]
        ];
    }
}
