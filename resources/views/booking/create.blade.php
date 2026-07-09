@extends('layouts.app')

@section('title', 'Book ' . $provider->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Back Button --}}
    <div>
        <a href="{{ route('provider.profile.show', $provider->id) }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Profile
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Booking Form Card --}}
        <div class="md:col-span-2 backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6 shadow-xl">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Book {{ $provider->name }}
            </h2>

            @if ($errors->any())
                <div class="backdrop-blur-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-xl text-sm mb-6">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customer.bookings.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="provider_id" value="{{ $provider->id }}">

                {{-- Date --}}
                <div>
                    <label for="booking_date" class="block text-sm font-medium text-slate-300 mb-2">Booking Date</label>
                    <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                        class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Start Time --}}
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-slate-300 mb-2">Start Time</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', '09:00') }}"
                            class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" required>
                    </div>

                    {{-- Duration --}}
                    <div>
                        <label for="duration" class="block text-sm font-medium text-slate-300 mb-2">Duration (Hours)</label>
                        <input type="number" name="duration" id="duration" min="1" max="12" value="{{ old('duration', 1) }}"
                            class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" required>
                    </div>
                </div>

                {{-- Problem Description --}}
                <div>
                    <label for="problem_description" class="block text-sm font-medium text-slate-300 mb-2">Problem Description / Service Details</label>
                    <textarea name="problem_description" id="problem_description" rows="4" placeholder="Describe the issue you need help with..."
                        class="w-full bg-slate-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all resize-none" required>{{ old('problem_description') }}</textarea>
                    <p class="text-xs text-slate-500 mt-1">Minimum 10 characters.</p>
                </div>

                {{-- Pricing Widget --}}
                <div class="backdrop-blur-xl bg-indigo-500/10 border border-indigo-500/20 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-indigo-400/80 uppercase font-semibold tracking-wider">Estimated Total</p>
                        <p class="text-2xl font-bold text-white mt-1">৳<span id="total-price-display">0</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Rate: ৳{{ number_format($provider->providerProfile->hourly_rate) }}/hr</p>
                        <p class="text-xs text-slate-500 mt-0.5">Calculated automatically</p>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold shadow-lg shadow-indigo-500/25 transition-all duration-300">
                    Submit Booking Request
                </button>
            </form>
        </div>

        {{-- Provider Info & Availability Sidebar --}}
        <div class="md:col-span-1 space-y-6">
            {{-- Quick Profile --}}
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 text-center">
                @if($provider->providerProfile && $provider->providerProfile->profile_photo)
                    <img src="{{ asset('storage/' . $provider->providerProfile->profile_photo) }}" alt="{{ $provider->name }}" class="w-20 h-20 rounded-xl object-cover border-2 border-white/10 mx-auto shadow-md mb-3">
                @else
                    <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-2xl font-bold text-white mx-auto shadow-md mb-3">
                        {{ $provider->initials }}
                    </div>
                @endif
                <h3 class="font-bold text-white text-base">{{ $provider->name }}</h3>
                <p class="text-xs text-indigo-400 font-medium mt-1">
                    {{ $provider->skills->pluck('name')->join(', ') }}
                </p>
            </div>

            {{-- Availability Calendar --}}
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5">
                <h4 class="text-sm font-semibold text-white mb-3">Provider Availability</h4>
                @if($provider->availabilities->count() > 0)
                    <div class="space-y-2">
                        @foreach($provider->availabilities as $av)
                            <div class="flex items-center justify-between p-2 rounded-lg bg-white/[0.02] border border-white/[0.04]">
                                <span class="text-xs font-semibold text-slate-300">{{ $av->day_of_week }}</span>
                                <span class="text-[10px] text-slate-400">
                                    {{ \Carbon\Carbon::parse($av->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($av->end_time)->format('g:i A') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500 italic">No availability slots set.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hourlyRate = {{ $provider->providerProfile->hourly_rate }};
        const durationInput = document.getElementById('duration');
        const priceDisplay = document.getElementById('total-price-display');

        function updatePrice() {
            const duration = parseInt(durationInput.value) || 0;
            const total = duration * hourlyRate;
            priceDisplay.textContent = total.toLocaleString();
        }

        durationInput.addEventListener('input', updatePrice);
        durationInput.addEventListener('change', updatePrice);
        
        // Initial run
        updatePrice();
    });
</script>
@endsection
