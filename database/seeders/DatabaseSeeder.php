<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed skills
        $this->call(SkillSeeder::class);

        // Create default admin
        User::updateOrCreate(
            ['email' => 'admin@servicehub.com'],
            [
                'name' => 'Admin User',
                'password' => 'password',
                'role' => 'admin',
                'is_approved' => true,
                'city' => 'Dhaka',
            ]
        );
    }
}
