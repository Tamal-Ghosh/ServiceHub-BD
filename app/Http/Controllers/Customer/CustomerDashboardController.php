<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    /**
     * Show the customer dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total' => $user->bookings()->count(),
            'active' => $user->bookings()->whereIn('status', ['pending', 'accepted', 'in_progress'])->count(),
            'completed' => $user->bookings()->where('status', 'completed')->count(),
            'reviews' => $user->givenReviews()->count(),
        ];

        $recentBookings = $user->bookings()
            ->with(['provider', 'provider.providerProfile'])
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('stats', 'recentBookings'));
    }
}
