@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Page Header --}}
    <div class="animate-fade-up">
        <h2 class="text-2xl font-bold text-white">Provider Profile Setup</h2>
        <p class="text-slate-400 mt-1">Complete your profile to start receiving bookings</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm animate-fade-up">
            {{ session('success') }}
        </div>
    @endif

    {{-- Approval Status --}}
    @if(!auth()->user()->is_approved)
        <div class="backdrop-blur-xl bg-amber-500/[0.08] border border-amber-500/20 rounded-2xl p-5 flex items-start gap-4 animate-fade-up">
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <div>
                <h4 class="text-amber-300 font-semibold">Account Pending Approval</h4>
                <p class="text-amber-400/70 text-sm mt-1">Complete your profile while waiting. Admin will review and approve your account.</p>
            </div>
        </div>
    @endif

    {{-- Profile Completion Progress --}}
    @php
        $steps = 0;
        $total = 4;
        if($profile->bio) $steps++;
        if($profile->hourly_rate > 0) $steps++;
        if(count($userSkillIds) > 0) $steps++;
        if($profile->profile_photo) $steps++;
        $percent = ($steps / $total) * 100;
    @endphp
    <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-5 animate-fade-up">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-slate-300">Profile Completion</span>
            <span class="text-sm font-bold {{ $percent == 100 ? 'text-emerald-400' : 'text-indigo-400' }}">{{ (int)$percent }}%</span>
        </div>
        <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-500 {{ $percent == 100 ? 'bg-gradient-to-r from-emerald-500 to-emerald-400' : 'bg-gradient-to-r from-indigo-500 to-purple-500' }}" style="width: {{ $percent }}%"></div>
        </div>
        <p class="text-xs text-slate-500 mt-2">{{ $steps }}/{{ $total }} steps completed</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT: Photo Upload --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Profile Photo Card --}}
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6 text-center">
                <h3 class="text-base font-semibold text-white mb-4">Profile Photo</h3>

                {{-- Photo Preview --}}
                <div class="relative w-32 h-32 mx-auto mb-4">
                    @if($profile->profile_photo)
                        <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="Profile Photo" class="w-32 h-32 rounded-2xl object-cover border-2 border-white/10">
                        <form method="POST" action="{{ route('provider.profile.photo.delete') }}" class="absolute -top-2 -right-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-7 h-7 rounded-full bg-rose-500/80 hover:bg-rose-500 flex items-center justify-center transition-colors shadow-lg" title="Remove photo">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    @else
                        <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 border-2 border-dashed border-white/10 flex items-center justify-center">
                            <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('provider.profile.photo') }}" enctype="multipart/form-data">
                    @csrf
                    <label class="block cursor-pointer">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/[0.06] border border-white/[0.1] text-slate-300 text-sm font-medium hover:bg-white/[0.1] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Choose Photo
                        </span>
                        <input type="file" name="profile_photo" class="hidden" accept="image/*" onchange="this.form.submit()">
                    </label>
                    <p class="text-xs text-slate-500 mt-2">JPG, PNG, WEBP • Max 2MB</p>
                </form>
                @error('profile_photo')
                    <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Quick Info --}}
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6">
                <h3 class="text-base font-semibold text-white mb-4">Account Info</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Name</span>
                        <span class="text-white font-medium">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Email</span>
                        <span class="text-white font-medium">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Status</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ auth()->user()->is_approved ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' }}">
                            {{ auth()->user()->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Joined</span>
                        <span class="text-white font-medium">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Profile Form --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Profile Details Form --}}
            <form method="POST" action="{{ route('provider.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6 space-y-5">
                    <h3 class="text-base font-semibold text-white">Profile Details</h3>

                    {{-- Bio --}}
                    <div>
                        <label for="bio" class="block text-sm font-medium text-slate-300 mb-1.5">Bio / About You</label>
                        <textarea name="bio" id="bio" rows="4"
                            class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300 resize-none"
                            placeholder="Tell customers about yourself, your experience, and what services you offer...">{{ old('bio', $profile->bio) }}</textarea>
                        @error('bio')
                            <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- City & Area --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-slate-300 mb-1.5">City</label>
                            <select name="city" id="city"
                                class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300 appearance-none"
                                style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1.25em 1.25em;">
                                <option value="" class="bg-slate-900">Select city</option>
                                @foreach(['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Sylhet', 'Barisal', 'Rangpur', 'Mymensingh', 'Comilla', 'Gazipur'] as $city)
                                    <option value="{{ $city }}" class="bg-slate-900" {{ old('city', $user->city) === $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="area" class="block text-sm font-medium text-slate-300 mb-1.5">Area / Locality</label>
                            <input type="text" name="area" id="area" value="{{ old('area', $profile->area) }}"
                                class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300"
                                placeholder="e.g. Mirpur, Dhanmondi, Uttara">
                        </div>
                    </div>

                    {{-- Phone & Hourly Rate --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-300 mb-1.5">Phone Number</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300"
                                placeholder="01XXXXXXXXX">
                        </div>
                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-slate-300 mb-1.5">Hourly Rate (৳ BDT)</label>
                            <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $profile->hourly_rate) }}" min="0" step="50"
                                class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300"
                                placeholder="500">
                            @error('hourly_rate')
                                <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Experience --}}
                    <div class="w-full sm:w-1/2">
                        <label for="experience_years" class="block text-sm font-medium text-slate-300 mb-1.5">Years of Experience</label>
                        <input type="number" name="experience_years" id="experience_years" value="{{ old('experience_years', $profile->experience_years) }}" min="0" max="50"
                            class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300"
                            placeholder="3">
                    </div>
                </div>

                {{-- Skills Selection --}}
                <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6 mt-6">
                    <h3 class="text-base font-semibold text-white mb-1">Your Skills</h3>
                    <p class="text-sm text-slate-400 mb-4">Select all services you can provide</p>
                    @error('skills')
                        <p class="mb-3 text-sm text-rose-400">{{ $message }}</p>
                    @enderror

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($skills as $skill)
                            <label class="relative cursor-pointer group">
                                <input type="checkbox" name="skills[]" value="{{ $skill->id }}" class="peer sr-only"
                                    {{ in_array($skill->id, old('skills', $userSkillIds)) ? 'checked' : '' }}>
                                <div class="p-3 rounded-xl border-2 border-white/[0.06] bg-white/[0.02] transition-all duration-300 peer-checked:border-indigo-500/50 peer-checked:bg-indigo-500/10 peer-checked:shadow-lg peer-checked:shadow-indigo-500/5 group-hover:border-white/15 group-hover:bg-white/[0.04]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/[0.05] flex items-center justify-center shrink-0">
                                            @switch($skill->icon)
                                                @case('bolt')
                                                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                    @break
                                                @case('wrench')
                                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    @break
                                                @case('snowflake')
                                                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18m0-18l4 4m-4-4L8 7m4 14l4-4m-4 4l-4-4M3 12h18M3 12l4-4m-4 4l4 4m14-4l-4-4m4 4l-4 4"/></svg>
                                                    @break
                                                @case('hammer')
                                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                    @break
                                                @case('book')
                                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                    @break
                                                @default
                                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            @endswitch
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-slate-300 block">{{ $skill->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="mt-6">
                    <button type="submit"
                        class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0">
                        Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Availability Section --}}
    <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-semibold text-white">Availability Schedule</h3>
                <p class="text-sm text-slate-400 mt-0.5">Set your available days and time slots</p>
            </div>
        </div>

        <form method="POST" action="{{ route('provider.profile.availability') }}" id="availability-form">
            @csrf
            @method('PUT')

            <div id="availability-slots" class="space-y-3">
                @forelse($availabilities as $index => $av)
                    <div class="availability-row flex flex-wrap items-center gap-3 p-4 rounded-xl bg-white/[0.03] border border-white/[0.06]">
                        <select name="availabilities[{{ $index }}][day_of_week]" class="px-3 py-2.5 rounded-lg bg-white/[0.05] border border-white/[0.1] text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all appearance-none" style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.25em 1.25em; padding-right: 2rem;">
                            @foreach(['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday'] as $day)
                                <option value="{{ $day }}" class="bg-slate-900" {{ $av->day_of_week === $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                        <div class="flex items-center gap-2">
                            <input type="time" name="availabilities[{{ $index }}][start_time]" value="{{ \Carbon\Carbon::parse($av->start_time)->format('H:i') }}" class="px-3 py-2.5 rounded-lg bg-white/[0.05] border border-white/[0.1] text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <span class="text-slate-500 text-sm">to</span>
                            <input type="time" name="availabilities[{{ $index }}][end_time]" value="{{ \Carbon\Carbon::parse($av->end_time)->format('H:i') }}" class="px-3 py-2.5 rounded-lg bg-white/[0.05] border border-white/[0.1] text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all">
                        </div>
                        <button type="button" onclick="this.closest('.availability-row').remove()" class="p-2 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                @empty
                    <p id="no-slots-msg" class="text-sm text-slate-500 text-center py-4">No availability set. Click "Add Slot" to begin.</p>
                @endforelse
            </div>

            <div class="flex items-center gap-3 mt-4">
                <button type="button" onclick="addAvailabilitySlot()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/[0.06] border border-white/[0.1] text-slate-300 text-sm font-medium hover:bg-white/[0.1] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Slot
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-all hover:-translate-y-0.5">
                    Save Availability
                </button>
            </div>
        </form>
    </div>

    {{-- View Public Profile Link --}}
    @if(auth()->user()->is_approved)
        <div class="text-center">
            <a href="{{ route('provider.profile.show', auth()->user()->id) }}" target="_blank"
                class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View your public profile
            </a>
        </div>
    @endif
</div>

<script>
    let slotIndex = {{ $availabilities->count() }};

    function addAvailabilitySlot() {
        const noMsg = document.getElementById('no-slots-msg');
        if (noMsg) noMsg.remove();

        const container = document.getElementById('availability-slots');
        const html = `
            <div class="availability-row flex flex-wrap items-center gap-3 p-4 rounded-xl bg-white/[0.03] border border-white/[0.06] animate-fade-up">
                <select name="availabilities[${slotIndex}][day_of_week]" class="px-3 py-2.5 rounded-lg bg-white/[0.05] border border-white/[0.1] text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all appearance-none" style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.25em 1.25em; padding-right: 2rem;">
                    <option value="Saturday" class="bg-slate-900">Saturday</option>
                    <option value="Sunday" class="bg-slate-900">Sunday</option>
                    <option value="Monday" class="bg-slate-900">Monday</option>
                    <option value="Tuesday" class="bg-slate-900">Tuesday</option>
                    <option value="Wednesday" class="bg-slate-900">Wednesday</option>
                    <option value="Thursday" class="bg-slate-900">Thursday</option>
                    <option value="Friday" class="bg-slate-900">Friday</option>
                </select>
                <div class="flex items-center gap-2">
                    <input type="time" name="availabilities[${slotIndex}][start_time]" value="09:00" class="px-3 py-2.5 rounded-lg bg-white/[0.05] border border-white/[0.1] text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all">
                    <span class="text-slate-500 text-sm">to</span>
                    <input type="time" name="availabilities[${slotIndex}][end_time]" value="17:00" class="px-3 py-2.5 rounded-lg bg-white/[0.05] border border-white/[0.1] text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all">
                </div>
                <button type="button" onclick="this.closest('.availability-row').remove()" class="p-2 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        slotIndex++;
    }
</script>
@endsection
