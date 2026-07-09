<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a new review for a completed booking.
     */
    public function store(Request $request, Booking $booking)
    {
        // Guard checks
        if ($booking->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status !== 'completed') {
            return back()->with('error', 'You can only review completed service bookings.');
        }

        // Prevent duplicate reviews
        if ($booking->review()->exists()) {
            return back()->with('error', 'You have already reviewed this booking.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|min:3|max:1000',
        ], [
            'rating.required' => 'Please select a rating between 1 and 5 stars.',
            'comment.min' => 'Review comment must be at least 3 characters.',
        ]);

        // Create Review
        Review::create([
            'provider_id' => $booking->provider_id,
            'customer_id' => auth()->id(),
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Thank you for your feedback! Your review has been published.');
    }
}
