<?php

namespace App\Http\Controllers;

use App\Http\Requests\Program\StoreProgramRequest;
use App\Http\Requests\Program\UpdateProgramRequest;
use App\Models\Program;
use Illuminate\Foundation\Http\FormRequest;

class ProgramController extends Controller
{
    protected static string $modelClass = Program::class;
    protected static array $indexColumns = ['id', 'program_code', 'program_title', 'created_at', 'updated_at'];
    protected static array $orderBy = ['id' => 'desc'];

    protected function storeRequest(): FormRequest
    {
        return app()->make(StoreProgramRequest::class);
    }

    protected function updateRequest(): FormRequest
    {
        return app()->make(UpdateProgramRequest::class);
    }
}
