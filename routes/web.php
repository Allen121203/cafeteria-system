<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ProfileController;

// ---------- Index -> Login ----------
Route::get('/', fn () => redirect()->route('login'))->name('home');

// ---------- Breeze auth routes (login, register, logout, password, etc.) ----------
require __DIR__ . '/auth.php';

// ---------- Dashboard redirect helper ----------
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) return redirect()->route('login');

    return match ($user->role) {
        'superadmin' => redirect()->route('superadmin.users'),
        'admin'      => redirect()->route('admin.dashboard'),
        default      => redirect()->route('customer.home'),
    };
})->middleware(['auth'])->name('dashboard');

// ---------- Profile (Account Settings) ----------
Route::middleware(['auth'])->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---------- Superadmin ----------
Route::middleware(['auth', 'role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get   ('/users',            [SuperAdminController::class, 'index'])->name('users');
        Route::post  ('/users',            [SuperAdminController::class, 'store'])->name('users.store');
        Route::put   ('/users/{user}',     [SuperAdminController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',     [SuperAdminController::class, 'destroy'])->name('users.destroy');
        Route::get   ('/users/{user}/audit',[SuperAdminController::class, 'audit'])->name('users.audit');
    });

// ---------- Admin ----------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/reservations', [App\Http\Controllers\ReservationController::class, 'index'])
            ->name('reservations');

        Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])
            ->name('calendar');

        Route::resource('inventory', \App\Http\Controllers\InventoryItemController::class);
        Route::resource('menus', \App\Http\Controllers\MenuController::class);
    });


// ---------- Customer ----------
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/home', [CustomerHomeController::class, 'index'])->name('customer.home');
});
