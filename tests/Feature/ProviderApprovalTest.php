<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_provider_registration_redirects_to_pending_approval(): void
    {
        $skill = \App\Models\Skill::create(['name' => 'Electrician', 'icon' => 'bolt']);

        $response = $this->post('/register', [
            'name' => 'John Provider',
            'email' => 'john@example.com',
            'phone' => '01711223344',
            'city' => 'Dhaka',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'provider',
            'skills' => [$skill->id],
        ]);

        $response->assertRedirect(route('provider.pending'));

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->is_approved);
    }

    public function test_unapproved_provider_can_login_and_access_profile_edit(): void
    {
        $provider = User::create([
            'name' => 'John Provider',
            'email' => 'john@example.com',
            'phone' => '01711223344',
            'city' => 'Dhaka',
            'password' => bcrypt('password123'),
            'role' => 'provider',
            'is_approved' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('provider.pending'));
        $this->assertAuthenticatedAs($provider);

        // Verify unapproved provider can access profile edit page
        $editResponse = $this->actingAs($provider)->get(route('provider.profile.edit'));
        $editResponse->assertStatus(200);
    }

    public function test_admin_can_approve_provider(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'is_approved' => true,
        ]);

        $provider = User::create([
            'name' => 'John Provider',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'role' => 'provider',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.providers.approve', $provider->id));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue($provider->fresh()->is_approved);
    }

    public function test_unapproved_provider_cannot_access_dashboard(): void
    {
        $provider = User::create([
            'name' => 'John Provider',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'role' => 'provider',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($provider)->get(route('provider.dashboard'));

        $response->assertRedirect(route('provider.pending'));
    }
}
