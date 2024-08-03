<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\SchoolYearController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::put('school-years/set-school-year-status/{school_year}', [SchoolYearController::class, 'setSchoolYearStatus']);
Route::apiResource('school-years', SchoolYearController::class);
Route::apiResource('courses', CourseController::class);
