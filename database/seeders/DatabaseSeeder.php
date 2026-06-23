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
        // Create default admin
        User::updateOrCreate(
            ['email' => 'admin@servicehub.com'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'role' => 'admin',
                'is_approved' => true,
                'city' => 'Dhaka',
            ]
        );

        // Create a test customer
        User::updateOrCreate(
            ['email' => 'customer@test.com'],
            [
                'name' => 'Rahim Ahmed',
                'password' => 'password',
                'role' => 'customer',
                'is_approved' => true,
                'phone' => '01712345678',
                'city' => 'Dhaka',
            ]
        );

        // Create a test provider
        User::updateOrCreate(
            ['email' => 'provider@test.com'],
            [
                'name' => 'Karim Electrician',
                'password' => 'password',
                'role' => 'provider',
                'is_approved' => true,
                'phone' => '01812345678',
                'city' => 'Dhaka',
            ]
        );
    }
}
