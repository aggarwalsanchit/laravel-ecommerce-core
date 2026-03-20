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

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::get('/', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });


    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');

        // ==================== USERS ====================
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
        Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');

        // ==================== ROLES ====================
        Route::resource('roles', RoleController::class);
        Route::get('/roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
        Route::post('/roles/{role}/sync-permissions', [RoleController::class, 'syncPermissions'])->name('roles.sync-permissions');
        Route::post('/roles/bulk-action', [RoleController::class, 'bulkAction'])->name('roles.bulk-action');

        // ==================== PERMISSIONS ====================
        Route::resource('permissions', PermissionController::class);
        Route::post('/permissions/bulk-action', [PermissionController::class, 'bulkAction'])->name('permissions.bulk-action');

        // ==================== PROFILE ====================
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::post('/avatar', [ProfileController::class, 'uploadAvatar'])->name('avatar');
            Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
            Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('password');
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
