<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/**
 * Public routes
 */
Route::controller(RegisterUserController::class)->group(function () {
    Route::post('register-instructor', 'registerInstructor');
    Route::post('register-student', 'registerStudent');
});

/**
 * Protected routes
 */
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('user', 'user');
        Route::get('instructors', 'getInstructors');
        Route::get('students', 'getStudents');
    });

    Route::apiResource('school-years', SchoolYearController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('programs', ProgramController::class);
});
