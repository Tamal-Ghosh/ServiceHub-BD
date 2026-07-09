<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProviderDashboardController extends Controller
{
    /**
     * Show the provider dashboard.
     */
    public function index()
    {
        if (!auth()->user()->is_approved) {
            return redirect()->route('provider.pending');
        }
        
        $withdrawals = auth()->user()->withdrawals()->latest()->get();

        return view('provider.dashboard', compact('withdrawals'));
    }
}
