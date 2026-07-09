@extends('layouts.app')

@section('title', $provider->name . ' — Provider Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Back Button --}}
    <div>
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    {{-- Provider Header Card --}}
    <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-8 animate-fade-up">
        <div class="flex flex-col sm:flex-row items-start gap-6">

            {{-- Photo --}}
            <div class="shrink-0">
                @if($provider->providerProfile && $provider->providerProfile->profile_photo)
                    <img src="{{ asset('storage/' . $provider->providerProfile->profile_photo) }}" alt="{{ $provider->name }}" class="w-28 h-28 rounded-2xl object-cover border-2 border-white/10 shadow-lg">
                @else
                    <div class="w-28 h-28 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-3xl font-bold text-white shadow-lg">
                        {{ $provider->initials }}
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $provider->name }}</h2>
                        <div class="flex flex-wrap items-center gap-3 mt-2">
                            @if($provider->city)
                                <span class="inline-flex items-center gap-1.5 text-sm text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $provider->city }}{{ $provider->providerProfile && $provider->providerProfile->area ? ', ' . $provider->providerProfile->area : '' }}
                                </span>
                            @endif
                            @if($provider->providerProfile && $provider->providerProfile->experience_years > 0)
                                <span class="inline-flex items-center gap-1.5 text-sm text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $provider->providerProfile->experience_years }} yrs experience
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Hourly Rate Badge --}}
                    @if($provider->providerProfile && $provider->providerProfile->hourly_rate > 0)
                        <div class="backdrop-blur-xl bg-emerald-500/10 border border-emerald-500/20 rounded-xl px-5 py-3 text-center">
                            <p class="text-2xl font-bold text-emerald-400">৳{{ number_format($provider->providerProfile->hourly_rate) }}</p>
                            <p class="text-xs text-emerald-400/60">per hour</p>
                        </div>
                    @endif
                </div>

                {{-- Skills Tags --}}
                @if($provider->skills->count() > 0)
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach($provider->skills as $skill)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                {{ $skill->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT: Stats & Availability --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Quick Stats --}}
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6">
                <h3 class="text-base font-semibold text-white mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">Rating</span>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            <span class="text-white font-semibold">{{ $provider->average_rating }}</span>
                            <span class="text-slate-500 text-xs">({{ $provider->review_count }} {{ Str::plural('review', $provider->review_count) }})</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">Jobs Done</span>
                        <span class="text-white font-semibold">{{ $provider->review_count }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">Member Since</span>
                        <span class="text-white font-medium text-sm">{{ $provider->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6">
                <h3 class="text-base font-semibold text-white mb-4">Contact</h3>
                <div class="space-y-3">
                    @if($provider->phone)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <span class="text-sm text-slate-300">{{ $provider->phone }}</span>
                        </div>
                    @endif
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-sm text-slate-300">{{ $provider->email }}</span>
                    </div>
                </div>
            </div>

            {{-- Availability --}}
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6">
                <h3 class="text-base font-semibold text-white mb-4">Availability</h3>
                @if($provider->availabilities->count() > 0)
                    <div class="space-y-2">
                        @foreach($provider->availabilities as $av)
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-white/[0.03]">
                                <span class="text-sm font-medium text-slate-300">{{ $av->day_of_week }}</span>
                                <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($av->start_time)->format('g:i A') }} — {{ \Carbon\Carbon::parse($av->end_time)->format('g:i A') }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500">No availability set yet.</p>
                @endif
            </div>
        </div>

        {{-- RIGHT: Bio & Reviews --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Bio --}}
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6">
                <h3 class="text-base font-semibold text-white mb-3">About</h3>
                @if($provider->providerProfile && $provider->providerProfile->bio)
                    <p class="text-slate-300 leading-relaxed whitespace-pre-line">{{ $provider->providerProfile->bio }}</p>
                @else
                    <p class="text-slate-500 italic">No bio provided yet.</p>
                @endif
            </div>

            {{-- Reviews Section --}}
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6">
                <h3 class="text-base font-semibold text-white mb-4">Reviews ({{ $provider->review_count }})</h3>
                @if($provider->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($provider->reviews as $review)
                            <div class="p-4 rounded-xl bg-white/[0.02] border border-white/[0.04]">
                                <div class="flex items-center justify-between gap-4 mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-xs font-bold text-indigo-400">
                                            {{ strtoupper(substr($review->customer->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="text-sm font-semibold text-white">{{ $review->customer->name }}</span>
                                            <span class="text-xs text-slate-500 block">{{ $review->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Stars --}}
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-sm text-slate-300 leading-relaxed pl-10">
                                        {{ $review->comment }}
                                    </p>
                                @endif
                                
                                @if($review->reply)
                                    <div class="mt-3 ml-10 p-3 rounded-lg bg-indigo-500/[0.04] border border-indigo-500/10">
                                        <span class="text-xs font-bold text-indigo-400 block mb-1">Reply from {{ $provider->name }}:</span>
                                        <p class="text-xs text-slate-300 italic leading-relaxed">
                                            "{{ $review->reply }}"
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <p class="text-slate-400 font-medium">No reviews yet</p>
                        <p class="text-sm text-slate-500 mt-1">Reviews will appear here after completed bookings</p>
                    </div>
                @endif
            </div>

            {{-- Book Now Button --}}
            @auth
                @if(auth()->user()->isCustomer())
                    <div class="text-center">
                        <a href="{{ route('customer.bookings.create', $provider->id) }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-300 hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Book This Provider
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-300 hover:-translate-y-0.5">
                        Login to Book
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection
