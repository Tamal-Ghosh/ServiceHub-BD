@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="w-full max-w-lg animate-fade-up">

    {{-- Logo & Brand --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/25 mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold bg-gradient-to-r from-white to-indigo-200 bg-clip-text text-transparent">ServiceHub BD</h1>
        <p class="text-slate-400 mt-2">Find trusted local service providers</p>
    </div>

    {{-- Glassmorphism Card --}}
    <div class="backdrop-blur-xl bg-white/[0.05] border border-white/[0.08] rounded-3xl p-8 shadow-2xl shadow-black/20">

        <h2 class="text-xl font-semibold text-white mb-6">Create your account</h2>

        {{-- Session status --}}
        @if (session('status'))
            <div class="mb-4 p-3 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            {{-- Role Selection Cards --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-3">I want to join as</label>
                <div class="grid grid-cols-2 gap-3">
                    {{-- Customer Card --}}
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="role" value="customer" class="peer sr-only" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                        <div class="p-4 rounded-2xl border-2 border-white/[0.08] bg-white/[0.03] transition-all duration-300 peer-checked:border-indigo-500/60 peer-checked:bg-indigo-500/10 peer-checked:shadow-lg peer-checked:shadow-indigo-500/10 group-hover:border-white/20 group-hover:bg-white/[0.05]">
                            <div class="flex flex-col items-center text-center gap-2">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center transition-colors peer-checked:bg-blue-500/20">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-300">Customer</span>
                                <span class="text-xs text-slate-500">Book services</span>
                            </div>
                        </div>
                    </label>

                    {{-- Provider Card --}}
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="role" value="provider" class="peer sr-only" {{ old('role') === 'provider' ? 'checked' : '' }}>
                        <div class="p-4 rounded-2xl border-2 border-white/[0.08] bg-white/[0.03] transition-all duration-300 peer-checked:border-purple-500/60 peer-checked:bg-purple-500/10 peer-checked:shadow-lg peer-checked:shadow-purple-500/10 group-hover:border-white/20 group-hover:bg-white/[0.05]">
                            <div class="flex flex-col items-center text-center gap-2">
                                <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-300">Provider</span>
                                <span class="text-xs text-slate-500">Offer services</span>
                            </div>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Full Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                    class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300"
                    placeholder="Enter your full name">
                @error('name')
                    <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300"
                    placeholder="you@example.com">
                @error('email')
                    <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone & City in a row --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-300 mb-1.5">Phone</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300"
                        placeholder="01XXXXXXXXX">
                    @error('phone')
                        <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-slate-300 mb-1.5">City</label>
                    <select name="city" id="city"
                        class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300 appearance-none"
                        style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1.25em 1.25em;">
                        <option value="" class="bg-slate-900">Select city</option>
                        <option value="Dhaka" class="bg-slate-900" {{ old('city') === 'Dhaka' ? 'selected' : '' }}>Dhaka</option>
                        <option value="Chittagong" class="bg-slate-900" {{ old('city') === 'Chittagong' ? 'selected' : '' }}>Chittagong</option>
                        <option value="Rajshahi" class="bg-slate-900" {{ old('city') === 'Rajshahi' ? 'selected' : '' }}>Rajshahi</option>
                        <option value="Khulna" class="bg-slate-900" {{ old('city') === 'Khulna' ? 'selected' : '' }}>Khulna</option>
                        <option value="Sylhet" class="bg-slate-900" {{ old('city') === 'Sylhet' ? 'selected' : '' }}>Sylhet</option>
                        <option value="Barisal" class="bg-slate-900" {{ old('city') === 'Barisal' ? 'selected' : '' }}>Barisal</option>
                        <option value="Rangpur" class="bg-slate-900" {{ old('city') === 'Rangpur' ? 'selected' : '' }}>Rangpur</option>
                        <option value="Mymensingh" class="bg-slate-900" {{ old('city') === 'Mymensingh' ? 'selected' : '' }}>Mymensingh</option>
                        <option value="Comilla" class="bg-slate-900" {{ old('city') === 'Comilla' ? 'selected' : '' }}>Comilla</option>
                        <option value="Gazipur" class="bg-slate-900" {{ old('city') === 'Gazipur' ? 'selected' : '' }}>Gazipur</option>
                    </select>
                    @error('city')
                        <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Services Offered (Provider Only) --}}
            <div id="skills-section" class="hidden space-y-3">
                <label class="block text-sm font-medium text-slate-300">Services Offered (Select all that apply)</label>
                <div class="grid grid-cols-2 gap-3 p-4 rounded-2xl bg-white/[0.03] border border-white/[0.08]">
                    @foreach($skills as $skill)
                        <label class="flex items-center gap-3 cursor-pointer group py-1.5">
                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}" 
                                class="rounded border-white/[0.1] bg-white/[0.05] text-indigo-600 focus:ring-indigo-500/50 focus:ring-offset-0 transition-all"
                                {{ is_array(old('skills')) && in_array($skill->id, old('skills')) ? 'checked' : '' }}>
                            <span class="text-sm text-slate-300 group-hover:text-white transition-colors">{{ $skill->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('skills')
                    <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300 pr-12"
                        placeholder="Min 8 characters">
                    <button type="button" onclick="togglePassword('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-300 transition-colors">
                        <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300"
                    placeholder="Re-enter password">
            </div>

            {{-- Submit --}}
            <button type="submit" id="register-btn"
                class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0">
                Create Account
            </button>
        </form>

        {{-- Login link --}}
        <p class="text-center text-slate-400 text-sm mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors">Sign in</a>
        </p>
    </div>
</div>

<script>
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const roleInputs = document.querySelectorAll('input[name="role"]');
        const skillsSection = document.getElementById('skills-section');
        const skillCheckboxes = skillsSection.querySelectorAll('input[type="checkbox"]');

        function toggleSkillsVisibility() {
            const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
            if (selectedRole === 'provider') {
                skillsSection.classList.remove('hidden');
            } else {
                skillsSection.classList.add('hidden');
                // Clear selected skills if customer
                skillCheckboxes.forEach(cb => cb.checked = false);
            }
        }

        roleInputs.forEach(input => {
            input.addEventListener('change', toggleSkillsVisibility);
        });

        // Initialize on load
        toggleSkillsVisibility();
    });
</script>
@endsection
