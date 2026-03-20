<?php
// database/seeders/RoleTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    public function run(): void
    {
        // Get all permissions
        $allPermissions = Permission::all();

        // 1. SUPER ADMIN - Has all permissions
        $superAdmin = Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $superAdmin->givePermissionTo($allPermissions);
        $this->command->info('✓ Super Admin role created with all permissions');

        // 2. ADMIN - Has most permissions except system configuration
        $admin = Role::create(['name' => 'Admin', 'guard_name' => 'admin']);
        // $adminPermissions = Permission::whereNotIn('name', [
        //     'configure system',
        //     'impersonate users',
        //     'delete permissions',
        // ])->get();
        $admin->givePermissionTo($allPermissions);
        $this->command->info('✓ Admin role created');

        // 3. MANAGER - Can manage users and content
        $manager = Role::create(['name' => 'Manager', 'guard_name' => 'admin']);
        // $managerPermissions = Permission::whereIn('name', [
        //     'view users',
        //     'create users',
        //     'edit users',
        //     'activate users',
        //     'deactivate users',
        //     'view roles',
        //     'view posts',
        //     'create posts',
        //     'edit posts',
        //     'delete posts',
        //     'publish posts',
        //     'view reports',
        //     'export reports',
        //     'view dashboard',
        //     'view analytics',
        //     'edit own profile',
        //     'change own password',
        //     'upload avatar',
        // ])->get();
        $manager->givePermissionTo($allPermissions);
        $this->command->info('✓ Manager role created');

        // 4. EDITOR - Can manage content only
        $editor = Role::create(['name' => 'Editor', 'guard_name' => 'admin']);
        // $editorPermissions = Permission::whereIn('name', [
        //     'view posts',
        //     'create posts',
        //     'edit posts',
        //     'delete posts',
        //     'view dashboard',
        //     'edit own profile',
        //     'change own password',
        //     'upload avatar',
        // ])->get();
        $editor->givePermissionTo($allPermissions);
        $this->command->info('✓ Editor role created');

        // 5. VIEWER - Can only view content
        $viewer = Role::create(['name' => 'Viewer', 'guard_name' => 'admin']);
        // $viewerPermissions = Permission::whereIn('name', [
        //     'view posts',
        //     'view dashboard',
        //     'edit own profile',
        //     'change own password',
        //     'upload avatar',
        // ])->get();
        $viewer->givePermissionTo($allPermissions);
        $this->command->info('✓ Viewer role created');

        $this->command->info('✅ All roles created and permissions assigned successfully!');
    }
}
