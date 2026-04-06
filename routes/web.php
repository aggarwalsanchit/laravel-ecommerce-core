<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\AdminVendorController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminActivityLogController;
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
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Vendor\VendorApprovedController;
use App\Http\Controllers\Vendor\VendorManagementController;
use App\Http\Controllers\Vendor\VendorAuthController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorOrderController;
use App\Http\Controllers\Vendor\VendorStaffController;
use App\Http\Controllers\Vendor\VendorProfileController;
use App\Http\Controllers\Vendor\VendorSettingsController;
use App\Http\Controllers\Vendor\VendorUserController;
use App\Http\Controllers\Vendor\VendorRoleController;
use App\Http\Controllers\Vendor\VendorPermissionController;
use App\Http\Controllers\Vendor\VendorActivityLogController;


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
        Route::get('/categories/{category}/subcategories', [CategoryController::class, 'getSubcategories'])
            ->name('admin.categories.subcategories');

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
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
    
Route::get('location/states/{countryId}', [ProfileController::class, 'getStates'])->name('location.states');
    Route::get('location/cities/{stateId}', [ProfileController::class, 'getCities'])->name('location.cities');
    Route::get('admin/location/phone-code/{countryId}', [ProfileController::class, 'getPhoneCode'])->name('admin.location.phone-code');

    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [AdminActivityLogController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminActivityLogController::class, 'show'])->name('show');
        Route::get('/export/csv', [AdminActivityLogController::class, 'export'])->name('export');
    });

        // Admin Vendor Management Routes
        Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/', [AdminVendorController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminVendorController::class, 'show'])->name('show');
        
        // Approval routes
        Route::get('/{id}/approve', [AdminVendorController::class, 'approveForm'])->name('approve.form');
        Route::post('/{id}/approve', [AdminVendorController::class, 'approve'])->name('approve');
        
        // Rejection routes
        Route::post('/{id}/reject', [AdminVendorController::class, 'reject'])->name('reject');
        
        // Suspension routes
        Route::post('/{id}/suspend', [AdminVendorController::class, 'suspend'])->name('suspend');
        Route::get('/{id}/activate', [AdminVendorController::class, 'activate'])->name('activate');
        
        // Delete route
        Route::delete('/{id}', [AdminVendorController::class, 'destroy'])->name('destroy');
        
        // Bulk Action Route - ADD THIS LINE
        Route::post('/bulk-action', [AdminVendorController::class, 'bulkAction'])->name('bulk-action');
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
        Route::get('products/{product}/discounts', [DiscountController::class, 'getProductDiscounts'])->name('products.discounts');
        Route::get('discounts/attribute-values/{attributeId}', [App\Http\Controllers\Admin\DiscountController::class, 'getAttributeValues'])
            ->name('discounts.attribute-values');
        Route::resource('discounts', DiscountController::class);

        Route::resource('attribute-groups', AttributeGroupController::class);
        Route::post('/attribute-groups/{group}/toggle-status', [AttributeGroupController::class, 'toggleStatus'])->name('attribute-groups.toggle-status');

        // Attributes
        Route::resource('attributes', AttributeController::class);
        Route::get('/attributes/by-category/{category}', [AttributeController::class, 'getByCategory'])
            ->name('admin.attributes.by-category');
        Route::get('/attributes/{attribute}/analytics', [AttributeController::class, 'analytics'])->name('attributes.analytics');
        Route::post('/attributes/{attribute}/toggle-status', [AttributeController::class, 'toggleStatus'])->name('attributes.toggle-status');

        // Attribute Values
        Route::get('/attributes/{attribute}/values', [AttributeValueController::class, 'index'])->name('attributes.values.index');
        Route::post('/attributes/{attribute}/values', [AttributeValueController::class, 'store'])->name('attributes.values.store');
        Route::put('/attribute-values/{value}', [AttributeValueController::class, 'update'])->name('attribute-values.update');
        Route::delete('/attribute-values/{value}', [AttributeValueController::class, 'destroy'])->name('attribute-values.destroy');
        Route::get('/attribute-values/export', [AttributeValueController::class, 'export'])->name('attributes.values.export');

        Route::prefix('attribute-values')->name('attribute-values.')->group(function () {
            Route::get('/{value}', [AttributeValueController::class, 'show'])->name('show');
            Route::get('/{value}/analytics', [AttributeValueController::class, 'analytics'])->name('analytics');
            Route::put('/{value}', [AttributeValueController::class, 'update'])->name('update');
            Route::delete('/{value}', [AttributeValueController::class, 'destroy'])->name('destroy');
            Route::post('/{value}/toggle-default', [AttributeValueController::class, 'toggleDefault'])->name('toggle-default');
            Route::post('/{value}/toggle-visibility', [AttributeValueController::class, 'toggleVisibility'])->name('toggle-visibility');
            Route::post('/{value}/reorder', [AttributeValueController::class, 'reorder'])->name('reorder');
        });




        Route::post('/products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
        Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::post('/products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
        Route::get('/products/{product}/analytics', [ProductController::class, 'analytics'])->name('products.analytics');
        Route::get('/products/get-fresh-data', [ProductController::class, 'getFreshData'])->name('products.get-fresh-data');
        Route::resource('products', ProductController::class);

        Route::post('/colors/quick-store', [ColorController::class, 'quickStore'])->name('colors.quick-store');
        Route::post('/sizes/quick-store', [SizeController::class, 'quickStore'])->name('sizes.quick-store');
        Route::post('/categories/quick-store', [CategoryController::class, 'quickStore'])->name('categories.quick-store');
        Route::post('/attributes/quick-store', [AttributeController::class, 'quickStore'])->name('attributes.quick-store');
        Route::post('/attribute-values/quick-store', [AttributeValueController::class, 'quickStore'])->name('attribute-values.quick-store');

        //  Route::resource('discounts', App\Http\Controllers\Admin\DiscountController::class);


        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});



Route::prefix('marketplace')->name('vendor.')->group(function () {

    // Guest Routes (Not logged in as vendor)
    Route::middleware('vendor.guest')->group(function () {
        Route::get('/become-seller', [VendorAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [VendorAuthController::class, 'register'])->name('register.submit');
        Route::get('/', [VendorAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [VendorAuthController::class, 'login'])->name('login.submit');
        Route::get('/forgot-password', [VendorAuthController::class, 'showForgotForm'])->name('password.request');
        Route::post('/forgot-password', [VendorAuthController::class, 'sendResetLink'])->name('password.email');
    });

    // Authenticated Vendor Routes
    Route::middleware('vendor.auth')->group(function () {
        // Dashboard
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
        Route::get('complete-profile', [VendorApprovedController::class, 'showCompleteForm'])->name('complete-profile');
        Route::post('complete-profile', [VendorApprovedController::class, 'saveTab'])->name('profile.save-tab');

        Route::get('activity-logs', [VendorActivityLogController::class, 'index'])->name('activity-logs');
        Route::get('activity-logs/{id}', [VendorActivityLogController::class, 'show'])->name('activity-logs.show');
        Route::get('activity-logs/export/csv', [VendorActivityLogController::class, 'export'])->name('activity-logs.export');

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', [VendorProfileController::class, 'edit'])->name('edit');
            Route::put('update', [VendorProfileController::class, 'update'])->name('update');
            Route::get('/change-password', [VendorProfileController::class, 'changePassword'])->name('change-password');
            Route::post('/update-password', [VendorProfileController::class, 'updatePassword'])->name('update-password');
        });

        // ==================== USERS ====================
        Route::prefix('users')->name('users.')->group(function () {
            Route::resource('/', VendorUserController::class);
            Route::post('/{user}/activate', [VendorUserController::class, 'activate'])->name('activate');
            Route::post('/{user}/deactivate', [VendorUserController::class, 'deactivate'])->name('deactivate');
            Route::post('/bulk-action', [VendorUserController::class, 'bulkAction'])->name('bulk-action');
        });
        // ==================== ROLES ====================
        Route::resource('roles', VendorRoleController::class);
        Route::get('/roles/{role}/assign-permissions', [VendorRoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
        Route::post('/roles/{role}/sync-permissions', [VendorRoleController::class, 'syncPermissions'])->name('roles.sync-permissions');
        Route::post('/roles/bulk-action', [VendorRoleController::class, 'bulkAction'])->name('roles.bulk-action');

        // ==================== PERMISSIONS ====================
        Route::resource('permissions', VendorPermissionController::class);
        Route::post('/permissions/bulk-action', [VendorPermissionController::class, 'bulkAction'])->name('permissions.bulk-action');








        
        Route::get('/orders', [VendorOrderController::class, 'index'])->name('orders');

        Route::post('/logout', [VendorAuthController::class, 'logout'])->name('logout');

        // Profile Management


        // Shop Settings
        Route::get('/settings', [VendorSettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [VendorSettingsController::class, 'update'])->name('settings.update');

        // Product Management
        Route::resource('products', VendorProductController::class);
        Route::post('/products/{product}/toggle-status', [VendorProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::post('/products/bulk-action', [VendorProductController::class, 'bulkAction'])->name('products.bulk-action');

        // Order Management
        Route::get('/products', [VendorOrderController::class, 'index'])->name('products');
        Route::get('/orders/{order}', [VendorOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/update-status', [VendorOrderController::class, 'updateStatus'])->name('orders.update-status');

        // Staff Management (for vendors with multiple users)
        Route::resource('staff', VendorStaffController::class);
        Route::post('/staff/{staff}/toggle-status', [VendorStaffController::class, 'toggleStatus'])->name('staff.toggle-status');

        // Reports
        Route::get('/reports/sales', [VendorDashboardController::class, 'salesReport'])->name('reports.sales');
        Route::get('/reports/products', [VendorDashboardController::class, 'productReport'])->name('reports.products');

        // Analytics
        Route::get('/analytics', [VendorDashboardController::class, 'analytics'])->name('analytics');

        // Payouts
        Route::get('/payouts', [VendorDashboardController::class, 'payouts'])->name('payouts');
    });
});
