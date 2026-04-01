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
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\FabricController;
use App\Http\Controllers\Admin\OccasionController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\AttributeGroupController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeValueController;

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


        Route::get('/fabrics/analytics', [FabricController::class, 'analytics'])->name('fabrics.analytics');
        Route::post('/fabrics/{fabric}/toggle-status', [FabricController::class, 'toggleStatus'])->name('fabrics.toggle-status');
        Route::post('/fabrics/bulk-action', [FabricController::class, 'bulkAction'])->name('fabrics.bulk-action');
        Route::resource('fabrics', FabricController::class);


        Route::get('/occasions/analytics', [OccasionController::class, 'analytics'])->name('occasions.analytics');
        Route::post('/occasions/{occasion}/toggle-status', [OccasionController::class, 'toggleStatus'])->name('occasions.toggle-status');
        Route::post('/occasions/{occasion}/toggle-featured', [OccasionController::class, 'toggleFeatured'])->name('occasions.toggle-featured');
        Route::post('/occasions/bulk-action', [OccasionController::class, 'bulkAction'])->name('occasions.bulk-action');
        Route::resource('occasions', OccasionController::class);


        Route::get('/collections/analytics', [CollectionController::class, 'analytics'])->name('collections.analytics');
        Route::post('/collections/{collection}/toggle-status', [CollectionController::class, 'toggleStatus'])->name('collections.toggle-status');
        Route::post('/collections/{collection}/toggle-featured', [CollectionController::class, 'toggleFeatured'])->name('collections.toggle-featured');
        Route::post('/collections/bulk-action', [CollectionController::class, 'bulkAction'])->name('collections.bulk-action');
        Route::resource('collections', CollectionController::class);


        Route::get('/seasons/analytics', [SeasonController::class, 'analytics'])->name('seasons.analytics');
        Route::post('/seasons/{season}/toggle-status', [SeasonController::class, 'toggleStatus'])->name('seasons.toggle-status');
        Route::post('/seasons/{season}/set-current', [SeasonController::class, 'setCurrent'])->name('seasons.set-current');
        Route::post('/seasons/bulk-action', [SeasonController::class, 'bulkAction'])->name('seasons.bulk-action');
        Route::resource('seasons', SeasonController::class);


        Route::get('/brands/analytics', [BrandController::class, 'analytics'])->name('brands.analytics');
        Route::post('/brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status');
        Route::post('/brands/{brand}/toggle-featured', [BrandController::class, 'toggleFeatured'])->name('brands.toggle-featured');
        Route::post('/brands/bulk-action', [BrandController::class, 'bulkAction'])->name('brands.bulk-action');
        Route::resource('brands', BrandController::class);

        Route::get('/discounts/analytics', [DiscountController::class, 'analytics'])->name('discounts.analytics');
        Route::post('/discounts/{discount}/toggle-status', [DiscountController::class, 'toggleStatus'])->name('discounts.toggle-status');
        Route::post('/discounts/bulk-action', [DiscountController::class, 'bulkAction'])->name('discounts.bulk-action');
        Route::resource('discounts', DiscountController::class);

        Route::resource('attribute-groups', AttributeGroupController::class);
        Route::post('/attribute-groups/{group}/toggle-status', [AttributeGroupController::class, 'toggleStatus'])->name('attribute-groups.toggle-status');

        // Attributes
        Route::resource('attributes', AttributeController::class);
        Route::get('/attributes/{attribute}/analytics', [AttributeController::class, 'analytics'])->name('attributes.analytics');
        Route::post('/attributes/{attribute}/toggle-status', [AttributeController::class, 'toggleStatus'])->name('attributes.toggle-status');

        // Attribute Values
        Route::get('/attributes/{attribute}/values', [AttributeValueController::class, 'index'])->name('attributes.values.index');
        Route::post('/attributes/{attribute}/values', [AttributeValueController::class, 'store'])->name('attributes.values.store');
        Route::put('/attribute-values/{value}', [AttributeValueController::class, 'update'])->name('attribute-values.update');
        Route::delete('/attribute-values/{value}', [AttributeValueController::class, 'destroy'])->name('attribute-values.destroy');
        Route::get('/attribute-values/export', [AttributeValueController::class, 'export'])->name('values.export');

Route::prefix('attribute-values')->name('attribute-values.')->group(function () {
    Route::get('/{value}', [AttributeValueController::class, 'show'])->name('show');
    Route::get('/{value}/analytics', [AttributeValueController::class, 'analytics'])->name('analytics');
    Route::put('/{value}', [AttributeValueController::class, 'update'])->name('update');
    Route::delete('/{value}', [AttributeValueController::class, 'destroy'])->name('destroy');
    Route::post('/{value}/toggle-default', [AttributeValueController::class, 'toggleDefault'])->name('toggle-default');
    Route::post('/{value}/toggle-visibility', [AttributeValueController::class, 'toggleVisibility'])->name('toggle-visibility');
    Route::post('/{value}/reorder', [AttributeValueController::class, 'reorder'])->name('reorder');
});

Route::resource('attribute-categories', AttributeCategoryController::class);
    Route::post('/attribute-categories/{category}/toggle-status', [AttributeCategoryController::class, 'toggleStatus'])->name('attribute-categories.toggle-status');
    Route::post('/attribute-categories/reorder', [AttributeCategoryController::class, 'reorder'])->name('attribute-categories.reorder');
    Route::get('/attribute-categories/{category}/children', [AttributeCategoryController::class, 'getChildren'])->name('attribute-categories.children');


        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
