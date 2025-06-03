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
use App\Http\Controllers\Admin\QuraansController;
use App\Http\Controllers\Admin\WerdsController;
use App\Http\Controllers\Admin\MuhafezController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BehaviorsController;
use App\Http\Controllers\Admin\QuraanController;
use App\Http\Controllers\Admin\ResearchsController;
use App\Http\Controllers\Admin\SummariesController;

// Admin authentication
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');

// Protected admin routes
Route::middleware(['auth:admin'])->group(function () {
    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::get('students/download-template', [StudentsController::class, 'downloadTemplate'])->name('admin.students.download-template');
    Route::post('students/import', [StudentsController::class, 'import'])->name('admin.students.import');
    Route::resource('/students', StudentsController::class)->names('admin.students');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
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
    Route::get('attendances/excel-upload', [AttendanceController::class, 'excelUpload'])->name('admin.attendances.excel-upload');
    Route::get('attendances/bulk-create', [AttendanceController::class, 'bulkCreate'])->name('admin.attendance.bulk-create');
    Route::post('attendances/import', [AttendanceController::class, 'import'])->name('admin.attendances.import');
    Route::get('attendances/download-template', [AttendanceController::class, 'downloadTemplate'])->name('admin.attendances.download-template');
    Route::get('attendances/get-students-without-attendance', [AttendanceController::class, 'getStudentsWithoutAttendance'])->name('admin.attendances.get-students-without-attendance');
    Route::resource('attendances', AttendanceController::class)->names('admin.attendances');

    // Summaries module routes
    Route::post('summaries/import', [SummariesController::class, 'import'])->name('admin.summaries.import');
    Route::get('summaries/download-template', [SummariesController::class, 'downloadTemplate'])->name('admin.summaries.download-template');
    Route::resource('summaries', SummariesController::class)->names('admin.summaries');

    Route::resource('muhafezs', MuhafezController::class)->names('admin.muhafezs');
    Route::get('/muhafezs/{id}/enroll-students', [MuhafezController::class, 'showEnrollStudents'])->name('admin.muhafezs.enroll-students');
    Route::post('/muhafezs/{id}/enroll-students', [MuhafezController::class, 'enrollStudents'])->name('admin.muhafezs.enroll-students.store');
    Route::delete('/muhafezs/{muhafezId}/students/{studentId}', [MuhafezController::class, 'removeStudent'])->name('admin.muhafezs.enroll-students.remove');

    // Research management routes
    Route::post('researchs/import', [ResearchsController::class, 'import'])->name('admin.researchs.import');
    Route::get('researchs/download-template', [ResearchsController::class, 'downloadTemplate'])->name('admin.researchs.download-template');
    Route::resource('researchs', ResearchsController::class)->names('admin.researchs');

    // Behavior management routes
    Route::post('behaviors/import', [BehaviorsController::class, 'import'])->name('admin.behaviors.import');
    Route::get('behaviors/download-template', [BehaviorsController::class, 'downloadTemplate'])->name('admin.behaviors.download-template');
    Route::get('behaviors/get-students-without-behavior', [BehaviorsController::class, 'getStudentsWithoutBehavior'])->name('admin.behaviors.get-students-without-behavior');
    Route::resource('behaviors', BehaviorsController::class)->names('admin.behaviors');
    Route::get('werds/download-template', [WerdsController::class, 'downloadTemplate'])->name('admin.werds.download-template');
    Route::post('werds/import', [WerdsController::class, 'import'])->name('admin.werds.import');
    Route::resource('werds', WerdsController::class)->names('admin.werds');

    Route::post('quraans/import', [QuraanController::class, 'import'])->name('admin.quraans.import');
    Route::get('quraans/download-template', [QuraanController::class, 'downloadTemplate'])->name('admin.quraans.download-template');
    Route::resource('quraans', QuraanController::class)->names('admin.quraans');
});
