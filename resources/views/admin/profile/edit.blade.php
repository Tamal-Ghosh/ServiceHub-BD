@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white">System Profile</h2>
            <p class="text-sm text-slate-400">View and update your administrator credentials</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-white/[0.05] hover:bg-white/[0.08] border border-white/5 text-slate-300 hover:text-white text-xs font-semibold transition-all">
            Back to Admin Panel
        </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Edit Form --}}
    <form method="POST" action="{{ route('admin.profile.update') }}" class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6 md:p-8 space-y-6 shadow-xl">
        @csrf
        @method('PUT')

        {{-- Basic Information Section --}}
        <div>
            <h3 class="text-base font-bold text-white border-b border-white/5 pb-2 mb-4">Admin Account Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Administrator Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    @error('name')
                        <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    @error('email')
                        <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required
                        class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    @error('phone')
                        <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- City --}}
                <div>
                    <label for="city" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}" required
                        class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    @error('city')
                        <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Change Password Section --}}
        <div>
            <h3 class="text-base font-bold text-white border-b border-white/5 pb-2 mb-2 mt-4">Change Password</h3>
            <p class="text-xs text-slate-500 mb-4">Leave fields empty if you do not wish to update your password</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">New Password</label>
                    <input type="password" name="password" id="password" placeholder="Minimum 6 characters" minlength="6"
                        class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    @error('password')
                        <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-type new password" minlength="6"
                        class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 border-t border-white/5 pt-4">
            <button type="submit" class="px-5 py-3 rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 text-xs font-bold shadow-lg shadow-indigo-600/10 transition-all hover:-translate-y-0.5">
                Save Admin Profile
            </button>
        </div>
    </form>
</div>
@endsection
