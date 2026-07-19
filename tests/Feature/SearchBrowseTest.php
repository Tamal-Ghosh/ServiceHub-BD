<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Skill;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchBrowseTest extends TestCase
{
    use RefreshDatabase;

    protected User $provider1;
    protected User $provider2;
    protected Skill $electrician;
    protected Skill $plumber;

    protected function setUp(): void
    {
        parent::setUp();

        $this->electrician = Skill::create(['name' => 'Electrician', 'icon' => 'bolt']);
        $this->plumber = Skill::create(['name' => 'Plumber', 'icon' => 'wrench']);

        // Create provider 1
        $this->provider1 = User::create([
            'name' => 'Babul Electrician',
            'email' => 'babul@test.com',
            'role' => 'provider',
            'is_approved' => true,
            'city' => 'Dhaka',
            'password' => bcrypt('password'),
        ]);
        $this->provider1->providerProfile()->create([
            'bio' => 'Electrician bio',
            'area' => 'Mirpur',
            'hourly_rate' => 300,
        ]);
        $this->provider1->skills()->sync([$this->electrician->id]);

        // Create provider 2
        $this->provider2 = User::create([
            'name' => 'Abul Plumber',
            'email' => 'abul@test.com',
            'role' => 'provider',
            'is_approved' => true,
            'city' => 'Chittagong',
            'password' => bcrypt('password'),
        ]);
        $this->provider2->providerProfile()->create([
            'bio' => 'Plumber bio',
            'area' => 'Halishahar',
            'hourly_rate' => 250,
        ]);
        $this->provider2->skills()->sync([$this->plumber->id]);

        // Create customer for reviews
        $customer = User::create([
            'name' => 'Rahim Customer',
            'email' => 'rahim@test.com',
            'role' => 'customer',
            'is_approved' => true,
            'password' => bcrypt('password'),
        ]);

        // Create reviews
        // Provider 1 gets a 5 star review
        Review::create([
            'provider_id' => $this->provider1->id,
            'customer_id' => $customer->id,
            'rating' => 5,
            'comment' => 'Excellent electrical work!',
        ]);

        // Provider 2 gets a 3 star review
        Review::create([
            'provider_id' => $this->provider2->id,
            'customer_id' => $customer->id,
            'rating' => 3,
            'comment' => 'Average plumbing service.',
        ]);
    }

    public function test_homepage_lists_approved_providers(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Babul Electrician');
        $response->assertSee('Abul Plumber');
    }

    public function test_filter_by_skill(): void
    {
        $response = $this->get('/?skill=' . $this->electrician->id);

        $response->assertStatus(200);
        $response->assertSee('Babul Electrician');
        $response->assertDontSee('Abul Plumber');
    }

    public function test_filter_by_city(): void
    {
        $response = $this->get('/?city=Chittagong');

        $response->assertStatus(200);
        $response->assertSee('Abul Plumber');
        $response->assertDontSee('Babul Electrician');
    }

    public function test_filter_by_rating(): void
    {
        // Filter for 4+ rating (Only Babul should show, since Abul is 3.0)
        $response = $this->get('/?rating=4');

        $response->assertStatus(200);
        $response->assertSee('Babul Electrician');
        $response->assertDontSee('Abul Plumber');
    }

    public function test_provider_detail_page_shows_reviews(): void
    {
        $response = $this->get(route('provider.profile.show', $this->provider1->id));

        $response->assertStatus(200);
        $response->assertSee('Babul Electrician');
        $response->assertSee('Excellent electrical work!');
        $response->assertSee('Rahim Customer');
    }

    public function test_provider_registration_with_skills(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Provider',
            'email' => 'newprovider@test.com',
            'phone' => '01712345678',
            'city' => 'Dhaka',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'provider',
            'skills' => [$this->electrician->id, $this->plumber->id],
        ]);

        $response->assertRedirect(route('login'));

        // Assert user exists and is not approved
        $user = User::where('email', 'newprovider@test.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('provider', $user->role);
        $this->assertFalse($user->is_approved);

        // Assert profile was initialized
        $this->assertNotNull($user->providerProfile);

        // Assert skills were synced
        $this->assertCount(2, $user->skills);
        $this->assertTrue($user->skills->contains($this->electrician->id));
        $this->assertTrue($user->skills->contains($this->plumber->id));
    }
}
