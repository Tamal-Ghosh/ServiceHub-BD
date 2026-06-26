@extends('layouts.app')

@section('title', 'Provider Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Welcome Section --}}
    <div class="animate-fade-up">
        <h2 class="text-2xl font-bold text-white">Welcome back, {{ auth()->user()->name }}! 🛠️</h2>
        <p class="text-slate-400 mt-1">Manage your services and bookings</p>
    </div>

    {{-- Approval Status Banner --}}
    @if(!auth()->user()->is_approved)
        <div class="backdrop-blur-xl bg-amber-500/[0.08] border border-amber-500/20 rounded-2xl p-5 flex items-start gap-4 animate-fade-up">
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h4 class="text-amber-300 font-semibold">Account Pending Approval</h4>
                <p class="text-amber-400/70 text-sm mt-1">Your account is currently being reviewed by our admin team. You will be able to receive bookings once approved.</p>
            </div>
        </div>
    @endif

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

        {{-- Pending Requests --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/5" style="animation: fade-up 0.5s ease-out 0.2s both;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-amber-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Pending
                </span>
            </div>
            <p class="text-3xl font-bold text-white">0</p>
            <p class="text-sm text-slate-400 mt-1">Pending Requests</p>
        </div>

        {{-- Completed Jobs --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/5" style="animation: fade-up 0.5s ease-out 0.3s both;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-slate-500">All time</span>
            </div>
            <p class="text-3xl font-bold text-white">0</p>
            <p class="text-sm text-slate-400 mt-1">Completed Jobs</p>
        </div>

        {{-- Average Rating --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-purple-500/5" style="animation: fade-up 0.5s ease-out 0.4s both;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <span class="text-xs text-slate-500">Average</span>
            </div>
            <p class="text-3xl font-bold text-white">0.0</p>
            <p class="text-sm text-slate-400 mt-1">Average Rating</p>
        </div>
    </div>

    {{-- Earnings Overview --}}
    <div>
        <h3 class="text-lg font-semibold text-white mb-4">Earnings Overview</h3>
        <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-8 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-slate-400 font-medium">Earnings tracking coming soon</p>
            <p class="text-sm text-slate-500 mt-1">Complete jobs to start tracking your earnings</p>
        </div>
    </div>

    {{-- Recent Booking Requests (empty state) --}}
    <div>
        <h3 class="text-lg font-semibold text-white mb-4">Recent Booking Requests</h3>
        <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <p class="text-slate-400 font-medium">No booking requests yet</p>
            <p class="text-sm text-slate-500 mt-1">New booking requests from customers will appear here</p>
        </div>
    </div>

</div>
@endsection
