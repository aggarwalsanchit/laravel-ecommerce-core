<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;
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
use App\Http\Controllers\Vendor\VendorForgotPasswordController;
use App\Http\Controllers\Vendor\VendorResetPasswordController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorOrderController;
use App\Http\Controllers\Vendor\VendorStaffController;
use App\Http\Controllers\Vendor\VendorProfileController;
use App\Http\Controllers\Vendor\VendorCategoryController;
use App\Http\Controllers\Vendor\VendorUserController;
use App\Http\Controllers\Vendor\VendorRoleController;
use App\Http\Controllers\Vendor\VendorPermissionController;
use App\Http\Controllers\Vendor\VendorActivityLogController;
use App\Http\Controllers\Vendor\VendorColorController;
use App\Http\Controllers\Vendor\VendorSizeController;
use App\Http\Controllers\Vendor\VendorAttributeController;
use App\Http\Controllers\Vendor\VendorAttributeValueController;


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
            Route::get('/{id}/staff', [AdminVendorController::class, 'staff'])->name('staff');
            Route::post('/{id}/change-type', [AdminVendorController::class, 'changeType'])->name('change-type');
            Route::post('/{id}/approve', [AdminVendorController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [AdminVendorController::class, 'reject'])->name('reject');
            Route::post('/{id}/suspend', [AdminVendorController::class, 'suspend'])->name('suspend');
            Route::post('/{id}/send-message', [AdminVendorController::class, 'sendMessage'])->name('send-message');
            // Staff routes
            Route::get('/{id}/staff', [AdminVendorController::class, 'staff'])->name('staff');
            Route::post('/{id}/staff/bulk-action', [AdminVendorController::class, 'staffBulkAction'])->name('staff.bulk-action');
            Route::post('/staff/{id}/activate', [AdminVendorController::class, 'activateStaff'])->name('staff.activate');
            Route::post('/staff/{id}/deactivate', [AdminVendorController::class, 'deactivateStaff'])->name('staff.deactivate');
            Route::delete('/staff/{id}', [AdminVendorController::class, 'deleteStaff'])->name('staff.delete');
        });

        // ==================== CATEGORIES ====================

        // Category Routes
        Route::prefix('categories')->name('categories.')->group(function () {
            // Request management routes (must come before resource)
            Route::get('/requests', [CategoryController::class, 'pendingRequests'])->name('requests');
            Route::get('/requests/{id}', [CategoryController::class, 'viewRequest'])->name('requests.show');
            Route::post('/requests/{id}/approve', [CategoryController::class, 'approveRequest'])->name('requests.approve');
            Route::post('/requests/{id}/reject', [CategoryController::class, 'rejectRequest'])->name('requests.reject');
            Route::delete('/requests/{id}', [CategoryController::class, 'deleteRequest'])->name('requests.delete');
            Route::post('/requests/bulk-action', [CategoryController::class, 'bulkRequestAction'])->name('requests.bulk-action');

            // Analytics
            Route::get('/analytics', [CategoryController::class, 'analytics'])->name('analytics');

            // Bulk action
            Route::post('/bulk-action', [CategoryController::class, 'bulkAction'])->name('bulk-action');

            // Toggle routes
            Route::post('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{category}/toggle-menu', [CategoryController::class, 'toggleMenu'])->name('toggle-menu');
            Route::post('/{category}/toggle-featured', [CategoryController::class, 'toggleFeatured'])->name('toggle-featured');
            Route::post('/{category}/toggle-popular', [CategoryController::class, 'togglePopular'])->name('toggle-popular');

            // Subcategories
            Route::get('/subcategories', [CategoryController::class, 'getSubcategories'])->name('subcategories');
            Route::get('/{category}/subcategories', [CategoryController::class, 'getSubcategories'])->name('subcategories.by-category');
        });

        // Resource route for categories (must come after custom routes)
        Route::resource('categories', CategoryController::class);


        // ==================== SIZE ROUTES ====================
        Route::prefix('sizes')->name('sizes.')->group(function () {

            // Request management routes (must come before resource)
            Route::get('/requests', [SizeController::class, 'pendingRequests'])->name('requests');
            Route::get('/requests/{id}', [SizeController::class, 'viewRequest'])->name('requests.show');
            Route::post('/requests/{id}/approve', [SizeController::class, 'approveRequest'])->name('requests.approve');
            Route::post('/requests/{id}/reject', [SizeController::class, 'rejectRequest'])->name('requests.reject');
            Route::delete('/requests/{id}', [SizeController::class, 'deleteRequest'])->name('requests.delete');
            Route::post('/requests/bulk-action', [SizeController::class, 'bulkRequestAction'])->name('requests.bulk-action');

            // Analytics
            Route::get('/analytics', [SizeController::class, 'analytics'])->name('analytics');

            // Bulk action
            Route::post('/bulk-action', [SizeController::class, 'bulkAction'])->name('bulk-action');

            // Toggle routes
            Route::post('/{size}/toggle-status', [SizeController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{size}/toggle-featured', [SizeController::class, 'toggleFeatured'])->name('toggle-featured');
            Route::post('/{size}/toggle-popular', [SizeController::class, 'togglePopular'])->name('toggle-popular');
        });

        // Resource route for sizes (must come after custom routes)
        Route::resource('sizes', SizeController::class);


        // Color Routes
        Route::prefix('colors')->name('colors.')->group(function () {
            // Request management routes (must come before resource)
            Route::get('/requests', [ColorController::class, 'pendingRequests'])->name('requests');
            Route::get('/requests/{id}', [ColorController::class, 'viewRequest'])->name('requests.show');
            Route::post('/requests/{id}/approve', [ColorController::class, 'approveRequest'])->name('requests.approve');
            Route::post('/requests/{id}/reject', [ColorController::class, 'rejectRequest'])->name('requests.reject');
            Route::delete('/requests/{id}', [ColorController::class, 'deleteRequest'])->name('requests.delete');
            Route::post('/requests/bulk-action', [ColorController::class, 'bulkRequestAction'])->name('requests.bulk-action');

            // Analytics
            Route::get('/analytics', [ColorController::class, 'analytics'])->name('analytics');

            // Bulk action
            Route::post('/bulk-action', [ColorController::class, 'bulkAction'])->name('bulk-action');

            // Toggle routes
            Route::post('/{color}/toggle-status', [ColorController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{color}/toggle-featured', [ColorController::class, 'toggleFeatured'])->name('toggle-featured');
            Route::post('/{color}/toggle-popular', [ColorController::class, 'togglePopular'])->name('toggle-popular');
        });

        // Resource route for colors (must come after custom routes)
        Route::resource('colors', ColorController::class);


       // ==================== ATTRIBUTE GROUP ROUTES ====================
    Route::prefix('attribute-groups')->name('attribute-groups.')->group(function () {
        // Request management routes
        Route::get('/requests', [AttributeGroupController::class, 'pendingRequests'])->name('requests');
        Route::get('/requests/{id}', [AttributeGroupController::class, 'viewRequest'])->name('requests.show');
        Route::post('/requests/{id}/approve', [AttributeGroupController::class, 'approveRequest'])->name('requests.approve');
        Route::post('/requests/{id}/reject', [AttributeGroupController::class, 'rejectRequest'])->name('requests.reject');
        Route::delete('/requests/{id}', [AttributeGroupController::class, 'deleteRequest'])->name('requests.delete');
        Route::post('/requests/bulk-action', [AttributeGroupController::class, 'bulkRequestAction'])->name('requests.bulk-action');
        
        // Analytics
        Route::get('/analytics', [AttributeGroupController::class, 'analytics'])->name('analytics');
        
        // Bulk action
        Route::post('/bulk-action', [AttributeGroupController::class, 'bulkAction'])->name('bulk-action');
        
        // Toggle routes
        Route::post('/{group}/toggle-status', [AttributeGroupController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    Route::resource('attribute-groups', AttributeGroupController::class);
    
    // ==================== ATTRIBUTE ROUTES ====================
    Route::prefix('attributes')->name('attributes.')->group(function () {
        // Request management routes
        Route::get('/requests', [AttributeController::class, 'pendingRequests'])->name('requests');
        Route::get('/requests/{id}', [AttributeController::class, 'viewRequest'])->name('requests.show');
        Route::post('/requests/{id}/approve', [AttributeController::class, 'approveRequest'])->name('requests.approve');
        Route::post('/requests/{id}/reject', [AttributeController::class, 'rejectRequest'])->name('requests.reject');
        Route::delete('/requests/{id}', [AttributeController::class, 'deleteRequest'])->name('requests.delete');
        Route::post('/requests/bulk-action', [AttributeController::class, 'bulkRequestAction'])->name('requests.bulk-action');
        
        // Value request routes
        Route::get('/value-requests', [AttributeController::class, 'valueRequests'])->name('value-requests');
        Route::get('/value-requests/{id}', [AttributeController::class, 'viewValueRequest'])->name('value-requests.show');
        Route::post('/value-requests/{id}/approve', [AttributeController::class, 'approveValueRequest'])->name('value-requests.approve');
        Route::post('/value-requests/{id}/reject', [AttributeController::class, 'rejectValueRequest'])->name('value-requests.reject');
        Route::delete('/value-requests/{id}', [AttributeController::class, 'deleteValueRequest'])->name('value-requests.delete');
        Route::post('/value-requests/bulk-action', [AttributeController::class, 'bulkValueRequestAction'])->name('value-requests.bulk-action');
        
        // Values management
        Route::get('/{attribute}/values', [AttributeController::class, 'manageValues'])->name('values');
        Route::post('/{attribute}/values', [AttributeController::class, 'storeValue'])->name('values.store');
        Route::put('/values/{value}', [AttributeController::class, 'updateValue'])->name('values.update');
        Route::delete('/values/{value}', [AttributeController::class, 'destroyValue'])->name('values.destroy');
        Route::post('/values/reorder', [AttributeController::class, 'reorderValues'])->name('values.reorder');
        
        // Analytics
        Route::get('/analytics', [AttributeController::class, 'analytics'])->name('analytics');
        Route::get('/analytics/{attribute}', [AttributeController::class, 'attributeAnalytics'])->name('analytics.show');
        
        // Bulk action
        Route::post('/bulk-action', [AttributeController::class, 'bulkAction'])->name('bulk-action');
        
        // Toggle routes
        Route::post('/{attribute}/toggle-status', [AttributeController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{attribute}/toggle-featured', [AttributeController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/{attribute}/toggle-filterable', [AttributeController::class, 'toggleFilterable'])->name('toggle-filterable');
        
        // Get attributes by category (for AJAX)
        Route::get('/by-category/{category}', [AttributeController::class, 'getByCategory'])->name('by-category');
    });
    
    Route::resource('attributes', AttributeController::class);

        Route::get('/discounts/analytics', [DiscountController::class, 'analytics'])->name('discounts.analytics');
        Route::post('/discounts/{discount}/toggle-status', [DiscountController::class, 'toggleStatus'])->name('discounts.toggle-status');
        Route::post('/discounts/bulk-action', [DiscountController::class, 'bulkAction'])->name('discounts.bulk-action');
        Route::get('products/{product}/discounts', [DiscountController::class, 'getProductDiscounts'])->name('products.discounts');
        Route::get('discounts/attribute-values/{attributeId}', [App\Http\Controllers\Admin\DiscountController::class, 'getAttributeValues'])
            ->name('discounts.attribute-values');
        Route::resource('discounts', DiscountController::class);

       




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
        Route::get('become-seller', [VendorAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [VendorAuthController::class, 'register'])->name('register.submit');
        Route::get('/', [VendorAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [VendorAuthController::class, 'login'])->name('login.submit');
        Route::post('logout', [VendorAuthController::class, 'logout'])->name('logout');
        // Password Reset Routes
        Route::get('forgot-password', [VendorForgotPasswordController::class, 'showForgotForm'])->name('password.request');
        Route::post('forgot-password', [VendorForgotPasswordController::class, 'sendResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [VendorResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [VendorResetPasswordController::class, 'reset'])->name('password.update');
    });

    // Authenticated Vendor Routes
    Route::middleware('vendor.auth')->group(function () {
        // Dashboard
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
        Route::get('complete-profile', [VendorApprovedController::class, 'showCompleteForm'])->name('complete-profile');
        Route::post('complete-profile', [VendorApprovedController::class, 'saveTab'])->name('profile.save-tab');

        Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
            Route::get('/', [VendorActivityLogController::class, 'index'])->name('index');
            Route::get('/{id}', [VendorActivityLogController::class, 'show'])->name('show');
            Route::get('/export/csv', [VendorActivityLogController::class, 'export'])->name('export');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [VendorProfileController::class, 'index'])->name('index');
            Route::get('/edit', [VendorProfileController::class, 'edit'])->name('edit');
            Route::put('update', [VendorProfileController::class, 'update'])->name('update');
            Route::get('/change-password', [VendorProfileController::class, 'changePassword'])->name('change-password');
            Route::post('/update-password', [VendorProfileController::class, 'updatePassword'])->name('update-password');
        });

        Route::get('location/states/{countryId}', [VendorProfileController::class, 'getStates'])->name('location.states');
        Route::get('location/cities/{stateId}', [VendorProfileController::class, 'getCities'])->name('location.cities');
        Route::get('vendor/location/phone-code/{countryId}', [VendorProfileController::class, 'getPhoneCode'])->name('admin.location.phone-code');

        Route::prefix('staff')->name('staff.')->group(function () {
            Route::get('/', [VendorUserController::class, 'index'])->name('index');
            Route::get('/create', [VendorUserController::class, 'create'])->name('create');
            Route::post('/', [VendorUserController::class, 'store'])->name('store');
            Route::get('/{id}', [VendorUserController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [VendorUserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [VendorUserController::class, 'update'])->name('update');
            Route::delete('/{id}', [VendorUserController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [VendorUserController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/bulk-action', [VendorUserController::class, 'bulkAction'])->name('bulk-action');
            Route::post('/{id}/resend-invitation', [VendorUserController::class, 'resendInvitation'])->name('resend-invitation');
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


        // ==================== CATEGORIES ====================

        // Category Routes
        Route::prefix('categories')->name('categories.')->group(function () {
        // View categories
        Route::get('/', [VendorCategoryController::class, 'index'])->name('index');
        Route::get('/requests', [VendorCategoryController::class, 'myRequests'])->name('requests.index');
        Route::get('/{id}', [VendorCategoryController::class, 'show'])->name('show');
        
        // Category requests
        
        Route::get('/request/create', [VendorCategoryController::class, 'createRequest'])->name('request.create');
        Route::post('/request', [VendorCategoryController::class, 'storeRequest'])->name('request.store');
        
        Route::get('/requests/{id}', [VendorCategoryController::class, 'showRequest'])->name('requests.show');
        Route::delete('/requests/{id}', [VendorCategoryController::class, 'cancelRequest'])->name('requests.cancel');
        
        // AJAX endpoints
        Route::get('/subcategories/{categoryId}', [VendorCategoryController::class, 'getSubcategories'])->name('subcategories');
        Route::get('/tree', [VendorCategoryController::class, 'getCategoryTree'])->name('tree');
    });


// ==================== SIZE ROUTES ====================
        Route::prefix('sizes')->name('sizes.')->group(function () {
        // View sizes
        Route::get('/', [VendorSizeController::class, 'index'])->name('index');
        Route::get('/requests', [VendorSizeController::class, 'myRequests'])->name('requests.index');
        Route::get('/{id}', [VendorSizeController::class, 'show'])->name('show');
        
        // Size requests
        Route::get('/request/create', [VendorSizeController::class, 'createRequest'])->name('request.create');
        Route::post('/request', [VendorSizeController::class, 'storeRequest'])->name('request.store');
        Route::get('/requests/{id}', [VendorSizeController::class, 'showRequest'])->name('requests.show');
        Route::delete('/requests/{id}', [VendorSizeController::class, 'cancelRequest'])->name('requests.cancel');
        
        // AJAX endpoints
        Route::get('/by-category/{categoryId}', [VendorSizeController::class, 'getSizesByCategory'])->name('by-category');
    });


       // Color Routes (Read-only for vendors)
    Route::prefix('colors')->name('colors.')->group(function () {
        // View colors
        Route::get('/', [VendorColorController::class, 'index'])->name('index');
        Route::get('/requests', [VendorColorController::class, 'myRequests'])->name('requests.index');
        Route::get('/{id}', [VendorColorController::class, 'show'])->name('show');
        
        // Color requests
        Route::get('/request/create', [VendorColorController::class, 'createRequest'])->name('request.create');
        Route::post('/request', [VendorColorController::class, 'storeRequest'])->name('request.store');
        Route::get('/requests/{id}', [VendorColorController::class, 'showRequest'])->name('requests.show');
        Route::delete('/requests/{id}', [VendorColorController::class, 'cancelRequest'])->name('requests.cancel');
    });


       // ==================== ATTRIBUTE GROUP ROUTES ====================
     // Attribute Routes (Read-only for vendors)
    Route::prefix('attributes')->name('attributes.')->group(function () {
        // View attributes
        Route::get('/', [VendorAttributeController::class, 'index'])->name('index');
        Route::get('/requests', [VendorAttributeController::class, 'myRequests'])->name('requests.index');
        Route::get('/value-requests', [VendorAttributeController::class, 'myValueRequests'])->name('value-requests.index');
        Route::get('/{id}', [VendorAttributeController::class, 'show'])->name('show');
        
        // Attribute requests
        Route::get('/request/create', [VendorAttributeController::class, 'createRequest'])->name('request.create');
        Route::post('/request', [VendorAttributeController::class, 'storeRequest'])->name('request.store');
        Route::get('/requests/{id}', [VendorAttributeController::class, 'showRequest'])->name('requests.show');
        Route::delete('/requests/{id}', [VendorAttributeController::class, 'cancelRequest'])->name('requests.cancel');
        
        // Attribute value requests
        
        Route::get('/value-request/create', [VendorAttributeController::class, 'createValueRequest'])->name('value-request.create');
        Route::post('/value-request', [VendorAttributeController::class, 'storeValueRequest'])->name('value-request.store');
        Route::get('/value-requests/{id}', [VendorAttributeController::class, 'showValueRequest'])->name('value-requests.show');
        Route::delete('/value-requests/{id}', [VendorAttributeController::class, 'cancelValueRequest'])->name('value-requests.cancel');
        
        // AJAX endpoints
        Route::get('/by-category/{categoryId}', [VendorAttributeController::class, 'getAttributesByCategory'])->name('by-category');
        Route::get('/values/{attributeId}', [VendorAttributeController::class, 'getAttributeValues'])->name('values');
    });





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

        // // Staff Management (for vendors with multiple users)
        // Route::resource('staff', VendorStaffController::class);
        // Route::post('/staff/{staff}/toggle-status', [VendorStaffController::class, 'toggleStatus'])->name('staff.toggle-status');

        // Reports
        Route::get('/reports/sales', [VendorDashboardController::class, 'salesReport'])->name('reports.sales');
        Route::get('/reports/products', [VendorDashboardController::class, 'productReport'])->name('reports.products');

        // Analytics
        Route::get('/analytics', [VendorDashboardController::class, 'analytics'])->name('analytics');

        // Payouts
        Route::get('/payouts', [VendorDashboardController::class, 'payouts'])->name('payouts');
    });
});

Route::get('get-states', function (Request $request) {
    return response()->json(\App\Models\State::where('country_id', $request->country_id)->get());
})->name('get.states');

Route::get('get-cities', function (Request $request) {
    return response()->json(\App\Models\City::where('state_id', $request->state_id)->get());
})->name('get.cities');

Route::get('get-subcategories', function (Request $request) {
    $category = \App\Models\Category::with('children')->find($request->category_id);
    return response()->json($category->children);
})->name('get.subcategories');
