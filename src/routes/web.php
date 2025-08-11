<?php

use Illuminate\Support\Facades\Route;
use admin\courses\Controllers\CourseManagerController;
use admin\courses\Controllers\CoursePurchaseManagerController;
use admin\courses\Controllers\LectureManagerController;
use admin\courses\Controllers\TransactionManagerController;

Route::name('admin.')->middleware(['web', 'admin.auth'])->group(function () {

    // Courses
    Route::resource('courses', CourseManagerController::class);
    Route::post('courses/updateStatus', [CourseManagerController::class, 'updateStatus'])->name('courses.updateStatus');
    Route::post('courses/updateHighlight', [CourseManagerController::class, 'updateHighlight'])->name('courses.updateHighlight');

    // Lectures - custom before resource
    Route::post('lectures/updateStatus', [LectureManagerController::class, 'updateStatus'])->name('lectures.updateStatus');
    Route::post('lectures/updateHighlight', [LectureManagerController::class, 'updateHighlight'])->name('lectures.updateHighlight');
    Route::get('fetch/course/section/{course}', [LectureManagerController::class, 'fetchCourseSections'])->name('courses.sections');
    Route::resource('lectures', LectureManagerController::class);

    // Transactions
    Route::resource('transactions', TransactionManagerController::class);

    // Course Purchases
    Route::resource('course-purchases', CoursePurchaseManagerController::class);
});
