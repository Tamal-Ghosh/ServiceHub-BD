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
        return view('provider.dashboard');
    }
}
