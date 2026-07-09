<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * List all bookings for the provider.
     */
    public function index()
    {
        $provider = auth()->user();

        $incomingBookings = $provider->providerBookings()
            ->where('status', 'pending')
            ->with('customer')
            ->latest()
            ->get();

        $activeBookings = $provider->providerBookings()
            ->whereIn('status', ['accepted', 'in_progress'])
            ->with('customer')
            ->latest()
            ->get();

        $pastBookings = $provider->providerBookings()
            ->whereIn('status', ['completed', 'rejected', 'cancelled'])
            ->with('customer')
            ->latest()
            ->get();

        return view('provider.bookings.index', compact('incomingBookings', 'activeBookings', 'pastBookings'));
    }

    /**
     * Update the status of a booking.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        // Guard check
        if ($booking->provider_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected,in_progress,completed',
        ]);

        $newStatus = $request->status;
        $currentStatus = $booking->status;

        // Transition validations
        if ($newStatus === 'accepted' || $newStatus === 'rejected') {
            if ($currentStatus !== 'pending') {
                return back()->with('error', 'Only pending bookings can be accepted or rejected.');
            }
        } elseif ($newStatus === 'in_progress') {
            if ($currentStatus !== 'accepted') {
                return back()->with('error', 'Only accepted bookings can be marked as in progress.');
            }
        } elseif ($newStatus === 'completed') {
            if ($currentStatus !== 'in_progress') {
                return back()->with('error', 'Only in-progress bookings can be marked as completed.');
            }
        }

        $booking->update([
            'status' => $newStatus,
        ]);

        $message = "Booking status updated to " . str_replace('_', ' ', $newStatus) . " successfully.";
        return back()->with('success', $message);
    }
}
