<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '1234567890',
            'role' => 'admin',
        ]);

        // Create 5 doctor users
        User::factory()->count(5)->doctor()->create();

        // Create 10 patient users
        // User::factory()->count(10)->patient()->create();

        // Create 20 general users with random roles
        User::factory()->count(20)->create();
    }
}
