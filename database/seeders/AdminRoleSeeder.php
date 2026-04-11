<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminRoleSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // =============================================
        // ADMIN ROLES
        // =============================================

        // 1. SUPER ADMIN (Full Access to everything)
        $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'admin']);
        $superAdmin->givePermissionTo(Permission::where('guard_name', 'admin')->get());

        // 2. ADMIN MANAGER (Manage admins, roles, permissions, settings)
        $adminManager = Role::create(['name' => 'admin_manager', 'guard_name' => 'admin']);
        $adminManager->givePermissionTo(Permission::where('guard_name', 'admin')->get());

        // 3. VENDOR MANAGER (Manage vendors, commissions, payouts)
        $vendorManager = Role::create(['name' => 'vendor_manager', 'guard_name' => 'admin']);
        $vendorManager->givePermissionTo([
            'view_dashboard',
            'view_vendors',
            'create_vendors',
            'edit_vendors',
            'delete_vendors',
            'approve_vendors',
            'suspend_vendors',
            'view_commissions',
            'manage_commissions',
            'view_payments',
            'manage_payouts',
            'view_reports',
            'export_reports',
        ]);

        // 4. PRODUCT MANAGER (Manage products, categories, brands)
        $productManager = Role::create(['name' => 'product_manager', 'guard_name' => 'admin']);
        $productManager->givePermissionTo([
            'view_dashboard',
            'view_all_products',
            'create_all_products',
            'edit_all_products',
            'delete_all_products',
            'feature_products',
            'export_products',
            'import_products',
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',
        ]);

        // 5. ORDER MANAGER (Manage all orders)
        $orderManager = Role::create(['name' => 'order_manager', 'guard_name' => 'admin']);
        $orderManager->givePermissionTo([
            'view_dashboard',
            'view_all_orders',
            'update_all_orders',
            'cancel_all_orders',
            'view_payments',
        ]);

        // 6. SUPPORT AGENT (Handle customer support tickets)
        $supportAgent = Role::create(['name' => 'support_agent', 'guard_name' => 'admin']);
        $supportAgent->givePermissionTo([
            'view_dashboard',
            'view_tickets',
            'reply_tickets',
            'resolve_tickets',
            'view_all_orders',
        ]);

        // 7. ACCOUNTANT (View financial reports only)
        $accountant = Role::create(['name' => 'accountant', 'guard_name' => 'admin']);
        $accountant->givePermissionTo([
            'view_dashboard',
            'view_reports',
            'export_reports',
            'view_payments',
            'view_commissions',
        ]);

        // 8. VIEWER (Read-only access)
        $viewer = Role::create(['name' => 'viewer', 'guard_name' => 'admin']);
        $viewer->givePermissionTo([
            'view_dashboard',
            'view_users',
            'view_vendors',
            'view_all_products',
            'view_all_orders',
            'view_reports',
        ]);

        $this->command->info('========================================');
        $this->command->info('Admin roles created successfully!');
        $this->command->info('========================================');
        $this->command->info('Roles created:');
        $this->command->info('  - super_admin (Full Access)');
        $this->command->info('  - admin_manager (Manage Admins & Settings)');
        $this->command->info('  - vendor_manager (Manage Vendors)');
        $this->command->info('  - product_manager (Manage Products)');
        $this->command->info('  - order_manager (Manage Orders)');
        $this->command->info('  - support_agent (Customer Support)');
        $this->command->info('  - accountant (Financial Reports)');
        $this->command->info('  - viewer (Read-Only Access)');
        $this->command->info('========================================');
    }
}
