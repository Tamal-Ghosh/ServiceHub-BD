<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $total_users = User::count();
        $total_providers = User::where('role', 'provider')->count();
        $total_customers = User::where('role', 'customer')->count();
        $pending_providers = User::where('role', 'provider')->where('is_approved', false)->get();
        $recent_users = User::latest()->take(10)->get();
        
        $pending_withdrawals = \App\Models\Withdrawal::where('status', 'pending')->with('provider')->latest()->get();
        $recent_withdrawals = \App\Models\Withdrawal::whereIn('status', ['approved', 'rejected'])->with('provider')->latest()->take(10)->get();

        $all_bookings = \App\Models\Booking::with(['customer', 'provider', 'payment'])->latest()->get();
        $all_users = \App\Models\User::latest()->get();

        return view('admin.dashboard', compact(
            'total_users',
            'total_providers',
            'total_customers',
            'pending_providers',
            'recent_users',
            'pending_withdrawals',
            'recent_withdrawals',
            'all_bookings',
            'all_users'
        ));
    }

    /**
     * Approve a pending provider.
     */
    public function approve(User $user)
    {
        if ($user->role !== 'provider') {
            return back()->with('error', 'Only provider accounts can be approved.');
        }

        $user->update(['is_approved' => true]);

        return redirect()->route('admin.dashboard')->with('success', "Provider {$user->name} has been approved successfully!");
    }

    /**
     * Approve or reject a withdrawal request.
     */
    public function updateWithdrawalStatus(Request $request, \App\Models\Withdrawal $withdrawal)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'This withdrawal request has already been processed.');
        }

        $withdrawal->update([
            'status' => $request->status,
        ]);

        $statusLabel = $request->status === 'approved' ? 'approved' : 'rejected';
        return redirect()->route('admin.dashboard')
            ->with('success', "Withdrawal request of ৳" . number_format($withdrawal->amount) . " has been successfully {$statusLabel}!");
    }
}
