<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewAdminSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $provider;
    protected Skill $skill;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a customer
        $this->customer = User::create([
            'name' => 'Rahim Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'is_approved' => true,
        ]);

        // Create a provider
        $this->provider = User::create([
            'name' => 'Karim Provider',
            'email' => 'provider@test.com',
            'password' => bcrypt('password'),
            'role' => 'provider',
            'is_approved' => true,
        ]);

        // Create profile
        $this->provider->providerProfile()->create([
            'bio' => 'Professional technician.',
            'hourly_rate' => 400.00,
            'experience_years' => 5,
        ]);

        // Create a skill
        $this->skill = Skill::create([
            'name' => 'Electrician',
            'icon' => 'bolt',
            'description' => 'Electrical work',
        ]);
        $this->provider->skills()->sync([$this->skill->id]);

        // Create an admin
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_approved' => true,
        ]);
    }

    /** @test */
    public function customer_can_submit_review_for_completed_booking()
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => date('Y-m-d'),
            'start_time' => '10:00:00',
            'duration' => 2,
            'problem_description' => 'Fan repair',
            'total_price' => 800.00,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->customer)->post(route('customer.bookings.review', $booking->id), [
            'rating' => 5,
            'comment' => 'Exceptional service!',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(1, Review::count());

        $review = Review::first();
        $this->assertEquals(5, $review->rating);
        $this->assertEquals('Exceptional service!', $review->comment);
        $this->assertEquals($booking->id, $review->booking_id);

        // Average rating accessor check
        $this->provider->refresh();
        $this->assertEquals(5.0, $this->provider->average_rating);
        $this->assertEquals(1, $this->provider->review_count);
    }

    /** @test */
    public function customer_cannot_submit_duplicate_review_for_same_booking()
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => date('Y-m-d'),
            'start_time' => '10:00:00',
            'duration' => 2,
            'problem_description' => 'Fan repair',
            'total_price' => 800.00,
            'status' => 'completed',
        ]);

        Review::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_id' => $booking->id,
            'rating' => 4,
            'comment' => 'First review',
        ]);

        // Attempting to post another review should fail
        $response = $this->actingAs($this->customer)->post(route('customer.bookings.review', $booking->id), [
            'rating' => 5,
            'comment' => 'Second review',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, Review::count());
    }

    /** @test */
    public function provider_can_reply_to_review()
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => date('Y-m-d'),
            'start_time' => '10:00:00',
            'duration' => 2,
            'problem_description' => 'Fan repair',
            'total_price' => 800.00,
            'status' => 'completed',
        ]);

        $review = Review::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_id' => $booking->id,
            'rating' => 5,
            'comment' => 'Excellent provider!',
        ]);

        $response = $this->actingAs($this->provider)->post(route('provider.reviews.reply', $review->id), [
            'reply' => 'Thank you so much Rahim!',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('Thank you so much Rahim!', $review->fresh()->reply);
    }

    /** @test */
    public function admin_dashboard_includes_all_users_and_bookings()
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => date('Y-m-d'),
            'start_time' => '10:00:00',
            'duration' => 2,
            'problem_description' => 'Fan repair',
            'total_price' => 800.00,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('all_users');
        $response->assertViewHas('all_bookings');
    }

    /** @test */
    public function customer_can_view_and_update_profile()
    {
        $response = $this->actingAs($this->customer)->get(route('customer.profile.edit'));
        $response->assertStatus(200);

        $updateResponse = $this->actingAs($this->customer)->put(route('customer.profile.update'), [
            'name' => 'New Customer Name',
            'email' => 'customer_new@test.com',
            'phone' => '01700000000',
            'city' => 'Rajshahi',
        ]);

        $updateResponse->assertSessionHasNoErrors();
        $this->customer->refresh();
        $this->assertEquals('New Customer Name', $this->customer->name);
        $this->assertEquals('customer_new@test.com', $this->customer->email);
        $this->assertEquals('01700000000', $this->customer->phone);
        $this->assertEquals('Rajshahi', $this->customer->city);
    }

    /** @test */
    public function admin_can_view_and_update_profile()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.profile.edit'));
        $response->assertStatus(200);

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.profile.update'), [
            'name' => 'New Admin Name',
            'email' => 'admin_new@test.com',
            'phone' => '01800000000',
            'city' => 'Barisal',
        ]);

        $updateResponse->assertSessionHasNoErrors();
        $this->admin->refresh();
        $this->assertEquals('New Admin Name', $this->admin->name);
        $this->assertEquals('admin_new@test.com', $this->admin->email);
        $this->assertEquals('01800000000', $this->admin->phone);
        $this->assertEquals('Barisal', $this->admin->city);
    }
}
