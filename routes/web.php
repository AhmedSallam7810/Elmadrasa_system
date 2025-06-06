<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MuhafezController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Muhafez enrollment routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
});

// Include admin routes
require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
