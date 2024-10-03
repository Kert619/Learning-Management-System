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
Route::post('register-student', [RegisterUserController::class, 'registerStudent']);

/**
 * Protected routes
 */
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('user', 'user');
        Route::get('instructors', 'getInstructors');
        Route::get('students', 'getStudents');
    });

    Route::post('register-instructor', [RegisterUserController::class, 'registerInstructor']);

    Route::apiResource('school-years', SchoolYearController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('programs', ProgramController::class);
});
