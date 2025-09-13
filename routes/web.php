<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordController;

// Index → show login (if already logged in, controller will role-redirect)
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');

// Fake "dashboard" that forwards to the proper one (avoids Route [dashboard] not defined)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'superadmin') return redirect()->route('superadmin.users');
    if ($user->role === 'admin')      return redirect()->route('admin.dashboard');
    return redirect()->route('customer.home');
})->middleware(['auth'])->name('dashboard');

// Profile (Breeze profile pages)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/superadmin/users/{user}/audit', [SuperAdminController::class, 'audit'])->name('superadmin.users.audit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});

// Superadmin
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/users', [SuperAdminController::class, 'index'])->name('users');

    // Create admin
    Route::post('/users', [SuperAdminController::class, 'store'])->name('users.store');

    // Update admin
    Route::put('/users/{user}', [SuperAdminController::class, 'update'])->name('users.update');

    // Delete user ✅ This fixes your error
    Route::delete('/users/{user}', [SuperAdminController::class, 'destroy'])->name('users.destroy');

    // Audit trail
    Route::get('/users/{user}/audit', [SuperAdminController::class, 'audit'])->name('users.audit');
});

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Customer
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/home', [CustomerHomeController::class, 'index'])->name('customer.home');
});

Route::post('/superadmin/users/store', [SuperAdminController::class, 'store'])->name('superadmin.users.store');

// Breeze auth routes (login, register, password reset, logout)
require __DIR__.'/auth.php';
