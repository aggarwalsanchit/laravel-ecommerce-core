<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // =============================================
        // ADMIN GUARD PERMISSIONS
        // =============================================

        $adminPermissions = [
            // Dashboard
            'view_dashboard',

            // Profile Management
            'view_profile',
            'change_password',
            'edit_profile',

            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'activate_users',
            'deactivate_users',
            'suspend_users',
            'impersonate_users',

            // Role Management
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'assign_roles',

            // Permission Management
            'view_permissions',
            'create_permissions',
            'edit_permissions',
            'delete_permissions',
            'assign_permissions',

            // Vendor Management
            'view_vendors',
            'create_vendors',
            'edit_vendors',
            'delete_vendors',
            'approve_vendors',
            'suspend_vendors',

            // Product Management
            'view_all_products',
            'create_all_products',
            'edit_all_products',
            'delete_all_products',
            'feature_products',
            'export_products',
            'import_products',

            // Order Management
            'view_all_orders',
            'update_all_orders',
            'cancel_all_orders',

            // Category Management
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',

            // Color Management
            'view_colors',
            'create_colors',
            'edit_colors',
            'delete_colors',

            // Size Management
            'view_sizes',
            'create_sizes',
            'edit_sizes',
            'delete_sizes',

            // Custom Attributes Management
            'view_attributes',
            'create_attributes',
            'edit_attributes',
            'delete_attributes',

            // Discount Management (For approved vendors)
            'view_discounts',
            'create_discounts',
            'edit_discounts',
            'delete_discounts',

            // Activity Logs Management (For approved vendors)
            'view_logs',

            // Commission Management
            'view_commissions',
            'manage_commissions',

            // Payment Management
            'view_payments',
            'manage_payouts',

            // Report Management
            'view_reports',
            'export_reports',

            // Settings
            'manage_system_settings',
            'manage_email_settings',
            'manage_payment_gateways',

            // Support
            'view_tickets',
            'reply_tickets',
            'resolve_tickets',
        ];

        foreach ($adminPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

        // =============================================
        // VENDOR GUARD PERMISSIONS
        // =============================================

        $vendorPermissions = [
            // Basic Permissions (For pending vendors)
            'view_dashboard',
            'complete_profile',

            // Profile Permissions
            'view_profile',
            'update_profile',
            'change_password',

            // Role Management
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'assign_roles',

            // Permission Management
            'view_permissions',
            'create_permissions',
            'edit_permissions',
            'delete_permissions',
            'assign_permissions',

            // Product Permissions (For approved vendors)
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'export_products',
            'import_products',
            'feature_products',

            // Category Management
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',

            // Color Management
            'view_colors',
            'create_colors',
            'edit_colors',
            'delete_colors',

            // Size Management
            'view_sizes',
            'create_sizes',
            'edit_sizes',
            'delete_sizes',

            // Custom Attributes Management
            'view_attributes',
            'create_attributes',
            'edit_attributes',
            'delete_attributes',

            // Order Permissions (For approved vendors)
            'view_orders',
            'update_order_status',
            'cancel_orders',
            'export_orders',

            // Report Permissions (For approved vendors)
            'view_reports',
            'view_analytics',
            'export_reports',

            // Staff Management (For approved vendors)
            'view_staff',
            'create_staff',
            'edit_staff',
            'delete_staff',
            'manage_staff',

            // Store Settings (For approved vendors)
            'manage_store_settings',
            'manage_payment_settings',
            'manage_shipping_settings',

            // Discount Management (For approved vendors)
            'view_discounts',
            'create_discounts',
            'edit_discounts',
            'delete_discounts',

            // Review Management (For approved vendors)
            'view_reviews',
            'reply_reviews',

            // Withdrawal Management (For approved vendors)
            'request_withdrawal',
            'view_withdrawals',

            // Activity Logs Management (For approved vendors)
            'view_logs',
        ];

        foreach ($vendorPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'vendor']);
        }

        $this->command->info('All permissions created successfully!');
        $this->command->info('Admin permissions: ' . count($adminPermissions));
        $this->command->info('Vendor permissions: ' . count($vendorPermissions));
    }
}
