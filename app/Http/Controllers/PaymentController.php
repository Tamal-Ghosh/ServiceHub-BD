<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show the bKash payment checkout page.
     */
    public function show(Booking $booking)
    {
        // Guard checks
        if ($booking->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'pending_payment') {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'This booking does not require payment.');
        }

        return view('payment.checkout', compact('booking'));
    }

    /**
     * Process the mock bKash payment.
     */
    public function process(Request $request, Booking $booking)
    {
        // Guard checks
        if ($booking->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'pending_payment') {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'This booking does not require payment.');
        }

        $request->validate([
            'wallet_number' => 'required|string|regex:/^01[3-9]\d{8}$/',
            'pin' => 'required|string|digits:4',
        ], [
            'wallet_number.regex' => 'Please enter a valid 11-digit bKash wallet number (starts with 013-019).',
            'pin.digits' => 'PIN must be exactly 4 digits.',
        ]);

        // Generate Transaction ID
        $transactionId = 'TRX' . strtoupper(Str::random(8));

        // Calculations (15% platform fee, 85% provider earning)
        $amount = $booking->total_price;
        $platformCharge = $amount * 0.15;
        $providerEarning = $amount * 0.85;

        // Create Payment record
        Payment::create([
            'booking_id' => $booking->id,
            'customer_id' => auth()->id(),
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'platform_charge' => $platformCharge,
            'provider_earning' => $providerEarning,
            'status' => 'completed',
        ]);

        // Activate Booking (set to pending approval by provider)
        $booking->update([
            'status' => 'pending',
        ]);

        return redirect()->route('customer.bookings.index')
            ->with('success', "Payment of ৳" . number_format($amount) . " successful! Transaction ID: {$transactionId}. Booking is now pending provider approval.");
    }
}
