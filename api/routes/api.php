<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('user', 'getUser');
        Route::get('instructors', 'getInstructors');
        Route::post('instructors', 'storeInstructor');
    });

    Route::apiResource('school-years', SchoolYearController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('programs', ProgramController::class);
});
