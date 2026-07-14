<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

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
     * Initiate payment using the SSLCommerz API and redirect the user.
     */
    public function sslcommerzInitiate(Booking $booking)
    {
        // Guard checks
        if ($booking->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'pending_payment') {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'This booking does not require payment.');
        }

        $storeId = env('SSLCOMMERZ_STORE_ID', 'testbox');
        $storePassword = env('SSLCOMMERZ_STORE_PASSWORD', 'project_testbox');
        $isSandbox = env('SSLCOMMERZ_IS_SANDBOX', true);

        $apiUrl = $isSandbox 
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php' 
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';

        $transactionId = 'SSLC_' . $booking->id . '_' . strtoupper(Str::random(10));
        $customer = auth()->user();

        // Build Payload
        $postData = [
            'store_id' => $storeId,
            'store_passwd' => $storePassword,
            'total_amount' => $booking->total_price,
            'currency' => 'BDT',
            'tran_id' => $transactionId,
            'success_url' => route('payment.sslcommerz.success'),
            'fail_url' => route('payment.sslcommerz.fail'),
            'cancel_url' => route('payment.sslcommerz.cancel'),
            'cus_name' => $customer->name,
            'cus_email' => $customer->email,
            'cus_phone' => $customer->phone ?? '01700000000',
            'cus_add1' => $customer->address ?? 'Dhaka',
            'cus_city' => $customer->city ?? 'Dhaka',
            'cus_country' => 'Bangladesh',
            'shipping_method' => 'NO',
            'num_of_item' => 1,
            'product_name' => 'Service Booking #' . $booking->id,
            'product_category' => 'Service',
            'product_profile' => 'general',
        ];

        try {
            $response = Http::asForm()->post($apiUrl, $postData);
            
            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['status']) && $result['status'] === 'SUCCESS' && isset($result['GatewayPageURL'])) {
                    return redirect()->away($result['GatewayPageURL']);
                }
                
                return redirect()->route('customer.bookings.index')
                    ->with('error', 'SSLCommerz payment session initiation failed: ' . ($result['failedreason'] ?? 'Unknown Error'));
            }

            return redirect()->route('customer.bookings.index')
                ->with('error', 'Could not connect to SSLCommerz API.');
        } catch (\Exception $e) {
            // Fallback for offline local dev/testing environments if network request fails
            if (app()->environment('local', 'testing')) {
                // Generate simulated direct success mock redirect
                $simulatedSuccessUrl = route('payment.sslcommerz.success') . '?' . http_build_query([
                    'status' => 'VALID',
                    'tran_id' => $transactionId,
                    'amount' => $booking->total_price,
                ]);
                return redirect()->away($simulatedSuccessUrl);
            }
            
            return redirect()->route('customer.bookings.index')
                ->with('error', 'An error occurred while initiating payment: ' . $e->getMessage());
        }
    }

    /**
     * Handle SSLCommerz payment success callback.
     */
    public function sslcommerzSuccess(Request $request)
    {
        $tranId = $request->input('tran_id');
        $status = $request->input('status');

        if (!$tranId || !in_array($status, ['VALID', 'VALIDATED'])) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Invalid payment verification.');
        }

        // Parse booking ID out of tran_id
        $parts = explode('_', $tranId);
        $bookingId = $parts[1] ?? null;

        if (!$bookingId) {
            return redirect()->route('customer.bookings.index')
                ->with('error', 'Transaction mapping failed.');
        }

        $booking = Booking::findOrFail($bookingId);

        if ($booking->status !== 'pending_payment') {
            return redirect()->route('customer.bookings.index')
                ->with('info', 'This payment is already processed.');
        }

        // Calculations (15% platform fee, 85% provider earning)
        $amount = $booking->total_price;
        $platformCharge = $amount * 0.15;
        $providerEarning = $amount * 0.85;

        // Create Payment record
        Payment::create([
            'booking_id' => $booking->id,
            'customer_id' => $booking->customer_id,
            'transaction_id' => $tranId,
            'amount' => $amount,
            'platform_charge' => $platformCharge,
            'provider_earning' => $providerEarning,
            'status' => 'completed',
        ]);

        // Activate Booking
        $booking->update([
            'status' => 'pending',
        ]);

        // Auto login the customer if session was lost during redirect
        if (!auth()->check()) {
            auth()->loginUsingId($booking->customer_id);
        }

        return redirect()->route('customer.bookings.index')
            ->with('success', "Payment of ৳" . number_format($amount) . " successful via SSLCommerz! Transaction ID: {$tranId}. Booking is now pending provider approval.");
    }

    /**
     * Handle SSLCommerz payment fail callback.
     */
    public function sslcommerzFail(Request $request)
    {
        $tranId = $request->input('tran_id');
        $parts = explode('_', $tranId);
        $bookingId = $parts[1] ?? null;

        if ($bookingId) {
            $booking = Booking::find($bookingId);
            if ($booking && !auth()->check()) {
                auth()->loginUsingId($booking->customer_id);
            }
        }

        return redirect()->route('customer.bookings.index')
            ->with('error', 'SSLCommerz payment failed. Please try again.');
    }

    /**
     * Handle SSLCommerz payment cancel callback.
     */
    public function sslcommerzCancel(Request $request)
    {
        $tranId = $request->input('tran_id');
        $parts = explode('_', $tranId);
        $bookingId = $parts[1] ?? null;

        if ($bookingId) {
            $booking = Booking::find($bookingId);
            if ($booking && !auth()->check()) {
                auth()->loginUsingId($booking->customer_id);
            }
        }

        return redirect()->route('customer.bookings.index')
            ->with('info', 'SSLCommerz payment was canceled.');
    }
}
