<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolYearController extends Controller
{
    use HttpResponse;

    protected static string $modelName = SchoolYear::class;
    protected static array $indexColumns = ['school_year', 'status'];
    protected static array $orderBy = ['school_year'];

    protected function storeValidations(): array
    {
        return [
            'school_year' => ['required', 'string', 'max:255', Rule::unique('school_years', 'school_year')]
        ];
    }

    protected function updateValidations(string $id): array
    {
        return [
            'school_year' => ['required', 'string', 'max:255', Rule::unique('school_years', 'school_year')->ignore($id)],
            'status' => ['required', 'string', Rule::in(['open', 'close'])]
        ];
    }

    protected function schoolYearStatusValidation(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(['open', 'close'])]
        ];
    }

    public function setSchoolYearStatus(Request $request, SchoolYear $schoolYear)
    {
        $validatedData = $request->validate($this->schoolYearStatusValidation());

        SchoolYear::query()->update(['status' => 'close']);

        $schoolYear->update($validatedData);

        return $this->success($schoolYear, 'School year status updated');
    }
}
