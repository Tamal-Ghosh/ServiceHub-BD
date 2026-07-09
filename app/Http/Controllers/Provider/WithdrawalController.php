<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    /**
     * Store a new withdrawal request.
     */
    public function store(Request $request)
    {
        $provider = auth()->user();

        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|string|in:bKash,Rocket,Nagad,Bank',
            'account_number' => 'required|string|min:11|max:20',
        ], [
            'amount.min' => 'Minimum withdrawal amount is ৳100.',
            'payment_method.in' => 'Please select a valid payment method.',
        ]);

        // Check balance
        if ($request->amount > $provider->provider_withdrawable_balance) {
            return back()->withErrors([
                'amount' => 'Insufficient balance. Your withdrawable balance is ৳' . number_format($provider->provider_withdrawable_balance)
            ])->withInput();
        }

        // Create request
        Withdrawal::create([
            'provider_id' => $provider->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'account_number' => $request->account_number,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your withdrawal request of ৳' . number_format($request->amount) . ' has been submitted and is pending admin approval.');
    }
}
