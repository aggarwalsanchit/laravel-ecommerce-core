<?php
// database/factories/AdminFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Admin;

class AdminFactory extends Factory
{
    protected $model = \App\Models\Admin::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Default password: 'password'
            'remember_token' => Str::random(10),
            'is_active' => fake()->boolean(90), // 90% chance of being active
            'phone' => fake()->phoneNumber(),
            'avatar' => null,
        ];
    }

    /**
     * Indicate that the admin is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the email is unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Admin $admin) {
            // Any logic after making
        })->afterCreating(function (Admin $admin) {
            // Any logic after creating
        });
    }
}
