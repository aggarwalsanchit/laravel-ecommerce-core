<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // =============================================
        // CREATE ADMIN USERS WITH DIFFERENT ROLES
        // =============================================

        // 1. SUPER ADMIN (Full Control)
        $superAdmin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'avatar' => null,
            'is_active' => true,
            'last_login_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');

        // 2. ADMIN MANAGER (Can manage other admins)
        $adminManager = Admin::create([
            'name' => 'Admin Manager',
            'email' => 'adminmanager@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567891',
            'avatar' => null,
            'is_active' => true,
            'last_login_at' => now(),
        ]);
        $adminManager->assignRole('admin_manager');

        // 3. VENDOR MANAGER (Manages all vendors)
        $vendorManager = Admin::create([
            'name' => 'Vendor Manager',
            'email' => 'vendormanager@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567892',
            'avatar' => null,
            'is_active' => true,
            'last_login_at' => now(),
        ]);
        $vendorManager->assignRole('vendor_manager');

        // 4. PRODUCT MANAGER (Manages products, categories, brands)
        $productManager = Admin::create([
            'name' => 'Product Manager',
            'email' => 'productmanager@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567893',
            'avatar' => null,
            'is_active' => true,
            'last_login_at' => now(),
        ]);
        $productManager->assignRole('product_manager');

        // 5. ORDER MANAGER (Manages all orders)
        $orderManager = Admin::create([
            'name' => 'Order Manager',
            'email' => 'ordermanager@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567894',
            'avatar' => null,
            'is_active' => true,
            'last_login_at' => now(),
        ]);
        $orderManager->assignRole('order_manager');

        // 6. SUPPORT AGENT (Handles customer support)
        $supportAgent = Admin::create([
            'name' => 'Support Agent',
            'email' => 'supportagent@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567895',
            'avatar' => null,
            'is_active' => true,
            'last_login_at' => now(),
        ]);
        $supportAgent->assignRole('support_agent');

        // 7. ACCOUNTANT (Views financial reports)
        $accountant = Admin::create([
            'name' => 'Accountant',
            'email' => 'accountant@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567896',
            'avatar' => null,
            'is_active' => true,
            'last_login_at' => now(),
        ]);
        $accountant->assignRole('accountant');

        // 8. VIEWER (Read-only access)
        $viewer = Admin::create([
            'name' => 'Viewer',
            'email' => 'viewer@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567897',
            'avatar' => null,
            'is_active' => true,
            'last_login_at' => now(),
        ]);
        $viewer->assignRole('viewer');

        // =============================================
        // INACTIVE ADMIN (For testing)
        // =============================================
        $inactiveAdmin = Admin::create([
            'name' => 'Inactive Admin',
            'email' => 'inactive@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567898',
            'avatar' => null,
            'is_active' => false,
            'last_login_at' => null,
        ]);
        $inactiveAdmin->assignRole('viewer');

        $this->command->info('========================================');
        $this->command->info('Admin users created successfully!');
        $this->command->info('========================================');
        $this->command->info('SUPER ADMIN (Full Access):');
        $this->command->info('  Email: superadmin@example.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('ADMIN MANAGER:');
        $this->command->info('  Email: adminmanager@example.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('VENDOR MANAGER:');
        $this->command->info('  Email: vendormanager@example.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('PRODUCT MANAGER:');
        $this->command->info('  Email: productmanager@example.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('ORDER MANAGER:');
        $this->command->info('  Email: ordermanager@example.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('SUPPORT AGENT:');
        $this->command->info('  Email: supportagent@example.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('ACCOUNTANT:');
        $this->command->info('  Email: accountant@example.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('VIEWER (Read-Only):');
        $this->command->info('  Email: viewer@example.com');
        $this->command->info('  Password: password');
        $this->command->info('========================================');
    }
}
