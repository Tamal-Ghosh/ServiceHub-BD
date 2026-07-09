@extends('layouts.app')

@section('title', 'Provider Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Welcome Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-up">
        <div>
            <h2 class="text-2xl font-bold text-white">Welcome back, {{ auth()->user()->name }}! 🛠️</h2>
            <p class="text-slate-400 mt-1">Manage your services, earnings, and bookings</p>
        </div>
        <div>
            <a href="{{ route('provider.bookings.index') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-all shadow-lg shadow-indigo-500/25">
                Go to Bookings Workspace
            </a>
        </div>
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

    {{-- Notifications --}}
    @if(session('success'))
        <div class="backdrop-blur-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl text-sm animate-fade-up">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="backdrop-blur-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-xl text-sm animate-fade-up">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Total Bookings --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-500/5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-xs text-slate-500">All time</span>
            </div>
            <p class="text-3xl font-bold text-white">{{ auth()->user()->providerBookings()->count() }}</p>
            <p class="text-sm text-slate-400 mt-1">Total Bookings</p>
        </div>

        {{-- Pending Requests --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-amber-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Active Requests
                </span>
            </div>
            <p class="text-3xl font-bold text-white">{{ auth()->user()->providerBookings()->whereIn('status', ['pending', 'accepted', 'in_progress'])->count() }}</p>
            <p class="text-sm text-slate-400 mt-1">Pending/Active Jobs</p>
        </div>

        {{-- Completed Jobs --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-slate-500">All time</span>
            </div>
            <p class="text-3xl font-bold text-white">{{ auth()->user()->providerBookings()->where('status', 'completed')->count() }}</p>
            <p class="text-sm text-slate-400 mt-1">Completed Jobs</p>
        </div>

        {{-- Average Rating --}}
        <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-purple-500/5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <span class="text-xs text-slate-500">Average</span>
            </div>
            <p class="text-3xl font-bold text-white">{{ auth()->user()->average_rating }} ★</p>
            <p class="text-sm text-slate-400 mt-1">Rating ({{ auth()->user()->review_count }} Reviews)</p>
        </div>
    </div>

    {{-- Wallet & Withdrawal Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Wallet Balance Stats --}}
        <div class="lg:col-span-1 space-y-6">
            <h3 class="text-lg font-semibold text-white">My Earnings & Wallet</h3>
            
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6 space-y-6">
                
                {{-- Withdrawable Balance Card --}}
                <div class="bg-gradient-to-br from-emerald-500/10 to-indigo-500/10 border border-emerald-500/20 p-5 rounded-xl text-center">
                    <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Withdrawable Balance</span>
                    <span class="text-4xl font-black text-emerald-400 mt-2 block">৳{{ number_format(auth()->user()->provider_withdrawable_balance) }}</span>
                    <span class="text-[10px] text-slate-500 block mt-1">Platform Charge (15%) already deducted.</span>
                    
                    @if(auth()->user()->provider_withdrawable_balance >= 100)
                        <button onclick="openWithdrawModal()" class="w-full mt-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg shadow-emerald-500/10">
                            Withdraw Payout
                        </button>
                    @else
                        <button class="w-full mt-4 py-2.5 rounded-xl bg-slate-800 text-slate-500 text-xs font-bold cursor-not-allowed" disabled>
                            Min ৳100 to Withdraw
                        </button>
                    @endif
                </div>

                {{-- Other Balance Details --}}
                <div class="space-y-3.5 text-sm pt-2">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Total Provider Earnings:</span>
                        <span class="font-bold text-slate-200">৳{{ number_format(auth()->user()->provider_total_earnings) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Pending Escrow Earnings:</span>
                        <span class="font-bold text-amber-400">৳{{ number_format(auth()->user()->provider_pending_earnings) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Approved Withdrawals:</span>
                        <span class="font-bold text-slate-200">৳{{ number_format(auth()->user()->provider_total_withdrawn) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Pending Withdrawals:</span>
                        <span class="font-bold text-slate-200">৳{{ number_format(auth()->user()->provider_pending_withdrawal) }}</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Withdrawal Requests History --}}
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-lg font-semibold text-white">Withdrawal History</h3>
            
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl overflow-hidden shadow-md">
                @if($withdrawals->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-14 h-14 rounded-xl bg-slate-800 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h11l5 5v11a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-slate-400 font-medium text-sm">No payout requests submitted yet.</p>
                        <p class="text-xs text-slate-500 mt-1">Submit your first request once your withdrawable balance exceeds ৳100.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-white/[0.02] border-b border-white/[0.06] text-xs text-slate-400 uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="px-6 py-4">Requested On</th>
                                    <th class="px-6 py-4">Method</th>
                                    <th class="px-6 py-4">Account Details</th>
                                    <th class="px-6 py-4">Amount</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/[0.04]">
                                @foreach($withdrawals as $withdrawal)
                                    <tr class="hover:bg-white/[0.02] transition-colors">
                                        <td class="px-6 py-4 text-slate-300">
                                            {{ $withdrawal->created_at->format('M d, Y') }}
                                            <span class="text-[10px] text-slate-500 block mt-0.5">{{ $withdrawal->created_at->format('g:i A') }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 rounded bg-indigo-500/10 text-indigo-400 text-xs font-semibold">
                                                {{ $withdrawal->payment_method }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-300 font-mono">
                                            {{ $withdrawal->account_number }}
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-200">
                                            ৳{{ number_format($withdrawal->amount) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($withdrawal->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                    Pending
                                                </span>
                                            @elseif($withdrawal->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    Approved
                                                </span>
                                            @elseif($withdrawal->status === 'rejected')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                    Rejected
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>

{{-- Request Withdrawal Modal --}}
<div id="withdraw-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden">
    <div class="backdrop-blur-xl bg-slate-900 border border-white/10 rounded-2xl max-w-md w-full p-6 shadow-2xl animate-fade-in">
        <h3 class="text-lg font-bold text-white mb-2">Request Wallet Withdrawal</h3>
        <p class="text-xs text-slate-400 mb-4">Payout requests are manually approved and transferred by the administrative team.</p>

        <form method="POST" action="{{ route('provider.withdrawals.store') }}" class="space-y-4">
            @csrf
            
            {{-- Amount --}}
            <div>
                <label for="amount" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Withdrawal Amount (BDT)</label>
                <input type="number" name="amount" id="amount" min="100" max="{{ auth()->user()->provider_withdrawable_balance }}" value="{{ old('amount') }}" placeholder="Minimum ৳100"
                    class="w-full bg-slate-950 border border-white/10 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-mono" required>
                <span class="text-[10px] text-slate-500 block mt-1">Available balance: ৳{{ number_format(auth()->user()->provider_withdrawable_balance) }}</span>
            </div>

            {{-- Method --}}
            <div>
                <label for="payment_method" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Transfer Method</label>
                <select name="payment_method" id="payment_method"
                    class="w-full bg-slate-950 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" required>
                    <option value="bKash">bKash</option>
                    <option value="Rocket">Rocket</option>
                    <option value="Nagad">Nagad</option>
                    <option value="Bank">Bank Transfer</option>
                </select>
            </div>

            {{-- Account Number --}}
            <div>
                <label for="account_number" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Wallet/Account Number</label>
                <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}" placeholder="Mobile Wallet No. or Bank Account Details"
                    class="w-full bg-slate-950 border border-white/10 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" required>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeWithdrawModal()" class="px-4 py-2 rounded-xl bg-white/[0.05] border border-white/[0.08] hover:bg-white/[0.1] text-xs font-bold text-slate-300 transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-bold text-white transition-all shadow-lg shadow-indigo-500/20">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openWithdrawModal() {
        document.getElementById('withdraw-modal').classList.remove('hidden');
    }

    function closeWithdrawModal() {
        document.getElementById('withdraw-modal').classList.add('hidden');
    }
</script>
@endsection
