@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Welcome Section --}}
    <div class="animate-fade-up">
        <h2 class="text-2xl font-bold text-white">Admin Dashboard 🎛️</h2>
        <p class="text-slate-400 mt-1">Platform overview and management</p>
    </div>

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

    {{-- Pending Provider Approvals --}}
    <div>
        <h3 class="text-lg font-semibold text-white mb-4">Pending Provider Approvals</h3>
        <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl overflow-hidden">
            @if($pending_providers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/[0.06]">
                                <th class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider px-6 py-4">Provider</th>
                                <th class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider px-6 py-4">Email</th>
                                <th class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider px-6 py-4">City</th>
                                <th class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider px-6 py-4">Registered</th>
                                <th class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.04]">
                            @foreach($pending_providers as $provider)
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-xs font-bold text-white">
                                                {{ strtoupper(substr($provider->name, 0, 2)) }}
                                            </div>
                                            <span class="text-sm font-medium text-white">{{ $provider->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-400">{{ $provider->email }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-400">{{ $provider->city ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ $provider->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-500/10 text-amber-400">
                                            Pending
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-10 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-slate-400 font-medium">No pending approvals</p>
                    <p class="text-sm text-slate-500 mt-1">All providers have been reviewed</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Users --}}
    <div>
        <h3 class="text-lg font-semibold text-white mb-4">Recent Users</h3>
        <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/[0.06]">
                            <th class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider px-6 py-4">User</th>
                            <th class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider px-6 py-4">Email</th>
                            <th class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider px-6 py-4">Role</th>
                            <th class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider px-6 py-4">City</th>
                            <th class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider px-6 py-4">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04]">
                        @foreach($recent_users as $user)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br
                                            {{ $user->role === 'admin' ? 'from-rose-500 to-rose-600' : ($user->role === 'provider' ? 'from-emerald-500 to-emerald-600' : 'from-blue-500 to-blue-600') }}
                                            flex items-center justify-center text-xs font-bold text-white">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-medium text-white">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-400">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                                        {{ $user->role === 'admin' ? 'bg-rose-500/10 text-rose-400' : ($user->role === 'provider' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-blue-500/10 text-blue-400') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-400">{{ $user->city ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
