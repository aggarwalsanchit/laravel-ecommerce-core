<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class VendorRoleSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // =============================================
        // VENDOR ROLES
        // =============================================

        // 1. VENDOR ROLE (For Pending/Unverified Vendors)
        // This role has ONLY basic permissions (view dashboard and complete profile)
        $vendorRole = Role::create(['name' => 'vendor', 'guard_name' => 'vendor']);
        $vendorRole->givePermissionTo([
            'view_dashboard',
            'complete_profile',
        ]);

        // 2. STORE OWNER ROLE (For Approved/Verified Vendors)
        // This role has ALL permissions
        $storeOwner = Role::create(['name' => 'store_owner', 'guard_name' => 'vendor']);
        // Get all vendor permissions EXCEPT 'complete_profile'
        $allPermissionsExceptCompleteProfile = Permission::where('guard_name', 'vendor')
            ->where('name', '!=', 'complete_profile')
            ->get();

        $storeOwner->givePermissionTo($allPermissionsExceptCompleteProfile);

        // 3. STORE MANAGER ROLE
        $storeManager = Role::create(['name' => 'store_manager', 'guard_name' => 'vendor']);
        $storeManager->givePermissionTo([
            'view_dashboard',
            'view_profile',
            'update_profile',
            'change_password',
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'export_products',
            'view_orders',
            'update_order_status',
            'cancel_orders',
            'export_orders',
            'view_reports',
            'view_analytics',
            'export_reports',
            'manage_store_settings',
            'manage_payment_settings',
            'manage_shipping_settings',
            'view_discounts',
            'create_discounts',
            'edit_discounts',
            'delete_discounts',
            'view_reviews',
            'reply_reviews',
            'request_withdrawal',
            'view_withdrawals',
        ]);

        // 4. PRODUCT MANAGER ROLE
        $productManager = Role::create(['name' => 'product_manager', 'guard_name' => 'vendor']);
        $productManager->givePermissionTo([
            'view_dashboard',
            'view_profile',
            'update_profile',
            'change_password',
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'export_products',
            'import_products',
            'view_discounts',
            'create_discounts',
            'edit_discounts',
            'delete_discounts',
            'view_reviews',
            'reply_reviews',
        ]);

        // 5. ORDER MANAGER ROLE
        $orderManager = Role::create(['name' => 'order_manager', 'guard_name' => 'vendor']);
        $orderManager->givePermissionTo([
            'view_dashboard',
            'view_profile',
            'update_profile',
            'change_password',
            'view_orders',
            'update_order_status',
            'cancel_orders',
            'export_orders',
            'view_reviews',
            'reply_reviews',
        ]);

        // 6. INVENTORY MANAGER ROLE
        $inventoryManager = Role::create(['name' => 'inventory_manager', 'guard_name' => 'vendor']);
        $inventoryManager->givePermissionTo([
            'view_dashboard',
            'view_profile',
            'update_profile',
            'change_password',
            'view_products',
            'create_products',
            'edit_products',
            'import_products',
            'view_orders',
        ]);

        // 7. FULFILLMENT EXECUTIVE ROLE
        $fulfillmentExecutive = Role::create(['name' => 'fulfillment_executive', 'guard_name' => 'vendor']);
        $fulfillmentExecutive->givePermissionTo([
            'view_dashboard',
            'view_profile',
            'update_profile',
            'change_password',
            'view_orders',
            'update_order_status',
        ]);

        // 8. SUPPORT STAFF ROLE
        $supportStaff = Role::create(['name' => 'support_staff', 'guard_name' => 'vendor']);
        $supportStaff->givePermissionTo([
            'view_dashboard',
            'view_profile',
            'update_profile',
            'change_password',
            'view_orders',
            'view_reviews',
            'reply_reviews',
        ]);

        // 9. ACCOUNTANT ROLE
        $accountant = Role::create(['name' => 'accountant', 'guard_name' => 'vendor']);
        $accountant->givePermissionTo([
            'view_dashboard',
            'view_profile',
            'update_profile',
            'change_password',
            'view_reports',
            'view_analytics',
            'export_reports',
            'view_withdrawals',
        ]);

        $this->command->info('========================================');
        $this->command->info('Vendor roles created successfully!');
        $this->command->info('========================================');
        $this->command->info('Roles:');
        $this->command->info('  - vendor (pending vendors - limited permissions)');
        $this->command->info('  - store_owner (approved vendors - full permissions)');
        $this->command->info('  - store_manager');
        $this->command->info('  - product_manager');
        $this->command->info('  - order_manager');
        $this->command->info('  - inventory_manager');
        $this->command->info('  - fulfillment_executive');
        $this->command->info('  - support_staff');
        $this->command->info('  - accountant');
    }
}
