<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\{
    MenuController, RecipeController, ReservationController, CalendarController, InventoryItemController
};
// ---------- Index -> Login ----------
Route::get('/', fn () => redirect()->route('login'))->name('home');

// ---------- Breeze auth routes (login, register, logout, password, etc.) ----------
require __DIR__ . '/auth.php';

// ---------- Dashboard redirect helper ----------
Route::get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
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
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

        Route::resource('inventory', InventoryItemController::class);
        Route::resource('menus', MenuController::class);
        Route::post('/menus/{menu}/items', [MenuController::class,'addItem'])->name('menus.items.store');

        // Recipes
        Route::get   ('/menu-items/{menuItem}/recipes', [RecipeController::class,'index'])->name('recipes.index');
        Route::post  ('/menu-items/{menuItem}/recipes', [RecipeController::class,'store'])->name('recipes.store');
        Route::delete('/menu-items/{menuItem}/recipes/{recipe}', [RecipeController::class,'destroy'])->name('recipes.destroy');

        // Reservations (names align with your Blade: admin.reservations, admin.reservations.show, etc.)
        Route::get  ('/reservations',                       [ReservationController::class,'index'])->name('reservations');
        Route::get  ('/reservations/{reservation}',         [ReservationController::class,'show'])->name('reservations.show');
        Route::patch('/reservations/{reservation}/approve', [ReservationController::class,'approve'])->name('reservations.approve');
        Route::patch('/reservations/{reservation}/decline', [ReservationController::class,'decline'])->name('reservations.decline');
    });



// ---------- Customer ----------
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/home', [CustomerHomeController::class, 'index'])->name('customer.home');
    Route::post('/reservations', [CustomerHomeController::class, 'store'])->name('reservations.store');
});
