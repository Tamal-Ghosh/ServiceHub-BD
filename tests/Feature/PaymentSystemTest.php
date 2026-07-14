<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Skill;
use App\Models\Withdrawal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class PaymentSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $provider;
    protected Skill $skill;

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

        // Create availability for Tuesday (let's say today/booking is on Tuesday)
        $this->provider->availabilities()->create([
            'day_of_week' => 'Tuesday',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'is_available' => true,
        ]);

        // Create a skill
        $this->skill = Skill::create([
            'name' => 'Electrician',
            'icon' => 'bolt',
            'description' => 'Electrical work',
        ]);
        $this->provider->skills()->sync([$this->skill->id]);
    }

    /** @test */
    public function booking_creation_initializes_as_unpaid_and_redirects_to_checkout()
    {
        $response = $this->actingAs($this->customer)->post(route('customer.bookings.store'), [
            'provider_id' => $this->provider->id,
            'booking_date' => date('Y-m-d', strtotime('next Tuesday')),
            'start_time' => '10:00',
            'duration' => 2,
            'problem_description' => 'Fan repair needed.',
        ]);

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertEquals('pending_payment', $booking->status);
        $this->assertEquals(800.00, $booking->total_price);

        $response->assertRedirect(route('payment.show', $booking->id));
    }

    /** @test */
    public function provider_earnings_and_withdrawable_balance_updates_on_completed_jobs()
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => date('Y-m-d'),
            'start_time' => '10:00:00',
            'duration' => 2,
            'problem_description' => 'Fan repair',
            'total_price' => 1000.00,
            'status' => 'pending_payment',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'customer_id' => $this->customer->id,
            'transaction_id' => 'TRXABC123',
            'amount' => 1000.00,
            'platform_charge' => 150.00,
            'provider_earning' => 850.00,
            'status' => 'completed',
        ]);

        // Prior to completion, withdrawable balance should be 0 (job not completed yet)
        $this->assertEquals(0.00, $this->provider->provider_withdrawable_balance);

        // Complete the booking
        $booking->update(['status' => 'completed']);

        // Now withdrawable balance should be credited
        $this->provider->refresh();
        $this->assertEquals(850.00, $this->provider->provider_withdrawable_balance);
        $this->assertEquals(850.00, $this->provider->provider_total_earnings);
    }

    /** @test */
    public function provider_cannot_withdraw_more_than_withdrawable_balance()
    {
        // Set up provider with 500 BDT withdrawable balance
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => date('Y-m-d'),
            'start_time' => '10:00:00',
            'duration' => 2,
            'problem_description' => 'Fan repair',
            'total_price' => 1000.00,
            'status' => 'completed',
        ]);
        Payment::create([
            'booking_id' => $booking->id,
            'customer_id' => $this->customer->id,
            'transaction_id' => 'TRXABC123',
            'amount' => 1000.00,
            'platform_charge' => 150.00,
            'provider_earning' => 850.00,
            'status' => 'completed',
        ]);

        // Attempting to withdraw 1000 BDT should fail
        $response = $this->actingAs($this->provider)->post(route('provider.withdrawals.store'), [
            'amount' => 1000.00,
            'payment_method' => 'bKash',
            'account_number' => '01711223344',
        ]);

        $response->assertSessionHasErrors('amount');
        $this->assertEquals(0, Withdrawal::count());
    }

    /** @test */
    public function provider_can_request_withdrawal_within_balance_limit()
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => date('Y-m-d'),
            'start_time' => '10:00:00',
            'duration' => 2,
            'problem_description' => 'Fan repair',
            'total_price' => 1000.00,
            'status' => 'completed',
        ]);
        Payment::create([
            'booking_id' => $booking->id,
            'customer_id' => $this->customer->id,
            'transaction_id' => 'TRXABC123',
            'amount' => 1000.00,
            'platform_charge' => 150.00,
            'provider_earning' => 850.00,
            'status' => 'completed',
        ]);

        // Request 500 BDT withdrawal
        $response = $this->actingAs($this->provider)->post(route('provider.withdrawals.store'), [
            'amount' => 500.00,
            'payment_method' => 'bKash',
            'account_number' => '01711223344',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(1, Withdrawal::count());

        $withdrawal = Withdrawal::first();
        $this->assertEquals('pending', $withdrawal->status);
        $this->assertEquals(500.00, $withdrawal->amount);

        // Withdrawable balance should temporarily deduct pending withdrawals to prevent double requests
        $this->provider->refresh();
        $this->assertEquals(350.00, $this->provider->provider_withdrawable_balance);
        $this->assertEquals(500.00, $this->provider->provider_pending_withdrawal);
    }

    /** @test */
    public function admin_can_approve_or_reject_withdrawal_request()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_approved' => true,
        ]);

        $withdrawal = Withdrawal::create([
            'provider_id' => $this->provider->id,
            'amount' => 500.00,
            'payment_method' => 'bKash',
            'account_number' => '01711223344',
            'status' => 'pending',
        ]);

        // Admin approves withdrawal
        $response = $this->actingAs($admin)->post(route('admin.withdrawals.status', $withdrawal->id), [
            'status' => 'approved',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $withdrawal->refresh();
        $this->assertEquals('approved', $withdrawal->status);

        // Provider withdrawal metrics verify
        $this->provider->refresh();
        $this->assertEquals(500.00, $this->provider->provider_total_withdrawn);
        $this->assertEquals(0.00, $this->provider->provider_pending_withdrawal);
    }

    /** @test */
    public function customer_can_initiate_sslcommerz_payment()
    {
        Http::fake([
            'sandbox.sslcommerz.com/*' => Http::response([
                'status' => 'SUCCESS',
                'GatewayPageURL' => 'https://sandbox.sslcommerz.com/gwprocess/v4/pay.php?id=123',
            ], 200)
        ]);

        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => date('Y-m-d', strtotime('next Tuesday')),
            'start_time' => '10:00:00',
            'duration' => 2,
            'problem_description' => 'Fan repair',
            'total_price' => 800.00,
            'status' => 'pending_payment',
        ]);

        $response = $this->actingAs($this->customer)->post(route('payment.sslcommerz.initiate', $booking->id));
        
        $response->assertRedirect();
        $this->assertStringContainsString('sandbox.sslcommerz.com/gwprocess/v4/pay.php', $response->headers->get('Location'));
    }

    /** @test */
    public function customer_payment_is_completed_on_sslcommerz_success()
    {
        $booking = Booking::create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'booking_date' => date('Y-m-d', strtotime('next Tuesday')),
            'start_time' => '10:00:00',
            'duration' => 2,
            'problem_description' => 'Fan repair',
            'total_price' => 800.00,
            'status' => 'pending_payment',
        ]);

        $tranId = 'SSLC_' . $booking->id . '_1234567890';

        $response = $this->post(route('payment.sslcommerz.success'), [
            'status' => 'VALID',
            'tran_id' => $tranId,
            'amount' => 800.00,
        ]);

        $response->assertRedirect(route('customer.bookings.index'));
        
        $booking->refresh();
        $this->assertEquals('pending', $booking->status);

        $payment = Payment::where('booking_id', $booking->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals('completed', $payment->status);
        $this->assertEquals($tranId, $payment->transaction_id);
    }
}
