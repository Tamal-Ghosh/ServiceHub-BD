<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        $skills = \App\Models\Skill::orderBy('name')->get();
        return view('auth.register', compact('skills'));
    }

    /**
     * Handle registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:customer,provider',
            'skills' => 'required_if:role,provider|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'password' => $request->password,
            'role' => $request->role,
            'is_approved' => $request->role === 'customer', // Customers auto-approved
        ]);

        if ($user->role === 'provider') {
            $user->providerProfile()->create();
            if ($request->has('skills')) {
                $user->skills()->sync($request->skills);
            }
        }

        Auth::login($user);

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Redirect user based on their role.
     */
    private function redirectBasedOnRole($user)
    {
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
