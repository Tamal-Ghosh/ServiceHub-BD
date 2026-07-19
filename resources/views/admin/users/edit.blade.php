@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-up">

    {{-- Breadcrumb / Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Edit User Details 👤</h2>
            <p class="text-slate-400 mt-1">Modify account settings and roles for <span class="text-indigo-400 font-semibold">{{ $user->name }}</span></p>
        </div>
        <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-slate-300 bg-white/[0.05] hover:bg-white/[0.08] border border-white/[0.06] text-sm font-semibold transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    {{-- Glassmorphism Card --}}
    <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-3xl p-8 shadow-2xl shadow-black/20"
         x-data="{ role: '{{ old('role', $user->role) }}' }">

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/[0.03] border border-white/[0.08] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300">
                </div>
                @error('name')
                    <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/[0.03] border border-white/[0.08] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300">
                </div>
                @error('email')
                    <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-300 mb-1.5">Phone Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/[0.03] border border-white/[0.08] text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300">
                    </div>
                    @error('phone')
                        <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- City --}}
                <div>
                    <label for="city" class="block text-sm font-medium text-slate-300 mb-1.5">City</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <select name="city" id="city"
                            class="w-full pl-12 pr-4 py-3 rounded-xl bg-slate-900 border border-white/[0.08] text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300 appearance-none">
                            <option value="" class="bg-slate-900">Select City</option>
                            @foreach(['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Sylhet', 'Barisal', 'Rangpur', 'Mymensingh', 'Comilla', 'Gazipur'] as $cityName)
                                <option value="{{ $cityName }}" class="bg-slate-900" {{ old('city', $user->city) === $cityName ? 'selected' : '' }}>{{ $cityName }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('city')
                        <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Role Selection --}}
            <div>
                <label for="role" class="block text-sm font-medium text-slate-300 mb-1.5">User Role</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <select name="role" id="role" x-model="role"
                        class="w-full pl-12 pr-4 py-3 rounded-xl bg-slate-900 border border-white/[0.08] text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300 appearance-none">
                        <option value="customer" class="bg-slate-900">Customer</option>
                        <option value="provider" class="bg-slate-900">Provider</option>
                        <option value="admin" class="bg-slate-900">Admin</option>
                    </select>
                </div>
                @error('role')
                    <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Approval Status (Only shown if Provider) --}}
            <div x-show="role === 'provider'" class="p-4 rounded-2xl bg-white/[0.02] border border-white/[0.05] space-y-3" style="display: none;">
                <label class="block text-sm font-semibold text-slate-300">Approval Status</label>
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_approved" value="0">
                        <input type="checkbox" name="is_approved" value="1" {{ old('is_approved', $user->is_approved) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-sm text-slate-400">Account Approved & Active</span>
                    </label>
                </div>
            </div>

            {{-- Provider Skills (Only shown if Provider) --}}
            <div x-show="role === 'provider'" class="p-4 rounded-2xl bg-white/[0.02] border border-white/[0.05] space-y-4" style="display: none;">
                <div>
                    <h3 class="text-sm font-semibold text-slate-300">Skills / Service Categories</h3>
                    <p class="text-xs text-slate-500 mt-1">Select the categories this provider offers.</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($skills as $skill)
                        <label class="flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] border border-white/[0.05] hover:bg-white/[0.05] hover:border-white/[0.08] cursor-pointer transition-all">
                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                {{ in_array($skill->id, old('skills', $user->skills->pluck('id')->toArray())) ? 'checked' : '' }}
                                class="rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-500/50 focus:ring-offset-0">
                            <span class="text-sm text-slate-300">{{ $skill->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('skills')
                    <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit / Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="px-5 py-3 rounded-xl text-slate-300 bg-white/[0.05] hover:bg-white/[0.08] border border-white/[0.06] text-sm font-semibold transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 text-sm font-bold shadow-lg shadow-indigo-600/20 transition-all hover:-translate-y-0.5">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
