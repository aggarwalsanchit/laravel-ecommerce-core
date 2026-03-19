<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        Permission::create([
            'name' => 'manage users',
            'guard_name' => 'admin'
        ]);

        Permission::create([
            'name' => 'manage products',
            'guard_name' => 'admin'
        ]);

        // Create role
        $role = Role::create([
            'name' => 'super_admin',
            'guard_name' => 'admin'
        ]);

        // Assign permissions
        $role->givePermissionTo(['manage users', 'manage products']);

        // Create admin
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assign role
        $admin->assignRole($role);
    }
}
