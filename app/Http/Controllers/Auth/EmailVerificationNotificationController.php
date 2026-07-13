<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $redirectRoute = match ($user->role) {
            'admin' => 'admin.dashboard',
            'provider' => 'provider.dashboard',
            default => 'customer.dashboard',
        };

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route($redirectRoute, absolute: false));
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
