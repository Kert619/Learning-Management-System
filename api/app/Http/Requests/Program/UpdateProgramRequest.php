<?php

namespace App\Http\Requests\Program;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgramRequest extends FormRequest
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
        $id = $this->route('program');
        return [
            'program_code' => ['required', 'string', 'max:255', Rule::unique('programs', 'program_code')->ignore($id)],
            'program_title' => ['required', 'string', 'max:255', Rule::unique('programs', 'program_title')->ignore($id)]
        ];
    }
}
