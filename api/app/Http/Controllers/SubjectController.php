<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;

class SubjectController extends Controller
{
    protected static string $modelClass = Subject::class;
    protected static array $indexColumns = ['id', 'subject_code', 'subject_title', 'created_at', 'updated_at'];
    protected static array $orderBy = ['id' => 'desc'];

    protected function storeRequest(): FormRequest
    {
        return app()->make(StoreSubjectRequest::class);
    }

    protected function updateRequest(): FormRequest
    {
        return app()->make(UpdateSubjectRequest::class);
    }
}
