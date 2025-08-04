<?php

use Illuminate\Support\Facades\Route;
use admin\courses\Controllers\CourseManagerController;

Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {  
    Route::resource('courses', CourseManagerController::class);
    Route::post('courses/updateStatus', [CourseManagerController::class, 'updateStatus'])->name('courses.updateStatus');
    Route::post('courses/updateHighlight', [CourseManagerController::class, 'updateHighlight'])->name('courses.updateHighlight');
});
