<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $skills = \App\Models\Skill::orderBy('name')->get();
        return view('auth.register', compact('skills'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:customer,provider'],
            'skills' => ['required_if:role,provider', 'array'],
            'skills.*' => ['exists:skills,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_approved' => $request->role === 'customer', // Customers auto-approved
        ]);

        if ($user->role === 'provider') {
            $user->providerProfile()->create();
            if ($request->has('skills')) {
                $user->skills()->sync($request->skills);
            }
        }

        event(new Registered($user));

        Auth::login($user);

        if ($user->isProvider() && !$user->is_approved) {
            return redirect()->route('provider.pending');
        }

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'provider' => redirect()->route('provider.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    }
}
