<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
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
        $id = $this->route('subject');
        return [
            'subject_code' => ['required', 'string', 'max:255', Rule::unique('subjects', 'subject_code')->ignore($id)],
            'subject_title' => ['required', 'string', 'max:255', Rule::unique('subjects', 'subject_title')->ignore($id)]
        ];
    }
}
