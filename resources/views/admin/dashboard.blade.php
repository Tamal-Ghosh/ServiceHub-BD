@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Welcome Section --}}
    <div class="animate-fade-up">
        <h2 class="text-2xl font-bold text-white">Admin Dashboard 🎛️</h2>
        <p class="text-slate-400 mt-1">Platform overview and management</p>
    </div>

    {{-- Session Alerts --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm animate-fade-up">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm animate-fade-up">
            {{ session('error') }}
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- Total Users --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-500/5" style="animation: fade-up 0.5s ease-out 0.1s both;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-xs text-slate-500">Total</span>
            </div>
            <p class="text-3xl font-bold text-white">{{ $total_users }}</p>
            <p class="text-sm text-slate-400 mt-1">Total Users</p>
        </div>

        {{-- Service Providers --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/5" style="animation: fade-up 0.5s ease-out 0.2s both;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs text-slate-500">Registered</span>
            </div>
            <p class="text-3xl font-bold text-white">{{ $total_providers }}</p>
            <p class="text-sm text-slate-400 mt-1">Service Providers</p>
        </div>

        {{-- Customers --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-purple-500/5" style="animation: fade-up 0.5s ease-out 0.3s both;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="text-xs text-slate-500">Registered</span>
            </div>
            <p class="text-3xl font-bold text-white">{{ $total_customers }}</p>
            <p class="text-sm text-slate-400 mt-1">Customers</p>
        </div>

        {{-- Pending Approvals --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/5" style="animation: fade-up 0.5s ease-out 0.4s both;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-500 to-rose-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                @if($pending_providers->count() > 0)
                    <span class="text-xs text-amber-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Action needed
                    </span>
                @endif
            </div>
            <p class="text-3xl font-bold text-white">{{ $pending_providers->count() }}</p>
            <p class="text-sm text-slate-400 mt-1">Pending Approvals</p>
        </div>
    </div>

    {{-- Admin Navigation Tabs --}}
    <div class="border-b border-white/10 mb-6">
        <nav class="flex gap-6 text-sm font-semibold" aria-label="Tabs">
            <button onclick="switchTab('tab-pending-providers')" id="btn-tab-pending-providers" class="tab-btn pb-4 border-b-2 border-indigo-500 text-indigo-400 focus:outline-none transition-all">
                Pending Providers ({{ $pending_providers->count() }})
            </button>
            <button onclick="switchTab('tab-pending-withdrawals')" id="btn-tab-pending-withdrawals" class="tab-btn pb-4 border-b-2 border-transparent text-slate-400 hover:text-white focus:outline-none transition-all">
                Withdrawals ({{ $pending_withdrawals->count() }})
            </button>
            <button onclick="switchTab('tab-all-users')" id="btn-tab-all-users" class="tab-btn pb-4 border-b-2 border-transparent text-slate-400 hover:text-white focus:outline-none transition-all">
                All Users ({{ $all_users->count() }})
            </button>
            <button onclick="switchTab('tab-all-bookings')" id="btn-tab-all-bookings" class="tab-btn pb-4 border-b-2 border-transparent text-slate-400 hover:text-white focus:outline-none transition-all">
                All Bookings ({{ $all_bookings->count() }})
            </button>
        </nav>
    </div>

    {{-- TAB 1: PENDING PROVIDERS --}}
    <div id="tab-pending-providers" class="tab-content">
        <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl overflow-hidden shadow-md">
            @if($pending_providers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white/[0.02] border-b border-white/[0.06] text-xs text-slate-400 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="px-6 py-4">Provider</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">City</th>
                                <th class="px-6 py-4">Registered</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.04] text-slate-300">
                            @foreach($pending_providers as $provider)
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-xs font-bold text-white shadow-md">
                                                {{ strtoupper(substr($provider->name, 0, 2)) }}
                                            </div>
                                            <span class="font-medium text-white">{{ $provider->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ $provider->email }}</td>
                                    <td class="px-6 py-4">{{ $provider->city ?? '—' }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $provider->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.providers.approve', $provider->id) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-md shadow-emerald-500/10">
                                                Approve
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-slate-400 font-medium text-sm">No pending approvals</p>
                    <p class="text-xs text-slate-500 mt-1">All providers have been reviewed and approved.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- TAB 2: PENDING WITHDRAWALS --}}
    <div id="tab-pending-withdrawals" class="tab-content hidden">
        <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl overflow-hidden shadow-md">
            @if($pending_withdrawals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white/[0.02] border-b border-white/[0.06] text-xs text-slate-400 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="px-6 py-4">Provider</th>
                                <th class="px-6 py-4">Requested On</th>
                                <th class="px-6 py-4">Method</th>
                                <th class="px-6 py-4">Account Details</th>
                                <th class="px-6 py-4">Amount</th>
                                <th class="px-6 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.04] text-slate-300">
                            @foreach($pending_withdrawals as $withdrawal)
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-xs font-bold text-white shadow-md">
                                                {{ strtoupper(substr($withdrawal->provider->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="font-medium text-white block">{{ $withdrawal->provider->name }}</span>
                                                <span class="text-[10px] text-slate-500 block">Balance: ৳{{ number_format($withdrawal->provider->provider_withdrawable_balance) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $withdrawal->created_at->format('M d, Y') }}
                                        <span class="text-[10px] text-slate-500 block mt-0.5">{{ $withdrawal->created_at->format('g:i A') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 rounded bg-indigo-500/10 text-indigo-400 text-xs font-semibold">
                                            {{ $withdrawal->payment_method }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono">{{ $withdrawal->account_number }}</td>
                                    <td class="px-6 py-4 font-bold text-slate-200">৳{{ number_format($withdrawal->amount) }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('admin.withdrawals.status', $withdrawal->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-md shadow-emerald-500/10">
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.withdrawals.status', $withdrawal->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-600/10 hover:bg-rose-600/20 border border-rose-500/20 text-rose-400 text-xs font-bold transition-all">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-slate-400 font-medium text-sm">No pending withdrawal requests</p>
                    <p class="text-xs text-slate-500 mt-1">All payout requests have been fully processed.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- TAB 3: ALL USERS --}}
    <div id="tab-all-users" class="tab-content hidden">
        <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl overflow-hidden shadow-md">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-white/[0.02] border-b border-white/[0.06] text-xs text-slate-400 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">City</th>
                            <th class="px-6 py-4">Joined Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04] text-slate-300">
                        @foreach($all_users as $user)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br
                                            {{ $user->role === 'admin' ? 'from-rose-500 to-rose-600' : ($user->role === 'provider' ? 'from-emerald-500 to-emerald-600' : 'from-blue-500 to-blue-600') }}
                                            flex items-center justify-center text-xs font-bold text-white shadow-sm">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <span class="font-medium text-white">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        {{ $user->role === 'admin' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : ($user->role === 'provider' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $user->city ?? '—' }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAB 4: ALL BOOKINGS --}}
    <div id="tab-all-bookings" class="tab-content hidden">
        <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl overflow-hidden shadow-md">
            @if($all_bookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white/[0.02] border-b border-white/[0.06] text-xs text-slate-400 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Customer</th>
                                <th class="px-6 py-4">Provider</th>
                                <th class="px-6 py-4">Date & Time</th>
                                <th class="px-6 py-4">Price</th>
                                <th class="px-6 py-4">Payment</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.04] text-slate-300">
                            @foreach($all_bookings as $booking)
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-6 py-4 font-mono text-slate-500 text-xs">#{{ $booking->id }}</td>
                                    <td class="px-6 py-4 text-white font-medium">{{ $booking->customer->name }}</td>
                                    <td class="px-6 py-4 text-slate-300">{{ $booking->provider->name }}</td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                        <span class="text-[10px] text-slate-500 block mt-0.5">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} ({{ $booking->duration }} hr)</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-200">৳{{ number_format($booking->total_price) }}</td>
                                    <td class="px-6 py-4">
                                        @if($booking->payment)
                                            <span class="px-2 py-0.5 rounded bg-pink-500/10 text-pink-400 text-xs font-mono font-semibold" title="Transaction ID: {{ $booking->payment->transaction_id }}">
                                                Paid ({{ substr($booking->payment->transaction_id, 0, 8) }}...)
                                            </span>
                                        @else
                                            <span class="text-slate-500 text-xs">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($booking->status === 'pending_payment')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-pink-500/10 text-pink-400 border border-pink-500/20">
                                                Unpaid
                                            </span>
                                        @elseif($booking->status === 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                Pending
                                            </span>
                                        @elseif($booking->status === 'accepted')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                Accepted
                                            </span>
                                        @elseif($booking->status === 'in_progress')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                                In Progress
                                            </span>
                                        @elseif($booking->status === 'completed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                Completed
                                            </span>
                                        @elseif($booking->status === 'rejected')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                Declined
                                            </span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p class="text-slate-400 font-medium text-sm">No bookings recorded yet</p>
                    <p class="text-xs text-slate-500 mt-1">Bookings will appear once customers register requests.</p>
                </div>
            @endif
        </div>
    </div>

</div>

<script>
    function switchTab(tabId) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // Show active tab
        document.getElementById(tabId).classList.remove('hidden');
        
        // Reset button borders & text color
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-indigo-500', 'text-indigo-400');
            btn.classList.add('border-transparent', 'text-slate-400', 'hover:text-white');
        });
        
        // Set active button
        const activeBtn = document.getElementById(`btn-${tabId}`);
        activeBtn.classList.remove('border-transparent', 'text-slate-400', 'hover:text-white');
        activeBtn.classList.add('border-indigo-500', 'text-indigo-400');
    }
</script>
@endsection
