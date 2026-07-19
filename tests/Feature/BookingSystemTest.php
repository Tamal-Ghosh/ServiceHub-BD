<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Skill;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $provider;
    protected Skill $skill;

    protected function setUp(): void
    {
        parent::setUp();

        $this->skill = Skill::create(['name' => 'Electrician', 'icon' => 'bolt']);

        // Create provider
        $this->provider = User::create([
            'name' => 'Babul Electrician',
            'email' => 'babul@test.com',
            'role' => 'provider',
            'is_approved' => true,
            'city' => 'Dhaka',
            'password' => bcrypt('password'),
        ]);

        $this->provider->providerProfile()->create([
            'bio' => 'Experienced electrician.',
            'area' => 'Mirpur',
            'hourly_rate' => 300,
        ]);

        $this->provider->skills()->sync([$this->skill->id]);

        // Register availability: Monday, 09:00 to 17:00
        $this->provider->availabilities()->create([
            'day_of_week' => 'Monday',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'is_available' => true,
        ]);

        // Create customer
        $this->customer = User::create([
            'name' => 'Rahim Customer',
            'email' => 'rahim@test.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_customer_can_view_booking_form(): void
    {
        $response = $this->actingAs($this->customer)
            ->get(route('customer.bookings.create', $this->provider->id));

        $response->assertStatus(200);
        $response->assertSee('Book Babul Electrician');
        $response->assertSee('Rate: ৳300/hr');
    }

    public function test_customer_can_create_booking_successfully(): void
    {
        $monday = date('Y-m-d', strtotime('next Monday'));
        // Book on Monday from 10:00 for 2 hours
        $response = $this->actingAs($this->customer)
            ->post(route('customer.bookings.store'), [
                'provider_id' => $this->provider->id,
                'booking_date' => $monday,
                'start_time' => '10:00',
                'duration' => 2,
                'problem_description' => 'My fan is not working. Needs inspection.',
            ]);

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $response->assertRedirect(route('payment.show', $booking->id));

        // Price is 300 * 2 = 600
        $this->assertDatabaseHas('bookings', [
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => $monday,
            'start_time' => '10:00',
            'duration' => 2,
            'total_price' => 600,
            'status' => 'pending_payment',
        ]);
    }

    public function test_booking_fails_if_outside_availability(): void
    {
        $monday = date('Y-m-d', strtotime('next Monday'));
        // Monday, but try booking at 18:00 (ends at 19:00, outside 09:00-17:00)
        $response = $this->actingAs($this->customer)
            ->from(route('customer.bookings.create', $this->provider->id))
            ->post(route('customer.bookings.store'), [
                'provider_id' => $this->provider->id,
                'booking_date' => $monday,
                'start_time' => '18:00',
                'duration' => 1,
                'problem_description' => 'Fix short circuit problem.',
            ]);

        $response->assertRedirect(route('customer.bookings.create', $this->provider->id));
        $response->assertSessionHasErrors('start_time');
    }

    public function test_booking_fails_on_unsupported_weekday(): void
    {
        $tuesday = date('Y-m-d', strtotime('next Tuesday'));
        // Provider has no availability on Tuesdays
        $response = $this->actingAs($this->customer)
            ->from(route('customer.bookings.create', $this->provider->id))
            ->post(route('customer.bookings.store'), [
                'provider_id' => $this->provider->id,
                'booking_date' => $tuesday,
                'start_time' => '10:00',
                'duration' => 1,
                'problem_description' => 'Fix light wiring issues.',
            ]);

        $response->assertRedirect(route('customer.bookings.create', $this->provider->id));
        $response->assertSessionHasErrors('booking_date');
    }

    public function test_booking_fails_if_double_booked(): void
    {
        $monday = date('Y-m-d', strtotime('next Monday'));
        // Create an existing booking
        Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => $monday,
            'start_time' => '10:00',
            'duration' => 2, // 10:00 to 12:00
            'problem_description' => 'First issue details.',
            'total_price' => 600,
            'status' => 'accepted',
        ]);

        // Attempting booking that overlaps (e.g. 11:00 to 12:00)
        $response = $this->actingAs($this->customer)
            ->from(route('customer.bookings.create', $this->provider->id))
            ->post(route('customer.bookings.store'), [
                'provider_id' => $this->provider->id,
                'booking_date' => $monday,
                'start_time' => '11:00',
                'duration' => 1,
                'problem_description' => 'Second issue details.',
            ]);

        $response->assertRedirect(route('customer.bookings.create', $this->provider->id));
        $response->assertSessionHasErrors('start_time');
    }

    public function test_provider_can_accept_booking(): void
    {
        $monday = date('Y-m-d', strtotime('next Monday'));
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => $monday,
            'start_time' => '10:00',
            'duration' => 2,
            'problem_description' => 'AC servicing required.',
            'total_price' => 600,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->provider)
            ->post(route('provider.bookings.status', $booking->id), [
                'status' => 'accepted',
            ]);

        $response->assertStatus(302);
        $this->assertEquals('accepted', $booking->fresh()->status);
    }

    public function test_provider_can_reject_booking(): void
    {
        $monday = date('Y-m-d', strtotime('next Monday'));
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => $monday,
            'start_time' => '10:00',
            'duration' => 2,
            'problem_description' => 'AC servicing required.',
            'total_price' => 600,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->provider)
            ->post(route('provider.bookings.status', $booking->id), [
                'status' => 'rejected',
            ]);

        $response->assertStatus(302);
        $this->assertEquals('rejected', $booking->fresh()->status);
    }

    public function test_provider_can_transition_to_in_progress_and_completed(): void
    {
        $monday = date('Y-m-d', strtotime('next Monday'));
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => $monday,
            'start_time' => '10:00',
            'duration' => 2,
            'problem_description' => 'Fix socket switches.',
            'total_price' => 600,
            'status' => 'accepted',
        ]);

        // Mark in progress
        $response = $this->actingAs($this->provider)
            ->post(route('provider.bookings.status', $booking->id), [
                'status' => 'in_progress',
            ]);

        $response->assertStatus(302);
        $this->assertEquals('in_progress', $booking->fresh()->status);

        // Mark completed
        $response = $this->actingAs($this->provider)
            ->post(route('provider.bookings.status', $booking->id), [
                'status' => 'completed',
            ]);

        $response->assertStatus(302);
        $this->assertEquals('completed', $booking->fresh()->status);
    }

    public function test_customer_can_cancel_booking_with_reason(): void
    {
        $monday = date('Y-m-d', strtotime('next Monday'));
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => $monday,
            'start_time' => '10:00',
            'duration' => 2,
            'problem_description' => 'Generator repair request.',
            'total_price' => 600,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->customer)
            ->post(route('customer.bookings.cancel', $booking->id), [
                'cancellation_reason' => 'I resolved the issue myself.',
            ]);

        $response->assertStatus(302);
        $this->assertEquals('cancelled', $booking->fresh()->status);
        $this->assertEquals('I resolved the issue myself.', $booking->fresh()->cancellation_reason);
    }
}
