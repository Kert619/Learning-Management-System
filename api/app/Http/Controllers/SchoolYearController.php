<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolYearController extends Controller
{
    use HttpResponse;

    protected static string $modelClass = SchoolYear::class;
    protected static array $indexColumns = ['id', 'school_year', 'status'];
    protected static array $orderBy = ['id' => 'desc'];

    protected function storeValidations(): array
    {
        return [
            'school_year' => ['required', 'string', 'max:255', Rule::unique('school_years', 'school_year')],
            'status' => ['nullable', 'string', Rule::in(['open', 'close'])]
        ];
    }

    protected function updateValidations(string $id): array
    {
        return [
            'school_year' => ['required', 'string', 'max:255', Rule::unique('school_years', 'school_year')->ignore($id)],
            'status' => ['required', 'string', Rule::in(['open', 'close'])]
        ];
    }

    protected function validationAttributes(): array
    {
        return [];
    }

    public function store(Request $request)
    {
        $response = parent::store($request);

        $data = json_decode($response->getContent(), true);

        $id = $data['data']['id'];
        $status = $data['data']['status'];

        if ($status === 'open') {
            SchoolYear::query()->where('id', '!=', $id)->update(['status' => 'close']);
        }

        return $response;
    }

    public function update(Request $request, $id)
    {
        $response = parent::update($request, $id);

        $data = json_decode($response->getContent(), true);

        $id = $data['data']['id'];
        $status = $data['data']['status'];

        if ($status === 'open') {
            SchoolYear::query()->where('id', '!=', $id)->update(['status' => 'close']);
        }

        return $response;
    }
}
