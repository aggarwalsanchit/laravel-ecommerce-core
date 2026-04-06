<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting database seeding...');
        $this->command->line('------------------------');

        $this->call(PermissionTableSeeder::class);
        $this->command->line('------------------------');

        $this->call(RoleTableSeeder::class);
        $this->command->line('------------------------');

        $this->call(AdminSeeder::class);
        $this->command->line('------------------------');

        $this->call(CountryStateCitySeeder::class);

        $this->command->info('🎉 All seeding completed successfully!');
    }
}
