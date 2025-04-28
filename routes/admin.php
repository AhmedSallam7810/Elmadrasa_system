<?php

use App\Http\Controllers\Admin\AwardsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentsController;
use App\Http\Controllers\Admin\ClassesController;
use App\Http\Controllers\Admin\StudyYearController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ExamsController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\QuraanController;
use App\Http\Controllers\Admin\WerdsController;
use App\Http\Controllers\Admin\MuhafezController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BehaviorsController;
use App\Http\Controllers\Admin\ResearchsController;
use App\Http\Controllers\Admin\SummariesController;

// Admin authentication
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');

// Protected admin routes
Route::middleware(['auth:admin'])->group(function() {
    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/students', StudentsController::class)->names('admin.students');
    Route::resource('/classes', ClassesController::class)->names('admin.classes');

    // Awards management routes
    Route::resource('awards', AwardsController::class)->names('admin.awards');
    Route::post('awards/{award}/complete', [AwardsController::class, 'markAsCompleted'])->name('admin.awards.complete');

    // Additional routes for class-student relationship
    Route::get('/classes/{id}/enroll-students', [ClassesController::class, 'enrollStudents'])->name('admin.classes.enroll-students');
    Route::post('/classes/{id}/enroll-students', [ClassesController::class, 'storeEnrolledStudents'])->name('admin.classes.store-enrolled-students');
    Route::delete('/classes/{classId}/students/{studentId}', [ClassesController::class, 'removeStudent'])->name('admin.classes.remove-student');

    // Study Years Routes
    Route::resource('study-years', StudyYearController::class)->names('admin.study-years');

    // Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('admin.reports.generate');

    // Exam management routes
    Route::resource('exams', ExamsController::class)->names('admin.exams');

    // Attendance Routes
    Route::resource('attendances', AttendanceController::class)->names('admin.attendances');
    Route::get('attendances/bulk-create', [AttendanceController::class, 'bulkCreate'])->name('admin.attendance.bulk-create');

    // Summaries module routes
    Route::resource('summaries', SummariesController::class)->names('admin.summaries');

    Route::resource('quraans', QuraanController::class)->names('admin.quraans');

    Route::resource('muhafezs', MuhafezController::class)->names('admin.muhafezs');
    Route::get('/muhafezs/{id}/enroll-students', [MuhafezController::class, 'showEnrollStudents'])->name('admin.muhafezs.enroll-students');
    Route::post('/muhafezs/{id}/enroll-students', [MuhafezController::class, 'enrollStudents'])->name('admin.muhafezs.enroll-students.store');
    Route::delete('/muhafezs/{muhafezId}/students/{studentId}', [MuhafezController::class, 'removeStudent'])->name('admin.muhafezs.enroll-students.remove');

    // Research management routes
    Route::resource('researchs', ResearchsController::class)->names('admin.researchs');

    // Behavior management routes
    Route::resource('behaviors', BehaviorsController::class)->names('admin.behaviors');
    Route::resource('werds', WerdsController::class)->names('admin.werds');
});
