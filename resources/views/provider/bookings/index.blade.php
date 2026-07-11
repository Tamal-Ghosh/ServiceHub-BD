@extends('layouts.app')

@section('title', 'Provider Bookings')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 animate-fade-up">
    <div>
        <h2 class="text-2xl font-bold text-white">Manage Bookings</h2>
        <p class="text-sm text-slate-400">Accept incoming requests, start, and complete your active jobs</p>
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

    {{-- SECTION 1: INCOMING REQUESTS --}}
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
            Incoming Requests ({{ $incomingBookings->count() }})
        </h3>

        @if($incomingBookings->isEmpty())
            <div class="backdrop-blur-xl bg-white/[0.02] border border-white/[0.05] rounded-2xl p-6 text-center text-slate-500 text-sm">
                No new booking requests at the moment.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($incomingBookings as $booking)
                    <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-500/10 flex items-center justify-center text-sm font-bold text-indigo-400">
                                        {{ strtoupper(substr($booking->customer->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white text-sm">{{ $booking->customer->name }}</h4>
                                        <p class="text-[10px] text-slate-500">{{ $booking->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="text-emerald-400 font-bold text-sm">৳{{ number_format($booking->total_price) }}</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-xs py-2 border-y border-white/5">
                                <div>
                                    <span class="text-[10px] text-slate-500 block uppercase tracking-wider font-semibold">Date</span>
                                    <span class="text-slate-300 font-medium mt-0.5 block">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-500 block uppercase tracking-wider font-semibold">Time</span>
                                    <span class="text-slate-300 font-medium mt-0.5 block">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-500 block uppercase tracking-wider font-semibold">Duration</span>
                                    <span class="text-slate-300 font-medium mt-0.5 block">{{ $booking->duration }} {{ Str::plural('Hr', $booking->duration) }}</span>
                                </div>
                            </div>

                            <div>
                                <span class="text-[10px] text-slate-500 block uppercase tracking-wider font-semibold mb-1">Issue Details</span>
                                <p class="text-slate-300 text-xs leading-relaxed whitespace-pre-line bg-white/[0.01] border border-white/[0.04] p-2.5 rounded-lg">
                                    {{ $booking->problem_description }}
                                </p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 mt-5 pt-3 border-t border-white/5">
                            <form action="{{ route('provider.bookings.status', $booking->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="w-full py-2.5 rounded-xl border border-rose-500/20 hover:border-rose-500/40 hover:bg-rose-500/5 text-rose-400 text-xs font-semibold transition-all">
                                    Decline Request
                                </button>
                            </form>
                            <form action="{{ route('provider.bookings.status', $booking->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-md shadow-indigo-600/10 transition-all">
                                    Accept Request
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- SECTION 2: ACTIVE BOOKINGS --}}
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-indigo-400"></span>
            Active Bookings ({{ $activeBookings->count() }})
        </h3>

        @if($activeBookings->isEmpty())
            <div class="backdrop-blur-xl bg-white/[0.02] border border-white/[0.05] rounded-2xl p-6 text-center text-slate-500 text-sm">
                No active bookings at the moment.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($activeBookings as $booking)
                    <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-500/10 flex items-center justify-center text-sm font-bold text-indigo-400">
                                        {{ strtoupper(substr($booking->customer->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white text-sm">{{ $booking->customer->name }}</h4>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            @if($booking->status === 'accepted')
                                                <span class="inline-flex w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                                <span class="text-[9px] text-indigo-400 font-semibold uppercase tracking-wider">Accepted</span>
                                            @else
                                                <span class="inline-flex w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                                                <span class="text-[9px] text-purple-400 font-semibold uppercase tracking-wider">In Progress</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="text-emerald-400 font-bold text-sm">৳{{ number_format($booking->total_price) }}</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-xs py-2 border-y border-white/5">
                                <div>
                                    <span class="text-[10px] text-slate-500 block uppercase tracking-wider font-semibold">Date</span>
                                    <span class="text-slate-300 font-medium mt-0.5 block">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-500 block uppercase tracking-wider font-semibold">Time</span>
                                    <span class="text-slate-300 font-medium mt-0.5 block">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-500 block uppercase tracking-wider font-semibold">Duration</span>
                                    <span class="text-slate-300 font-medium mt-0.5 block">{{ $booking->duration }} {{ Str::plural('Hr', $booking->duration) }}</span>
                                </div>
                            </div>

                            <div>
                                <span class="text-[10px] text-slate-500 block uppercase tracking-wider font-semibold mb-1">Issue Details</span>
                                <p class="text-slate-300 text-xs leading-relaxed whitespace-pre-line bg-white/[0.01] border border-white/[0.04] p-2.5 rounded-lg">
                                    {{ $booking->problem_description }}
                                </p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-5 pt-3 border-t border-white/5">
                            @if($booking->status === 'accepted')
                                <form action="{{ route('provider.bookings.status', $booking->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-semibold shadow-md shadow-purple-600/10 transition-all">
                                        Start Work (Mark as In Progress)
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('provider.bookings.status', $booking->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-md shadow-emerald-600/10 transition-all">
                                        Finish Work (Mark as Completed)
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- SECTION 3: PAST BOOKINGS --}}
    <div class="space-y-4" id="past-history">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span>
            Past History ({{ $pastBookings->count() }})
        </h3>

        @if($pastBookings->isEmpty())
            <div class="backdrop-blur-xl bg-white/[0.02] border border-white/[0.05] rounded-2xl p-6 text-center text-slate-500 text-sm">
                No past bookings history.
            </div>
        @else
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl overflow-hidden shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/[0.02]">
                                <th class="p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Customer</th>
                                <th class="p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Date</th>
                                <th class="p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</th>
                                <th class="p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Review & Feedback</th>
                                <th class="p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($pastBookings as $booking)
                                <tr class="hover:bg-white/[0.01] transition-colors">
                                    <td class="p-4">
                                        <span class="font-semibold text-white block">{{ $booking->customer->name }}</span>
                                    </td>
                                    <td class="p-4 text-slate-300 text-xs">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}
                                    </td>
                                    <td class="p-4 text-emerald-400 font-bold text-xs">
                                        ৳{{ number_format($booking->total_price) }}
                                    </td>
                                    <td class="p-4">
                                        @if($booking->status === 'completed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                Completed
                                            </span>
                                        @elseif($booking->status === 'rejected')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                Declined
                                            </span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-xs">
                                        @if($booking->status === 'completed')
                                            @if($booking->review)
                                                <div class="space-y-1.5 max-w-xs">
                                                    <div class="flex items-center gap-1 text-amber-400 font-bold">
                                                        ★ {{ $booking->review->rating }}
                                                    </div>
                                                    @if($booking->review->comment)
                                                        <p class="text-slate-300 italic text-[11px] leading-snug whitespace-pre-line">"{{ $booking->review->comment }}"</p>
                                                    @endif
                                                    
                                                    @if($booking->review->reply)
                                                        <div class="bg-white/[0.02] border border-white/[0.04] p-1.5 rounded mt-1">
                                                            <span class="text-[10px] text-indigo-400 font-bold block">Your Reply:</span>
                                                            <p class="text-slate-400 text-[11px] leading-snug">"{{ $booking->review->reply }}"</p>
                                                        </div>
                                                    @else
                                                        <button type="button" onclick="toggleReplyForm({{ $booking->review->id }})" class="text-[10px] font-bold text-indigo-400 hover:text-indigo-300 mt-1 block">
                                                            Reply to Review
                                                        </button>
                                                        <form id="reply-form-{{ $booking->review->id }}" method="POST" action="{{ route('provider.reviews.reply', $booking->review->id) }}" class="mt-2 space-y-1.5 hidden">
                                                            @csrf
                                                            <input type="text" name="reply" placeholder="Write a reply..." minlength="3" maxlength="1000" required
                                                                class="w-full bg-slate-950 border border-white/10 rounded px-2.5 py-1 text-[11px] text-white focus:outline-none focus:border-indigo-500">
                                                            <div class="flex justify-end gap-1.5">
                                                                <button type="button" onclick="toggleReplyForm({{ $booking->review->id }})" class="text-[9px] text-slate-500 hover:text-slate-300 font-bold">Cancel</button>
                                                                <button type="submit" class="text-[9px] text-white bg-indigo-600 hover:bg-indigo-500 px-2 py-0.5 rounded font-bold">Send</button>
                                                            </div>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-slate-500 text-xs">No review yet</span>
                                            @endif
                                        @else
                                            <span class="text-slate-500 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-slate-400 text-xs italic max-w-xs truncate">
                                        @if($booking->status === 'cancelled' && $booking->cancellation_reason)
                                            Cancelled: "{{ $booking->cancellation_reason }}"
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleReplyForm(reviewId) {
        const form = document.getElementById(`reply-form-${reviewId}`);
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    }

    // Auto scroll to past history section if completed tab parameter is passed
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'completed') {
            const element = document.getElementById('past-history');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
</script>
@endsection
