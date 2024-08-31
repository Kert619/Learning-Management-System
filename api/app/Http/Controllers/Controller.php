<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

abstract class Controller
{
    use HttpResponse;

    private Model $model;
    protected static string $modelClass;
    protected static array $indexColumns = [];
    protected static array $with = [];
    protected static array $orderBy = [];

    abstract protected function storeRequest(): FormRequest;
    abstract protected function updateRequest(): FormRequest;

    public function __construct()
    {
        $this->model = new (static::$modelClass);
    }

    private function filter(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            $query->where($key, 'LIKE', '%' . $value . '%');
        }
    }

    private function orderBy(Builder $query)
    {
        foreach (static::$orderBy as $key => $value) {
            $query->orderBy($key, $value);
        }
    }

    private function getIndexData(Request $request, Builder $query)
    {
        $data = [];

        $this->filter($query, $request->all());

        $this->orderBy($query);

        //check if pagination exists
        $perPage = $request->input('per_page');

        if ($perPage) {
            $data = $query->paginate($perPage);
        } else {
            $data = $query->get();
        }

        return $data;
    }

    public function index(Request $request)
    {
        $query = $this->model::query()->with(static::$with)->select(static::$indexColumns);

        $data = $this->getIndexData($request, $query);

        return $data;
    }

    public function store()
    {
        $validated = $this->storeRequest()->validated();
        $model = $this->model::create($validated);
        return $this->success($model, 'Created', 201);
    }

    public function show($id)
    {
        $model = $this->model::query()->with(static::$with)->where('id', $id)->select(static::$indexColumns)->firstOrFail();
        return $model;
    }

    public function update($id)
    {
        $model = $this->model::findOrFail($id);
        $validated = $this->updateRequest()->validated();
        $model->update($validated);
        return $this->success($model, 'Updated');
    }

    public function destroy($id)
    {
        $model = $this->model::findOrFail($id);
        $model->delete();
        return $this->success(null, 'Deleted', 204);
    }
}
