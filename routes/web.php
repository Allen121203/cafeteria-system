<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Index → login page (with role redirect if already logged in)
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');

Route::get('/home', CustomerHomeController::class)->name('customer.home');

// Customer routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/home', CustomerHomeController::class)->name('customer.home');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/reservations/approve', [AdminDashboardController::class, 'approve'])->name('reservations.approve');
});

// Superadmin routes
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/users', [SuperAdminController::class, 'index'])->name('superadmin.users');
    Route::post('/superadmin/users/update', [SuperAdminController::class, 'update'])->name('superadmin.users.update');
});

// Reservation routes (for logged-in users)
Route::middleware(['auth'])->group(function () {
    Route::post('/reservations', [CustomerHomeController::class, 'store'])->name('reservations.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include Breeze authentication routes (register, login, logout, password reset)
require __DIR__.'/auth.php';
