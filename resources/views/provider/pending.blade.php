@extends('layouts.guest')

@section('title', 'Pending Approval')

@section('content')
<div class="w-full max-w-md animate-fade-up text-center">
    <div class="backdrop-blur-xl bg-white/[0.05] border border-white/[0.08] rounded-3xl p-10 shadow-2xl shadow-black/20">

        {{-- Hourglass Icon --}}
        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-500/20 to-amber-600/20 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-white mb-3">Account Pending Approval</h2>
        <p class="text-slate-400 leading-relaxed">
            Your provider account is currently being reviewed by our admin team. You will receive full access once your account has been approved.
        </p>

        <div class="mt-8 space-y-3">
            <a href="{{ route('provider.profile.edit') }}" class="block w-full py-3 px-6 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-medium shadow-lg shadow-indigo-500/25 transition-all duration-200">
                Complete/Edit Profile
            </a>
            <a href="{{ route('provider.dashboard') }}" class="block w-full py-3 px-6 rounded-xl bg-white/[0.06] border border-white/[0.1] text-slate-300 font-medium hover:bg-white/[0.1] transition-all duration-200">
                Go to Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-3 px-6 rounded-xl text-slate-500 hover:text-rose-400 font-medium transition-all duration-200">
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
