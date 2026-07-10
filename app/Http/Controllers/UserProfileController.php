<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     * Show the profile edit form based on user role.
     */
    public function edit()
    {
        $user = Auth::user();

        if ($user->role === 'provider') {
            return redirect()->route('provider.profile.edit');
        }

        if ($user->role === 'admin') {
            return view('admin.profile.edit', compact('user'));
        }

        return view('customer.profile.edit', compact('user'));
    }

    /**
     * Update user profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Please provide your full name.',
            'phone.required' => 'Please provide a contact phone number.',
            'city.required' => 'Please enter your city.',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return back()->with('success', 'Your profile details have been successfully updated.');
    }
}
