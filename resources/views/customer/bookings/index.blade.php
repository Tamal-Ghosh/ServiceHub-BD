@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">My Bookings</h2>
            <p class="text-sm text-slate-400">Track and manage your service booking requests</p>
        </div>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-sm transition-colors">
            Find New Provider
        </a>
    </div>

    @if(session('success'))
        <div class="backdrop-blur-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="backdrop-blur-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($bookings->isEmpty())
        <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">No Bookings Yet</h3>
            <p class="text-slate-400 text-sm max-w-sm mx-auto mb-6">You haven't requested any service bookings yet. Browse our top providers to get started.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold transition-all">
                Browse Providers
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6 shadow-md transition-all hover:border-white/10 animate-fade-up">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/5 pb-4 mb-4">
                        {{-- Provider Info --}}
                        <div class="flex items-center gap-4">
                            @if($booking->provider->providerProfile && $booking->provider->providerProfile->profile_photo)
                                <img src="{{ asset('storage/' . $booking->provider->providerProfile->profile_photo) }}" alt="{{ $booking->provider->name }}" class="w-12 h-12 rounded-xl object-cover border border-white/10">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-lg font-bold text-white shadow-sm">
                                    {{ $booking->provider->initials }}
                                </div>
                            @endif
                            <div>
                                <h4 class="font-bold text-white text-base">{{ $booking->provider->name }}</h4>
                                <p class="text-xs text-indigo-400 font-medium mt-0.5">
                                    {{ $booking->provider->skills->pluck('name')->join(', ') }}
                                </p>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        <div>
                            @if($booking->status === 'pending_payment')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-pink-500/10 text-pink-400 border border-pink-500/20">
                                    Unpaid (bKash)
                                </span>
                            @elseif($booking->status === 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    Pending Approval
                                </span>
                            @elseif($booking->status === 'accepted')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                    Accepted
                                </span>
                            @elseif($booking->status === 'in_progress')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                    In Progress
                                </span>
                            @elseif($booking->status === 'completed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    Completed
                                </span>
                            @elseif($booking->status === 'rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                    Declined by Provider
                                </span>
                            @elseif($booking->status === 'cancelled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                    Cancelled
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Details Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-4">
                        <div>
                            <span class="text-xs text-slate-500 block uppercase tracking-wider font-semibold">Date</span>
                            <span class="text-slate-200 font-medium mt-1 block">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block uppercase tracking-wider font-semibold">Time</span>
                            <span class="text-slate-200 font-medium mt-1 block">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block uppercase tracking-wider font-semibold">Duration</span>
                            <span class="text-slate-200 font-medium mt-1 block">
                                {{ $booking->duration }} {{ Str::plural('Hour', $booking->duration) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block uppercase tracking-wider font-semibold">Price</span>
                            <span class="text-emerald-400 font-bold mt-1 block">
                                ৳{{ number_format($booking->total_price) }}
                            </span>
                        </div>
                    </div>

                    {{-- Problem Description --}}
                    <div class="bg-white/[0.01] border border-white/[0.04] p-3 rounded-xl mb-4">
                        <span class="text-xs text-slate-500 block uppercase tracking-wider font-semibold mb-1">Issue Description</span>
                        <p class="text-slate-300 text-sm whitespace-pre-line">{{ $booking->problem_description }}</p>
                    </div>

                    {{-- Cancellation Reason (if cancelled) --}}
                    @if($booking->status === 'cancelled' && $booking->cancellation_reason)
                        <div class="bg-rose-500/5 border border-rose-500/10 p-3 rounded-xl mb-4 text-rose-300/90 text-sm">
                            <span class="text-xs text-rose-400 block uppercase tracking-wider font-bold mb-1">Cancellation Reason</span>
                            <p class="italic">"{{ $booking->cancellation_reason }}"</p>
                        </div>
                    @endif

                    {{-- Actions --}}
                    @if(in_array($booking->status, ['pending', 'accepted', 'pending_payment']))
                        <div class="flex justify-end items-center gap-3 pt-2">
                            @if($booking->status === 'pending_payment')
                                <a href="{{ route('payment.show', $booking->id) }}" class="px-4 py-2 text-xs font-semibold text-white bg-pink-600 hover:bg-pink-500 rounded-xl transition-all shadow-md shadow-pink-600/10">
                                    Pay Now (bKash)
                                </a>
                            @endif
                            <button onclick="openCancelModal({{ $booking->id }})" class="px-4 py-2 text-xs font-semibold text-rose-400 hover:text-rose-300 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 rounded-xl transition-all">
                                Cancel Booking
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Cancellation Modal --}}
<div id="cancel-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden">
    <div class="backdrop-blur-xl bg-slate-900 border border-white/10 rounded-2xl max-w-md w-full p-6 shadow-2xl animate-fade-in">
        <h3 class="text-lg font-bold text-white mb-2">Cancel Service Booking</h3>
        <p class="text-xs text-slate-400 mb-4">Please provide a brief reason for cancelling your booking request.</p>

        <form id="cancel-form" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <textarea name="cancellation_reason" id="cancellation_reason" rows="3" placeholder="Reason for cancellation (e.g. My plan changed, found another way, etc.)"
                    class="w-full bg-slate-950 border border-white/10 rounded-xl px-3 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all resize-none" required></textarea>
                <span class="text-[10px] text-slate-500 block mt-1">Minimum 5 characters.</span>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeCancelModal()" class="px-4 py-2 rounded-xl text-slate-400 hover:text-white bg-white/[0.05] hover:bg-white/[0.1] text-xs font-semibold transition-all">
                    Dismiss
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl text-white bg-rose-600 hover:bg-rose-500 text-xs font-semibold shadow-md shadow-rose-600/25 transition-all">
                    Confirm Cancellation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCancelModal(bookingId) {
        const modal = document.getElementById('cancel-modal');
        const form = document.getElementById('cancel-form');
        form.action = `/customer/bookings/${bookingId}/cancel`;
        modal.classList.remove('hidden');
    }

    function closeCancelModal() {
        const modal = document.getElementById('cancel-modal');
        modal.classList.add('hidden');
    }
</script>
@endsection
