<?php

use Illuminate\Support\Facades\Route;
use admin\courses\Controllers\CourseManagerController;
use admin\courses\Controllers\LectureManagerController;

Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {  
    Route::resource('courses', CourseManagerController::class);
    Route::post('courses/updateStatus', [CourseManagerController::class, 'updateStatus'])->name('courses.updateStatus');
    Route::post('courses/updateHighlight', [CourseManagerController::class, 'updateHighlight'])->name('courses.updateHighlight');
    
    // Specific lecture routes must come before resource routes
    Route::post('lectures/updateStatus', [LectureManagerController::class, 'updateStatus'])->name('lectures.updateStatus');
    Route::post('lectures/updateHighlight', [LectureManagerController::class, 'updateHighlight'])->name('lectures.updateHighlight');
    
    // Resource route for lectures (this handles index, create, store, show, edit, update, destroy)
    Route::resource('lectures', LectureManagerController::class);
});
