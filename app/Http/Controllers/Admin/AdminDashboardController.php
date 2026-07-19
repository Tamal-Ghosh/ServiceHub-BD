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

    /**
     * Show edit form for a user.
     */
    public function editUser(User $user)
    {
        $skills = \App\Models\Skill::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'skills'));
    }

    /**
     * Update user information.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'role' => 'required|in:customer,provider,admin',
            'is_approved' => 'required|boolean',
            'skills' => 'required_if:role,provider|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'role' => $request->role,
            'is_approved' => ($request->role === 'customer' || $request->role === 'admin') ? true : $request->is_approved,
        ]);

        if ($user->role === 'provider') {
            if (!$user->providerProfile) {
                $user->providerProfile()->create();
            }
            if ($request->has('skills')) {
                $user->skills()->sync($request->skills);
            } else {
                $user->skills()->detach();
            }
        }

        return redirect()->route('admin.dashboard', ['tab' => 'users'])->with('success', "User {$user->name} has been updated successfully!");
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }

        $user->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'users'])->with('success', "User has been deleted successfully!");
    }
}
