<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Skill;
use App\Models\Booking;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed skills
        $this->call(SkillSeeder::class);

        // 2. Create default admin
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

        // 3. Create Customers
        $customersData = [
            [
                'email' => 'customer@test.com',
                'name' => 'Rahim Ahmed',
                'password' => 'password',
                'role' => 'customer',
                'is_approved' => true,
                'phone' => '01712345678',
                'city' => 'Dhaka',
            ],
            [
                'email' => 'customer2@test.com',
                'name' => 'Fahim Chowdhury',
                'password' => 'password',
                'role' => 'customer',
                'is_approved' => true,
                'phone' => '01512345678',
                'city' => 'Chittagong',
            ],
            [
                'email' => 'customer3@test.com',
                'name' => 'Nila Islam',
                'password' => 'password',
                'role' => 'customer',
                'is_approved' => true,
                'phone' => '01912345678',
                'city' => 'Dhaka',
            ],
        ];

        $customers = [];
        foreach ($customersData as $c) {
            $customers[] = User::updateOrCreate(['email' => $c['email']], $c);
        }

        // Retrieve Skills
        $electrician = Skill::where('name', 'Electrician')->first();
        $plumber = Skill::where('name', 'Plumber')->first();
        $acTech = Skill::where('name', 'AC Technician')->first();
        $cleaner = Skill::where('name', 'Cleaner')->first();
        $tutor = Skill::where('name', 'Tutor')->first();
        $itSupport = Skill::where('name', 'IT Support')->first();
        $painter = Skill::where('name', 'Painter')->first();
        $carpenter = Skill::where('name', 'Carpenter')->first();
        $appliance = Skill::where('name', 'Appliance Repair')->first();

        // 4. Create Providers
        $providersData = [
            [
                'user' => [
                    'email' => 'provider@test.com',
                    'name' => 'Karim Electrician',
                    'password' => 'password',
                    'role' => 'provider',
                    'is_approved' => true,
                    'phone' => '01812345678',
                    'city' => 'Dhaka',
                ],
                'profile' => [
                    'bio' => 'Professional electrician with 5+ years of experience in home wiring, appliance installation, and troubleshooting short circuits.',
                    'area' => 'Mirpur',
                    'hourly_rate' => 350,
                    'experience_years' => 5,
                ],
                'skills' => [$electrician->id, $acTech->id],
                'availabilities' => [
                    ['day_of_week' => 'Saturday', 'start_time' => '09:00', 'end_time' => '17:00'],
                    ['day_of_week' => 'Sunday', 'start_time' => '09:00', 'end_time' => '17:00'],
                    ['day_of_week' => 'Monday', 'start_time' => '09:00', 'end_time' => '17:00'],
                    ['day_of_week' => 'Wednesday', 'start_time' => '09:00', 'end_time' => '17:00'],
                ],
            ],
            [
                'user' => [
                    'email' => 'abul@test.com',
                    'name' => 'Abul Plumber',
                    'password' => 'password',
                    'role' => 'provider',
                    'is_approved' => true,
                    'phone' => '01711122233',
                    'city' => 'Dhaka',
                ],
                'profile' => [
                    'bio' => 'Expert plumbing services including pipe repairs, leak fixing, washroom fitting, and drainage cleaning.',
                    'area' => 'Dhanmondi',
                    'hourly_rate' => 300,
                    'experience_years' => 3,
                ],
                'skills' => [$plumber->id, $cleaner->id],
                'availabilities' => [
                    ['day_of_week' => 'Saturday', 'start_time' => '10:00', 'end_time' => '19:00'],
                    ['day_of_week' => 'Monday', 'start_time' => '10:00', 'end_time' => '19:00'],
                    ['day_of_week' => 'Wednesday', 'start_time' => '10:00', 'end_time' => '19:00'],
                ],
            ],
            [
                'user' => [
                    'email' => 'kamal@test.com',
                    'name' => 'Kamal AC Tech',
                    'password' => 'password',
                    'role' => 'provider',
                    'is_approved' => true,
                    'phone' => '01611122233',
                    'city' => 'Chittagong',
                ],
                'profile' => [
                    'bio' => 'Specialized in split and window AC servicing, gas refilling, leak detection, installation, and general appliance repair.',
                    'area' => 'Halishahar',
                    'hourly_rate' => 500,
                    'experience_years' => 8,
                ],
                'skills' => [$acTech->id, $appliance->id],
                'availabilities' => [
                    ['day_of_week' => 'Sunday', 'start_time' => '08:00', 'end_time' => '16:00'],
                    ['day_of_week' => 'Monday', 'start_time' => '08:00', 'end_time' => '16:00'],
                    ['day_of_week' => 'Wednesday', 'start_time' => '08:00', 'end_time' => '16:00'],
                ],
            ],
            [
                'user' => [
                    'email' => 'sadek@test.com',
                    'name' => 'Sadek Tutor',
                    'password' => 'password',
                    'role' => 'provider',
                    'is_approved' => true,
                    'phone' => '01911122233',
                    'city' => 'Sylhet',
                ],
                'profile' => [
                    'bio' => 'High school mathematics and physics tutor. Helping students build core concepts and excel in exams.',
                    'area' => 'Zindabazar',
                    'hourly_rate' => 400,
                    'experience_years' => 2,
                ],
                'skills' => [$tutor->id, $itSupport->id],
                'availabilities' => [
                    ['day_of_week' => 'Monday', 'start_time' => '14:00', 'end_time' => '20:00'],
                    ['day_of_week' => 'Wednesday', 'start_time' => '14:00', 'end_time' => '20:00'],
                ],
            ],
            [
                'user' => [
                    'email' => 'jamil@test.com',
                    'name' => 'Jamil Painter',
                    'password' => 'password',
                    'role' => 'provider',
                    'is_approved' => true,
                    'phone' => '01511122233',
                    'city' => 'Dhaka',
                ],
                'profile' => [
                    'bio' => 'Wall painting, interior design painting, and basic wood repair/carpenter services. Neat and clean work guaranteed.',
                    'area' => 'Uttara',
                    'hourly_rate' => 250,
                    'experience_years' => 4,
                ],
                'skills' => [$painter->id, $carpenter->id],
                'availabilities' => [
                    ['day_of_week' => 'Sunday', 'start_time' => '09:00', 'end_time' => '18:00'],
                    ['day_of_week' => 'Tuesday', 'start_time' => '09:00', 'end_time' => '18:00'],
                ],
            ],
            // Unapproved provider
            [
                'user' => [
                    'email' => 'pending@test.com',
                    'name' => 'Pending Repairer',
                    'password' => 'password',
                    'role' => 'provider',
                    'is_approved' => false,
                    'phone' => '01311122233',
                    'city' => 'Dhaka',
                ],
                'profile' => [
                    'bio' => 'Need approval to serve. Can fix doors, windows, locks, etc.',
                    'area' => 'Gulshan',
                    'hourly_rate' => 200,
                    'experience_years' => 1,
                ],
                'skills' => [$carpenter->id],
                'availabilities' => [
                    ['day_of_week' => 'Saturday', 'start_time' => '09:00', 'end_time' => '18:00'],
                ],
            ],
        ];

        $providers = [];
        foreach ($providersData as $p) {
            $user = User::updateOrCreate(['email' => $p['user']['email']], $p['user']);
            $user->providerProfile()->updateOrCreate(['user_id' => $user->id], $p['profile']);
            $user->skills()->sync($p['skills']);
            $user->availabilities()->delete();
            foreach ($p['availabilities'] as $slot) {
                $user->availabilities()->create(array_merge($slot, ['is_available' => true]));
            }
            $providers[$p['user']['email']] = $user;
        }



        // 6. Seed Bookings
        $bookingsData = [
            [
                'customer_email' => 'customer@test.com',
                'provider_email' => 'provider@test.com', // Karim Electrician
                'booking_date' => date('Y-m-d', strtotime('next Monday')),
                'start_time' => '10:00:00',
                'duration' => 2,
                'problem_description' => 'Need my house ceiling fan installed and wiring inspected.',
                'total_price' => 700.00,
                'status' => 'pending',
            ],
            [
                'customer_email' => 'customer@test.com',
                'provider_email' => 'kamal@test.com', // Kamal AC Tech
                'booking_date' => date('Y-m-d', strtotime('next Wednesday')),
                'start_time' => '11:00:00',
                'duration' => 3,
                'problem_description' => 'Split AC gas refilling and basic service check.',
                'total_price' => 1500.00,
                'status' => 'accepted',
            ],
            [
                'customer_email' => 'customer2@test.com',
                'provider_email' => 'provider@test.com', // Karim Electrician
                'booking_date' => date('Y-m-d', strtotime('last Monday')),
                'start_time' => '09:30:00',
                'duration' => 2,
                'problem_description' => 'Socket short circuit replacement.',
                'total_price' => 700.00,
                'status' => 'completed',
            ],
            [
                'customer_email' => 'customer3@test.com',
                'provider_email' => 'jamil@test.com', // Jamil Painter
                'booking_date' => date('Y-m-d', strtotime('last Sunday')),
                'start_time' => '10:00:00',
                'duration' => 4,
                'problem_description' => 'Living room feature wall painting.',
                'total_price' => 1000.00,
                'status' => 'cancelled',
                'cancellation_reason' => 'Postponed painting plan due to home renovation delays.',
            ],
        ];

        foreach ($bookingsData as $b) {
            $customer = User::where('email', $b['customer_email'])->first();
            $provider = User::where('email', $b['provider_email'])->first();

            if ($customer && $provider) {
                Booking::create([
                    'customer_id' => $customer->id,
                    'provider_id' => $provider->id,
                    'booking_date' => $b['booking_date'],
                    'start_time' => $b['start_time'],
                    'duration' => $b['duration'],
                    'problem_description' => $b['problem_description'],
                    'total_price' => $b['total_price'],
                    'status' => $b['status'],
                    'cancellation_reason' => $b['cancellation_reason'] ?? null,
                ]);
            }
        }
    }
}
