<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassDashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ViolationController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PublicLookupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — No Login Required
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicLookupController::class, 'index'])->name('home');
Route::get('/cek-siswa', [PublicLookupController::class, 'index'])->name('public.lookup');
Route::post('/cek-siswa', [PublicLookupController::class, 'search'])->name('public.lookup.search');

/*
|--------------------------------------------------------------------------
| Authenticated Routes — Guru BK Only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Class Dashboards
    Route::get('/kelas', [ClassDashboardController::class, 'index'])->name('classes.index');
    Route::get('/kelas/{class}', [ClassDashboardController::class, 'show'])->name('classes.show');

    // Students
    Route::get('/siswa/search', [StudentController::class, 'search'])->name('students.search');
    Route::resource('/siswa', StudentController::class)
        ->parameters(['siswa' => 'student'])
        ->names([
            'index'   => 'students.index',
            'create'  => 'students.create',
            'store'   => 'students.store',
            'show'    => 'students.show',
            'edit'    => 'students.edit',
            'update'  => 'students.update',
            'destroy' => 'students.destroy',
        ]);

    // Violations
    Route::resource('/pelanggaran', ViolationController::class)
        ->parameters(['pelanggaran' => 'violation'])
        ->except(['edit', 'update', 'destroy'])
        ->names([
            'index'  => 'violations.index',
            'create' => 'violations.create',
            'store'  => 'violations.store',
            'show'   => 'violations.show',
        ]);
    Route::post('/pelanggaran/{violation}/upload-scan', [ViolationController::class, 'uploadScan'])
        ->name('violations.upload-scan');

    // Export
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/pelanggaran/pdf',  [ExportController::class, 'violationsPdf'])->name('violations.pdf');
        Route::get('/pelanggaran/excel',[ExportController::class, 'violationsExcel'])->name('violations.excel');
        Route::get('/siswa/pdf',        [ExportController::class, 'studentsPdf'])->name('students.pdf');
        Route::get('/siswa/excel',      [ExportController::class, 'studentsExcel'])->name('students.excel');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
