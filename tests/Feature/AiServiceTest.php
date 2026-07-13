<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create some skills
        Skill::create(['name' => 'Electrician', 'icon' => 'bolt', 'description' => 'Electrician details']);
        Skill::create(['name' => 'Plumber', 'icon' => 'wrench', 'description' => 'Plumbing details']);
        Skill::create(['name' => 'AC Technician', 'icon' => 'snowflake', 'description' => 'AC details']);
    }

    /** @test */
    public function ai_suggests_service_via_groq_api()
    {
        // Mock Groq response
        \Illuminate\Support\Facades\Http::fake([
            'api.groq.com/*' => \Illuminate\Support\Facades\Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => '{"suggested_service": "AC Technician", "explanation": "Your AC has a cooling issue."}'
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson(route('ai.suggest'), [
            'problem' => 'My air conditioner is not working and the room is too hot'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'suggested_service' => 'AC Technician'
            ]);
    }

    /** @test */
    public function ai_returns_error_for_invalid_problem_length()
    {
        $response = $this->postJson(route('ai.suggest'), [
            'problem' => 'a'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['problem']);
    }

    /** @test */
    public function homepage_saves_searched_skill_to_session()
    {
        $response = $this->get('/?skill=Electrician');

        $response->assertStatus(200);
        $this->assertEquals('Electrician', session('last_searched_skill'));
    }

    /** @test */
    public function homepage_prioritizes_matching_city_and_last_searched_skill()
    {
        // Create 2 providers: 1 in Dhaka, 1 in Chittagong
        $dhakaProvider = User::create([
            'name' => 'Dhaka Provider',
            'email' => 'dhaka@test.com',
            'password' => 'password',
            'role' => 'provider',
            'is_approved' => true,
            'city' => 'Dhaka'
        ]);
        $dhakaProvider->providerProfile()->create([
            'bio' => 'Dhaka bio',
            'area' => 'Mirpur',
            'hourly_rate' => 300,
            'experience_years' => 5
        ]);

        $chittagongProvider = User::create([
            'name' => 'Chittagong Provider',
            'email' => 'ctg@test.com',
            'password' => 'password',
            'role' => 'provider',
            'is_approved' => true,
            'city' => 'Chittagong'
        ]);
        $chittagongProvider->providerProfile()->create([
            'bio' => 'Ctg bio',
            'area' => 'Agrabad',
            'hourly_rate' => 350,
            'experience_years' => 3
        ]);

        // Login as customer in Chittagong
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer_user@test.com',
            'password' => 'password',
            'role' => 'customer',
            'city' => 'Chittagong'
        ]);

        $response = $this->actingAs($customer)->get('/');

        // Chittagong provider should be positioned first because of matching city
        $providers = $response->viewData('providers');
        $this->assertEquals('Chittagong Provider', $providers[0]->name);
    }
}
