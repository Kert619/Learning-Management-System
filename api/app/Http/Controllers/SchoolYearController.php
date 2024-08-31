<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolYear\StoreSchoolYearRequest;
use App\Http\Requests\SchoolYear\UpdateSchoolYearRequest;
use App\Models\SchoolYear;
use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;

class SchoolYearController extends Controller
{
    use HttpResponse;

    protected static string $modelClass = SchoolYear::class;
    protected static array $indexColumns = ['id', 'school_year', 'status'];
    protected static array $orderBy = ['id' => 'desc'];

    protected function storeRequest(): FormRequest
    {
        return app()->make(StoreSchoolYearRequest::class);
    }

    protected function updateRequest(): FormRequest
    {
        return app()->make(UpdateSchoolYearRequest::class);
    }

    public function store()
    {
        $response = parent::store();

        $data = json_decode($response->getContent(), true);

        $id = $data['data']['id'];
        $status = $data['data']['status'];

        if ($status === 'open') {
            SchoolYear::query()->where('id', '!=', $id)->update(['status' => 'close']);
        }

        return $response;
    }

    public function update($id)
    {
        $response = parent::update($id);

        $data = json_decode($response->getContent(), true);

        $id = $data['data']['id'];
        $status = $data['data']['status'];

        if ($status === 'open') {
            SchoolYear::query()->where('id', '!=', $id)->update(['status' => 'close']);
        }

        return $response;
    }
}
