// database/seeders/VendorPermissionSeeder.php

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class VendorPermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Delete existing
        Role::where('guard_name', 'vendor')->delete();
        Permission::where('guard_name', 'vendor')->delete();
        
        // ========== CREATE PERMISSIONS ==========
        $allPermissions = [
            // Basic permissions (for pending vendors)
            'view_dashboard',
            'complete_profile',
            'change_password',
            
            // Full permissions (for approved vendors)
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'view_orders',
            'update_order_status',
            'cancel_orders',
            'view_reports',
            'view_analytics',
            'manage_staff',
            'manage_store_settings',
            'view_activity_logs',
        ];
        
        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'vendor']);
        }
        
        // ========== CREATE ROLES ==========
        
        // Role 1: Vendor (for pending/unapproved vendors) - Limited permissions
        $vendorRole = Role::create(['name' => 'vendor', 'guard_name' => 'vendor']);
        $vendorRole->givePermissionTo([
            'view_dashboard',
            'complete_profile',
            'change_password',
        ]);
        
        // Role 2: Store Owner (for approved vendors) - Full permissions
        $storeOwnerRole = Role::create(['name' => 'store_owner', 'guard_name' => 'vendor']);
        $storeOwnerRole->givePermissionTo($allPermissions);
        
        $this->command->info('========================================');
        $this->command->info('Vendor permissions created!');
        $this->command->info('========================================');
        $this->command->info('Roles created:');
        $this->command->info('  - vendor (limited permissions for pending)');
        $this->command->info('  - store_owner (full permissions after approval)');
        $this->command->info('========================================');
    }
}