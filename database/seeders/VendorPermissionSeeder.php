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

        // ========== CREATE ALL PERMISSIONS ==========
        $permissions = [
            // Limited permissions (for pending vendors)
            'view_dashboard',
            'view_profile',
            'update_profile',
            'change_password',
            'upload_avatar',
            'complete_profile',

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
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'vendor']);
        }

        // Create store_owner role (gets ALL permissions)
        $storeOwner = Role::create(['name' => 'store_owner', 'guard_name' => 'vendor']);

        // Initially assign ONLY limited permissions
        $storeOwner->givePermissionTo([
            'view_dashboard',
            'view_profile',
            'update_profile',
            'change_password',
            'upload_avatar',
            'complete_profile',
        ]);

        $this->command->info('========================================');
        $this->command->info('Store owner role created with limited permissions!');
        $this->command->info('Admin will grant full permissions after approval');
        $this->command->info('========================================');
    }
}
