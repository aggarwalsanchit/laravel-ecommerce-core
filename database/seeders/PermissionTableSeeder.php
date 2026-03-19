<?php
// database/seeders/PermissionTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionTableSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // User Management Permissions
        $userPermissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'activate users',
            'deactivate users',
            'impersonate users',
        ];

        // Role Management Permissions
        $rolePermissions = [
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'assign roles',
        ];

        // Permission Management Permissions
        $permissionPermissions = [
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'assign permissions',
        ];

        // Content Management Permissions
        $productPermissions = [
            'view products',
            'create products',
            'edit products',
            'delete products',
            'publish products',
            'archive products',
        ];

        // Report Permissions
        $orderPermissions = [
            'view orders',
            'export orders',
            'print orders',
        ];

        // Settings Permissions
        $categoryPermissions = [
            'view categories',
            'edit categories',
            'delete categories',
        ];

        // Dashboard Permissions
        $dashboardPermissions = [
            'view dashboard',
            'view analytics',
            'manage widgets',
        ];

        // Profile Permissions
        $profilePermissions = [
            'edit own profile',
            'change own password',
            'upload avatar',
        ];

        // Merge all permissions
        $allPermissions = array_merge(
            $userPermissions,
            $rolePermissions,
            $permissionPermissions,
            $productPermissions,
            $orderPermissions,
            $categoryPermissions,
            $dashboardPermissions,
            $profilePermissions
        );

        // Create permissions
        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

        $this->command->info('✅ All permissions created successfully!');
    }
}
