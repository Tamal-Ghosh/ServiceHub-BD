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

        return view('admin.dashboard', compact(
            'total_users',
            'total_providers',
            'total_customers',
            'pending_providers',
            'recent_users'
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
}
