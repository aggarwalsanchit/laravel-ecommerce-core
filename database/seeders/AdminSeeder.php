<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin user
        $superAdmin = Admin::create([
            'name' => 'Super Administrator',
            'email' => 'super@admin.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '+1234567890',
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('Super Admin');
        $this->command->info('✓ Super Admin user created');

        // Create Admin user
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '+1234567891',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Admin');
        $this->command->info('✓ Admin user created');

        // Create Manager user
        $manager = Admin::create([
            'name' => 'Manager User',
            'email' => 'manager@admin.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '+1234567892',
            'email_verified_at' => now(),
        ]);
        $manager->assignRole('Manager');
        $this->command->info('✓ Manager user created');

        // Create Editor user
        $editor = Admin::create([
            'name' => 'Editor User',
            'email' => 'editor@admin.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '+1234567893',
            'email_verified_at' => now(),
        ]);
        $editor->assignRole('Editor');
        $this->command->info('✓ Editor user created');

        // Create Viewer user
        $viewer = Admin::create([
            'name' => 'Viewer User',
            'email' => 'viewer@admin.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'phone' => '+1234567894',
            'email_verified_at' => now(),
        ]);
        $viewer->assignRole('Viewer');
        $this->command->info('✓ Viewer user created');

        // Create 20 random users with Viewer role
        Admin::factory(20)->create()->each(function ($user) {
            $user->assignRole('Viewer');
        });
        $this->command->info('✓ 20 random viewer users created');

        $this->command->info('✅ All users created successfully!');
    }
}
