<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $providers = User::where('role', 'provider')->get();

        if ($customers->isEmpty() || $providers->isEmpty()) {
            return;
        }

        $customer1 = $customers->first();
        $customer2 = $customers->skip(1)->first() ?? $customer1;
        $customer3 = $customers->skip(2)->first() ?? $customer1;

        $reviewData = [
            // Karim Electrician (provider@test.com)
            'provider@test.com' => [
                [
                    'customer' => $customer1,
                    'rating' => 5,
                    'comment' => 'Excellent work, resolved my short circuit issue quickly! Very polite and professional.',
                ],
                [
                    'customer' => $customer2,
                    'rating' => 4,
                    'comment' => 'Punctual and did a great job fixing the ceiling fan. Highly recommended.',
                ],
            ],
            // Abul Plumber (abul@test.com)
            'abul@test.com' => [
                [
                    'customer' => $customer3,
                    'rating' => 4,
                    'comment' => 'Good plumbing service. Fixed the pipe leak in the kitchen quickly.',
                ],
                [
                    'customer' => $customer2,
                    'rating' => 4,
                    'comment' => 'Decent work, but arrived 15 minutes late.',
                ],
            ],
            // Kamal AC Tech (kamal@test.com)
            'kamal@test.com' => [
                [
                    'customer' => $customer3,
                    'rating' => 5,
                    'comment' => 'Highly professional! Installed my split AC perfectly and cleaned up after work.',
                ],
                [
                    'customer' => $customer1,
                    'rating' => 5,
                    'comment' => 'Super fast servicing. My AC is cooling like new now. Value for money.',
                ],
            ],
            // Sadek Tutor (sadek@test.com)
            'sadek@test.com' => [
                [
                    'customer' => $customer2,
                    'rating' => 5,
                    'comment' => 'Great tutor. Extremely patient and clear in explanations. Helped my son with math.',
                ],
            ],
            // Jamil Painter (jamil@test.com)
            'jamil@test.com' => [
                [
                    'customer' => $customer3,
                    'rating' => 3,
                    'comment' => 'Painting was okay, but left some paint drops on the floor.',
                ],
                [
                    'customer' => $customer1,
                    'rating' => 4,
                    'comment' => 'Satisfactory work and finished exactly on time. Good behavior.',
                ],
            ],
        ];

        foreach ($reviewData as $email => $reviews) {
            $provider = User::where('email', $email)->first();
            if ($provider) {
                foreach ($reviews as $rev) {
                    Review::create([
                        'provider_id' => $provider->id,
                        'customer_id' => $rev['customer']->id,
                        'rating' => $rev['rating'],
                        'comment' => $rev['comment'],
                    ]);
                }
            }
        }
    }
}
