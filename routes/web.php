<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\ColorController;

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

        // ==================== CATEGORIES ====================
        Route::get('/categories/analytics', [CategoryController::class, 'analytics'])->name('categories.analytics');
        Route::resource('categories', CategoryController::class);
        Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        Route::post('/categories/{category}/toggle-featured', [CategoryController::class, 'toggleFeatured'])->name('categories.toggle-featured');
        Route::post('/categories/{category}/toggle-popular', [CategoryController::class, 'togglePopular'])->name('categories.toggle-popular');
        Route::post('/categories/{category}/toggle-menu', [CategoryController::class, 'toggleMenu'])->name('categories.toggle-menu');
        Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
        Route::post('/categories/bulk-action', [CategoryController::class, 'bulkAction'])->name('categories.bulk-action');
        Route::get('/categories/subcategories', [CategoryController::class, 'getSubcategories'])->name('categories.subcategories');

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


        Route::get('/sizes/analytics', [SizeController::class, 'analytics'])->name('sizes.analytics');
        Route::post('/sizes/{size}/toggle-status', [SizeController::class, 'toggleStatus'])->name('sizes.toggle-status');
        Route::post('/sizes/bulk-action', [SizeController::class, 'bulkAction'])->name('sizes.bulk-action');
        Route::resource('sizes', SizeController::class);


        Route::get('/colors/analytics', [ColorController::class, 'analytics'])->name('colors.analytics');
        Route::post('/colors/{color}/toggle-status', [ColorController::class, 'toggleStatus'])->name('colors.toggle-status');
        Route::post('/colors/bulk-action', [ColorController::class, 'bulkAction'])->name('colors.bulk-action');
        Route::resource('colors', ColorController::class);

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
