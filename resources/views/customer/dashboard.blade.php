@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Welcome Section --}}
    <div class="animate-fade-up">
        <h2 class="text-2xl font-bold text-white">Welcome back, {{ auth()->user()->name }}! 👋</h2>
        <p class="text-slate-400 mt-1">Find and book trusted service providers in your area</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- Total Bookings --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-500/5" style="animation: fade-up 0.5s ease-out 0.1s both;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-xs text-slate-500">All time</span>
            </div>
            <p class="text-3xl font-bold text-white">0</p>
            <p class="text-sm text-slate-400 mt-1">Total Bookings</p>
        </div>

        {{-- Active Bookings --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/5" style="animation: fade-up 0.5s ease-out 0.2s both;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-emerald-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Live
                </span>
            </div>
            <p class="text-3xl font-bold text-white">0</p>
            <p class="text-sm text-slate-400 mt-1">Active Bookings</p>
        </div>

        {{-- Completed --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-purple-500/5" style="animation: fade-up 0.5s ease-out 0.3s both;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-slate-500">All time</span>
            </div>
            <p class="text-3xl font-bold text-white">0</p>
            <p class="text-sm text-slate-400 mt-1">Completed</p>
        </div>

        {{-- Reviews Given --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/5" style="animation: fade-up 0.5s ease-out 0.4s both;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <span class="text-xs text-slate-500">All time</span>
            </div>
            <p class="text-3xl font-bold text-white">0</p>
            <p class="text-sm text-slate-400 mt-1">Reviews Given</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div>
        <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <a href="{{ route('home') }}" class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6 hover:bg-indigo-500/[0.08] hover:border-indigo-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-4 group-hover:bg-indigo-500/20 transition-colors">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h4 class="font-semibold text-white mb-1">Find a Provider</h4>
                <p class="text-sm text-slate-400">Search electricians, plumbers, tutors & more</p>
            </a>

            <a href="#" class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6 hover:bg-purple-500/[0.08] hover:border-purple-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center mb-4 group-hover:bg-purple-500/20 transition-colors">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <h4 class="font-semibold text-white mb-1">View My Bookings</h4>
                <p class="text-sm text-slate-400">Track your active and past bookings</p>
            </a>

            <a href="{{ route('home') }}" class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6 hover:bg-emerald-500/[0.08] hover:border-emerald-500/20 transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-4 group-hover:bg-emerald-500/20 transition-colors">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
                <h4 class="font-semibold text-white mb-1">Browse Services</h4>
                <p class="text-sm text-slate-400">Explore all available service categories</p>
            </a>
        </div>
    </div>

    {{-- Recent Bookings (empty state) --}}
    <div>
        <h3 class="text-lg font-semibold text-white mb-4">Recent Bookings</h3>
        <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-slate-400 font-medium">No bookings yet</p>
            <p class="text-sm text-slate-500 mt-1">Find a provider to get started!</p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Find a Provider
            </a>
        </div>
    </div>

</div>
@endsection
