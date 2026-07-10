@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-semibold flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Profile Hero Card --}}
    <div class="relative overflow-hidden backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-3xl shadow-2xl shadow-black/20">
        <div class="h-32 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 relative">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.05%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E')] opacity-50"></div>
        </div>
        <div class="px-6 md:px-8 pb-6 -mt-14 relative">
            <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4">
                <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-3xl font-black text-white shadow-xl shadow-indigo-500/30 border-4 border-slate-950 shrink-0">
                    {{ $user->initials }}
                </div>
                <div class="flex-1 pt-2 sm:pb-1">
                    <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                    <div class="flex flex-wrap items-center gap-3 mt-1.5">
                        <span class="inline-flex items-center gap-1.5 text-sm text-slate-400">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $user->email }}
                        </span>
                        @if($user->phone)
                        <span class="inline-flex items-center gap-1.5 text-sm text-slate-400">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $user->phone }}
                        </span>
                        @endif
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                            Customer
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Form --}}
    <form method="POST" action="{{ route('customer.profile.update') }}" id="profileForm" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Account Information Card --}}
        <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl overflow-hidden shadow-xl">
            <div class="px-6 md:px-8 py-5 border-b border-white/[0.06] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Account Information</h3>
                        <p class="text-xs text-slate-500">Your personal details</p>
                    </div>
                </div>
                {{-- Edit / Cancel Button --}}
                <button type="button" id="editBtn" onclick="toggleEdit()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 hover:bg-indigo-500/20 text-xs font-semibold transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <span id="editBtnText">Edit Profile</span>
                </button>
            </div>
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required disabled
                            class="profile-field w-full bg-slate-950/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                        @error('name') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required disabled
                            class="profile-field w-full bg-slate-950/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                        @error('email') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required placeholder="01XXXXXXXXX" disabled
                            class="profile-field w-full bg-slate-950/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                        @error('phone') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="city" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">City</label>
                        <select name="city" id="city" required disabled
                            class="profile-field w-full bg-slate-950/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all appearance-none disabled:opacity-60 disabled:cursor-not-allowed"
                            style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1.25em 1.25em;">
                            <option value="">Select city</option>
                            @foreach(['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Sylhet', 'Barisal', 'Rangpur', 'Mymensingh', 'Comilla', 'Gazipur'] as $cityName)
                                <option value="{{ $cityName }}" {{ old('city', $user->city) === $cityName ? 'selected' : '' }}>{{ $cityName }}</option>
                            @endforeach
                        </select>
                        @error('city') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Read-only Info Row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5 pt-5 border-t border-white/[0.06]">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Account Type</label>
                        <div class="w-full bg-slate-900/50 border border-white/5 rounded-xl px-4 py-3 text-sm text-slate-400 cursor-not-allowed">
                            {{ ucfirst($user->role) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Member Since</label>
                        <div class="w-full bg-slate-900/50 border border-white/5 rounded-xl px-4 py-3 text-sm text-slate-400 cursor-not-allowed">
                            {{ $user->created_at->format('F d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Security Card (hidden by default, shown when editing) --}}
        <div id="securityCard" class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl overflow-hidden shadow-xl hidden">
            <div class="px-6 md:px-8 py-5 border-b border-white/[0.06] flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white">Change Password</h3>
                    <p class="text-xs text-slate-500">Leave blank if you don't want to change your password</p>
                </div>
            </div>
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">New Password</label>
                        <input type="password" name="password" id="password" placeholder="Minimum 6 characters" minlength="6"
                            class="w-full bg-slate-950/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                        @error('password') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-type new password" minlength="6"
                            class="w-full bg-slate-950/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- Save / Cancel Buttons (hidden by default) --}}
        <div id="actionButtons" class="flex justify-end gap-3 hidden">
            <button type="button" onclick="toggleEdit()" class="px-5 py-3 rounded-xl text-slate-300 bg-white/[0.05] hover:bg-white/[0.08] border border-white/[0.06] text-sm font-semibold transition-all">
                Cancel
            </button>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-sm font-bold shadow-lg shadow-indigo-600/20 transition-all hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-600/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    let isEditing = false;

    function toggleEdit() {
        isEditing = !isEditing;
        const fields = document.querySelectorAll('.profile-field');
        const securityCard = document.getElementById('securityCard');
        const actionButtons = document.getElementById('actionButtons');
        const editBtn = document.getElementById('editBtn');
        const editBtnText = document.getElementById('editBtnText');

        fields.forEach(field => {
            field.disabled = !isEditing;
        });

        if (isEditing) {
            securityCard.classList.remove('hidden');
            actionButtons.classList.remove('hidden');
            editBtn.classList.add('hidden');
        } else {
            securityCard.classList.add('hidden');
            actionButtons.classList.add('hidden');
            editBtn.classList.remove('hidden');
            // Reset form values
            document.getElementById('profileForm').reset();
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        }
    }

    // If there are validation errors, auto-open edit mode
    @if($errors->any())
        toggleEdit();
    @endif
</script>
@endsection
