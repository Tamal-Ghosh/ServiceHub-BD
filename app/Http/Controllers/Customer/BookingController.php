<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Show the booking form for a specific provider.
     */
    public function create(User $provider)
    {
        // Ensure user is actually a provider and has a profile
        if (!$provider->isProvider() || !$provider->providerProfile) {
            abort(404, 'Provider profile not found.');
        }

        return view('booking.create', compact('provider'));
    }

    /**
     * Store a new booking request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|exists:users,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1|max:12',
            'problem_description' => 'required|string|min:10',
        ]);

        $provider = User::findOrFail($request->provider_id);
        
        if (!$provider->isProvider() || !$provider->providerProfile) {
            return back()->withErrors(['provider_id' => 'Invalid service provider.'])->withInput();
        }

        $bookingDate = Carbon::parse($request->booking_date);
        $dayOfWeek = $bookingDate->format('l');

        // 1. Conflict Detection: Check Provider's Availability Slot
        $availability = $provider->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return back()->withErrors(['booking_date' => "The provider is not available on {$dayOfWeek}s."])->withInput();
        }

        $reqStart = Carbon::parse($request->start_time);
        $reqEnd = (clone $reqStart)->addHours((int) $request->duration);

        // Normalize time strings to check range correctly
        $availStart = Carbon::parse($availability->start_time);
        $availEnd = Carbon::parse($availability->end_time);

        // Standardize time comparison by ignoring dates
        $reqStartToday = Carbon::today()->setTime($reqStart->hour, $reqStart->minute);
        $reqEndToday = Carbon::today()->setTime($reqEnd->hour, $reqEnd->minute);
        $availStartToday = Carbon::today()->setTime($availStart->hour, $availStart->minute);
        $availEndToday = Carbon::today()->setTime($availEnd->hour, $availEnd->minute);

        if ($reqStartToday < $availStartToday || $reqEndToday > $availEndToday) {
            $formattedStart = $availStart->format('g:i A');
            $formattedEnd = $availEnd->format('g:i A');
            return back()->withErrors([
                'start_time' => "The requested time slot is outside the provider's available hours on {$dayOfWeek}s ({$formattedStart} - {$formattedEnd})."
            ])->withInput();
        }

        // 2. Conflict Detection: Check Overlapping/Double Bookings
        $existingBookings = Booking::where('provider_id', $provider->id)
            ->where('booking_date', $request->booking_date)
            ->whereIn('status', ['pending', 'accepted', 'in_progress'])
            ->get();

        foreach ($existingBookings as $exBooking) {
            $exStart = Carbon::parse($exBooking->start_time);
            $exEnd = (clone $exStart)->addHours((int) $exBooking->duration);

            $exStartToday = Carbon::today()->setTime($exStart->hour, $exStart->minute);
            $exEndToday = Carbon::today()->setTime($exEnd->hour, $exEnd->minute);

            // Check overlap: Start time A < End time B AND End time A > Start time B
            if ($reqStartToday < $exEndToday && $reqEndToday > $exStartToday) {
                return back()->withErrors([
                    'start_time' => "The provider is already booked during this time slot ({$exStart->format('g:i A')} - {$exEnd->format('g:i A')})."
                ])->withInput();
            }
        }

        // Calculate auto price
        $totalPrice = $provider->providerProfile->hourly_rate * $request->duration;

        // Create booking
        $booking = Booking::create([
            'customer_id' => auth()->id(),
            'provider_id' => $provider->id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'duration' => $request->duration,
            'problem_description' => $request->problem_description,
            'total_price' => $totalPrice,
            'status' => 'pending_payment',
        ]);

        return redirect()->route('payment.show', $booking->id)
            ->with('info', 'Please complete bKash payment to submit your booking request.');
    }

    /**
     * List all customer bookings.
     */
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with(['provider.providerProfile', 'provider.skills'])
            ->latest()
            ->get();

        return view('customer.bookings.index', compact('bookings'));
    }

    /**
     * Cancel an active booking request.
     */
    public function cancel(Request $request, Booking $booking)
    {
        // Guard check
        if ($booking->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($booking->status, ['pending', 'accepted'])) {
            return back()->with('error', 'Only pending or accepted bookings can be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|min:5|max:1000',
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return back()->with('success', 'Booking has been cancelled successfully.');
    }
}
