<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;

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

        // User Management
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
        Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');

        // Role Management
        Route::resource('roles', RoleController::class);

        // Permission Management
        Route::resource('permissions', PermissionController::class);

        // Profile Management
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
        Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    });
});
