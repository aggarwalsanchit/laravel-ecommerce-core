<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::get('/', [AuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    });

    Route::middleware(['admin.auth'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('admin.dashboard');

        Route::get('/users', [UserController::class, 'showUsers'])->name('admin.users');
        // Route::get('/dashboard', [UserController::class, 'showDashboard'])->name('admin.dashboard');
        // Route::get('/dashboard', [UserController::class, 'showDashboard'])->name('admin.dashboard');
        // Route::get('/dashboard', [UserController::class, 'showDashboard'])->name('admin.dashboard');
        // Route::get('/dashboard', [UserController::class, 'showDashboard'])->name('admin.dashboard');
        // Route::get('/dashboard', [UserController::class, 'showDashboard'])->name('admin.dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    });
});
