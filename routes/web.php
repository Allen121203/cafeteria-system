<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ProfileController;

// Index → show login (if already logged in, controller will role-redirect)
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');

// Fake "dashboard" that forwards to the proper one (avoids Route [dashboard] not defined)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('superadmin')) return redirect()->route('superadmin.users');
    if ($user->hasRole('admin'))      return redirect()->route('admin.dashboard');
    return redirect()->route('customer.home');
})->middleware(['auth'])->name('dashboard');

// Profile (Breeze profile pages)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Customer
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/home', CustomerHomeController::class)->name('customer.home'); // invokable controller
    Route::post('/reservations', [CustomerHomeController::class, 'store'])->name('reservations.store');
});

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/reservations/approve', [AdminDashboardController::class, 'approve'])->name('reservations.approve');
});

// Superadmin
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/users', [SuperAdminController::class, 'index'])->name('superadmin.users');
    Route::post('/superadmin/users/store', [SuperAdminController::class, 'store'])->name('superadmin.users.store');
    Route::get('/superadmin/users/{user}/edit', [SuperAdminController::class, 'edit'])->name('superadmin.users.edit');
    Route::put('/superadmin/users/{user}', [SuperAdminController::class, 'update'])->name('superadmin.users.update');
    Route::delete('/superadmin/users/{user}', [SuperAdminController::class, 'destroy'])->name('superadmin.users.destroy');
    Route::get('/superadmin/users/{user}/audit', [SuperAdminController::class, 'audit'])->name('superadmin.users.audit');
});

Route::post('/superadmin/users/store', [SuperAdminController::class, 'store'])->name('superadmin.users.store');

// Breeze auth routes (login, register, password reset, logout)
require __DIR__.'/auth.php';
