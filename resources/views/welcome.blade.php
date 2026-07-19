<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ServiceHub BD — Find Trusted Local Service Providers</title>
    <meta name="description" content="Find and book trusted local service providers in Bangladesh — electricians, plumbers, AC technicians, tutors and more.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.add('light');
        } else {
            document.documentElement.classList.remove('light');
        }
    </script>
</head>
<body class="min-h-screen bg-slate-950 font-sans antialiased text-white overflow-x-hidden">

    {{-- Background decorations --}}
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-indigo-950/20 to-slate-950"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(99,102,241,0.1),_transparent_50%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,_rgba(139,92,246,0.08),_transparent_50%)]"></div>
        <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle, rgba(255,255,255,0.8) 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    {{-- Header --}}
    <header class="sticky top-0 z-40 backdrop-blur-xl bg-slate-950/80 border-b border-white/[0.06]">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:shadow-indigo-500/40 transition-shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-lg font-bold bg-gradient-to-r from-white to-indigo-200 bg-clip-text text-transparent">ServiceHub</span>
                    <span class="text-xs text-indigo-400 block -mt-0.5">BD</span>
                </div>
            </a>
            <nav class="flex items-center gap-4">
                {{-- Theme Toggle --}}
                <button id="themeToggleBtn" onclick="toggleTheme()" class="w-10 h-10 rounded-xl bg-white/[0.06] border border-white/[0.1] hover:bg-white/[0.1] text-slate-300 hover:text-white flex items-center justify-center transition-all" title="Toggle Theme">
                    <!-- Sun icon (shown in dark mode) -->
                    <svg id="sunIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <!-- Moon icon (shown in light mode) -->
                    <svg id="moonIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
                @auth
                    @php
                        $dashboardUrl = match(auth()->user()->role) {
                            'admin' => route('admin.dashboard'),
                            'provider' => auth()->user()->is_approved ? route('provider.dashboard') : route('provider.pending'),
                            default => route('customer.dashboard'),
                        };
                    @endphp
                    <a href="{{ $dashboardUrl }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-all shadow-lg shadow-indigo-500/25">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2.5 text-slate-300 hover:text-white text-sm font-medium transition-colors">
                        Log In
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-white/[0.06] border border-white/[0.1] text-slate-300 hover:bg-white/[0.1] hover:text-white text-sm font-medium transition-all">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    {{-- Hero Section --}}
    <section class="max-w-7xl mx-auto px-6 pt-16 pb-12 text-center relative">
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl -z-10"></div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight text-white mb-6 animate-fade-up">
            Find Trusted Local <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Service Providers</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed animate-fade-up" style="animation-delay: 0.1s">
            Electricians, plumbers, AC technicians, appliance repairers, tutors, and more. Certified and reviewed professionals at your doorstep.
        </p>

        {{-- AI Assistant Card --}}
        <div class="max-w-4xl mx-auto mb-6 backdrop-blur-xl bg-gradient-to-r from-indigo-500/10 to-purple-500/10 border border-indigo-500/20 p-5 rounded-3xl text-left shadow-2xl relative overflow-hidden animate-fade-up" style="animation-delay: 0.15s">
            <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white flex items-center gap-1.5">
                            AI Assistant
                            <span class="px-1.5 py-0.5 rounded bg-indigo-500/20 text-[9px] text-indigo-300 font-semibold tracking-wider uppercase">Beta</span>
                        </h4>
                        <p class="text-xs text-slate-200 mt-0.5 font-medium">Describe your issue in plain words and let AI match you with the right helper category!</p>
                    </div>
                </div>
                <button type="button" onclick="toggleAiAssistant()" class="shrink-0 text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-1">
                    <span id="aiToggleText">Try AI Assistant</span>
                    <svg id="aiToggleIcon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
            
            {{-- Collapsible AI Chatbot Body --}}
            <div id="aiAssistantBody" class="mt-4 pt-4 border-t border-white/[0.06] hidden">
                <div class="space-y-4">
                    {{-- Chat Messages Area --}}
                    <div id="aiChatHistory" class="max-h-[280px] overflow-y-auto space-y-3 p-4 rounded-2xl bg-slate-950/40 border border-white/5 scrollbar-thin flex flex-col">
                        <div class="flex items-start gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-500/10 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div class="p-3 rounded-2xl rounded-tl-none bg-white/[0.04] border border-white/[0.06] text-xs text-slate-300 max-w-[85%] leading-relaxed">
                                Hello! I am your ServiceHub AI Assistant. Feel free to chat with me normally or describe a home service issue you need help with!
                            </div>
                        </div>
                    </div>

                    {{-- Chat Input Row --}}
                    <div class="flex gap-2">
                        <input type="text" id="aiProblemInput" placeholder="Ask anything or describe a service problem..." onkeydown="if(event.key === 'Enter') askGemini()"
                            class="flex-1 px-4 py-3 rounded-xl bg-slate-950/50 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500 transition-all">
                        <button type="button" onclick="askGemini()" id="aiSendBtn" class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-md shadow-indigo-600/20 transition-all flex items-center gap-1.5 shrink-0">
                            <span id="aiBtnText">Send</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter / Search Form --}}
        <div class="max-w-4xl mx-auto backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] p-6 rounded-3xl shadow-2xl shadow-black/30 animate-fade-up" style="animation-delay: 0.2s">
            <form method="GET" action="/" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                {{-- Skill Filter --}}
                <div class="relative">
                    <label for="skill" class="block text-left text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Service Skill</label>
                    <select name="skill" id="skill" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/[0.1] text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 appearance-none transition-all" style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1.25em 1.25em; padding-right: 2.5rem;">
                        <option value="" class="bg-slate-900 text-slate-400">All Services</option>
                        @foreach($skills as $sk)
                            @php
                                $reqSkill = request('skill');
                                $skillName = is_numeric($reqSkill) ? ($skills->find($reqSkill)->name ?? '') : $reqSkill;
                            @endphp
                            <option value="{{ $sk->name }}" class="bg-slate-900 text-white" {{ $skillName === $sk->name ? 'selected' : '' }}>
                                {{ $sk->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- City Filter --}}
                <div class="relative">
                    <label for="city" class="block text-left text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">City</label>
                    <select name="city" id="city" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/[0.1] text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 appearance-none transition-all" style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1.25em 1.25em; padding-right: 2.5rem;">
                        <option value="" class="bg-slate-900 text-slate-400">All Cities</option>
                        @foreach($cities as $ct)
                            <option value="{{ $ct }}" class="bg-slate-900 text-white" {{ request('city') === $ct ? 'selected' : '' }}>
                                {{ $ct }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Rating Filter --}}
                <div class="relative">
                    <label for="rating" class="block text-left text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Minimum Rating</label>
                    <select name="rating" id="rating" class="w-full px-4 py-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 appearance-none transition-all" style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1.25em 1.25em; padding-right: 2.5rem;">
                        <option value="" class="bg-slate-900 text-slate-400">All Ratings</option>
                        <option value="5" class="bg-slate-900" {{ request('rating') == '5' ? 'selected' : '' }}>★ 5.0 only</option>
                        <option value="4.5" class="bg-slate-900" {{ request('rating') == '4.5' ? 'selected' : '' }}>★ 4.5 & up</option>
                        <option value="4" class="bg-slate-900" {{ request('rating') == '4' ? 'selected' : '' }}>★ 4.0 & up</option>
                        <option value="3" class="bg-slate-900" {{ request('rating') == '3' ? 'selected' : '' }}>★ 3.0 & up</option>
                    </select>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-3 px-6 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold shadow-lg shadow-indigo-500/25 transition-all hover:-translate-y-0.5">
                        Search
                    </button>
                    @if(request()->anyFilled(['skill', 'city', 'rating']))
                        <a href="/" class="p-3 rounded-xl bg-white/[0.05] border border-white/[0.1] text-slate-400 hover:text-white hover:bg-white/[0.1] transition-colors" title="Clear filters">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </section>

    {{-- Providers Section --}}
    <main class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl md:text-2xl font-bold text-white">Available Providers ({{ $providers->count() }})</h2>
        </div>

        @if($providers->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($providers as $provider)
                    
                    {{-- Provider Card --}}
                    <div class="group backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-3xl p-6 flex flex-col justify-between hover:bg-white/[0.06] hover:border-white/[0.1] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-indigo-500/5">
                        
                        <div>
                            {{-- Header: Avatar & Info --}}
                            <div class="flex items-start gap-4">
                                <div class="shrink-0">
                                    @if($provider->profile_photo_url)
                                        <img src="{{ $provider->profile_photo_url }}" alt="{{ $provider->name }}" class="w-16 h-16 rounded-2xl object-cover border border-white/10 shadow-lg">
                                    @else
                                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xl font-bold text-white shadow-lg">
                                            {{ $provider->initials }}
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-lg font-bold text-white truncate">{{ $provider->name }}</h3>
                                    <p class="text-sm text-slate-400 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $provider->city }}{{ $provider->providerProfile && $provider->providerProfile->area ? ', ' . $provider->providerProfile->area : '' }}
                                    </p>
                                    
                                    {{-- Rating --}}
                                    <div class="flex items-center gap-1.5 mt-1.5">
                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="text-sm font-semibold text-white">{{ $provider->average_rating }}</span>
                                        <span class="text-xs text-slate-500">({{ $provider->review_count }} {{ Str::plural('review', $provider->review_count) }})</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Skills --}}
                            @if($provider->skills->count() > 0)
                                <div class="flex flex-wrap gap-1.5 mt-5">
                                    @foreach($provider->skills as $skill)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                            {{ $skill->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Bio Snippet --}}
                            @if($provider->providerProfile && $provider->providerProfile->bio)
                                <p class="text-sm text-slate-400 line-clamp-2 mt-4 leading-relaxed">
                                    {{ $provider->providerProfile->bio }}
                                </p>
                            @endif
                        </div>

                        {{-- Footer: Price & Link --}}
                        <div class="flex items-center justify-between border-t border-white/[0.06] pt-4 mt-6">
                            <div>
                                <p class="text-xs text-slate-500">Hourly Rate</p>
                                <p class="text-lg font-black text-emerald-400">৳{{ number_format($provider->providerProfile->hourly_rate) }}<span class="text-xs text-slate-400 font-normal">/hr</span></p>
                            </div>
                            <a href="{{ route('provider.profile.show', $provider->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white/[0.06] border border-white/[0.1] text-slate-300 text-sm font-semibold hover:bg-white/[0.1] hover:text-white transition-all group-hover:border-indigo-500/50">
                                View Profile
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="backdrop-blur-xl bg-white/[0.03] border border-white/[0.06] rounded-3xl p-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No Service Providers Found</h3>
                <p class="text-slate-400 max-w-md mx-auto">We couldn't find any approved service providers matching your current search filters. Try widening your criteria or clearing filters.</p>
                <a href="/" class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 rounded-xl bg-white/[0.06] border border-white/[0.1] text-slate-300 hover:bg-white/[0.1] hover:text-white text-sm font-semibold transition-all">
                    Reset All Filters
                </a>
            </div>
        @endif
    </main>

    {{-- Footer --}}
    <footer class="max-w-7xl mx-auto px-6 py-12 border-t border-white/[0.06] mt-20 text-center text-slate-500 text-sm">
        <p>&copy; {{ date('Y') }} ServiceHub BD. All rights reserved.</p>
    </footer>

    <script>
        function toggleAiAssistant() {
            const body = document.getElementById('aiAssistantBody');
            const toggleText = document.getElementById('aiToggleText');
            const toggleIcon = document.getElementById('aiToggleIcon');
            
            if (body.classList.contains('hidden')) {
                body.classList.remove('hidden');
                toggleText.textContent = 'Hide AI Assistant';
                toggleIcon.classList.add('rotate-180');
            } else {
                body.classList.add('hidden');
                toggleText.textContent = 'Try AI Assistant';
                toggleIcon.classList.remove('rotate-180');
            }
        }

        let suggestedSkill = '';

        async function askGemini() {
            const inputField = document.getElementById('aiProblemInput');
            const problem = inputField.value.trim();
            const btnText = document.getElementById('aiBtnText');
            const chatHistory = document.getElementById('aiChatHistory');

            if (problem.length < 2) return;

            // Clear input field
            inputField.value = '';

            // Escape html to prevent XSS
            const escapedProblem = escapeHtml(problem);

            // Append User Message to Chat History
            const userMsgHtml = `
                <div class="flex items-start gap-2.5 justify-end mb-3">
                    <div class="p-3 rounded-2xl rounded-tr-none bg-indigo-600/25 border border-indigo-500/20 text-xs text-white max-w-[85%] leading-relaxed text-left">
                        ${escapedProblem}
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-indigo-600/20 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 text-indigo-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    </div>
                </div>
            `;
            chatHistory.insertAdjacentHTML('beforeend', userMsgHtml);
            chatHistory.scrollTop = chatHistory.scrollHeight;

            // Append Typing Indicator
            const typingId = 'typing_' + Date.now();
            const typingHtml = `
                <div id="${typingId}" class="flex items-start gap-2.5 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="p-3 rounded-2xl rounded-tl-none bg-white/[0.04] border border-white/[0.06] text-xs text-slate-400 max-w-[85%] italic">
                        AI is typing...
                    </div>
                </div>
            `;
            chatHistory.insertAdjacentHTML('beforeend', typingHtml);
            chatHistory.scrollTop = chatHistory.scrollHeight;

            btnText.textContent = '...';

            try {
                const response = await fetch('{{ route("ai.suggest") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ problem: problem })
                });

                let data;
                try {
                    data = await response.json();
                } catch (jsonErr) {
                    const rawText = await response.text();
                    console.error("Failed to parse JSON response:", rawText);
                    throw new Error("Server returned an invalid format: " + (rawText.substring(0, 150) || "Empty Response"));
                }
                
                // Remove typing indicator
                const typingElem = document.getElementById(typingId);
                if (typingElem) typingElem.remove();

                if (data.success) {
                    suggestedSkill = data.suggested_service;
                    const actionBtn = suggestedSkill ? `
                        <div class="mt-2.5">
                            <button type="button" onclick="applyAiSuggestion('${escapeQuotes(suggestedSkill)}')" class="px-3.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-[10px] transition-all shadow-md shadow-indigo-600/20">
                                Match with ${escapeHtml(suggestedSkill)}
                            </button>
                        </div>
                    ` : '';

                    const aiMsgHtml = `
                        <div class="flex items-start gap-2.5 mb-3 animate-fade-up">
                            <div class="w-7 h-7 rounded-lg bg-indigo-500/10 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div class="p-3 rounded-2xl rounded-tl-none bg-white/[0.04] border border-white/[0.06] text-xs text-slate-300 max-w-[85%] leading-relaxed text-left">
                                <p>${escapeHtml(data.explanation)}</p>
                                ${actionBtn}
                            </div>
                        </div>
                    `;
                    chatHistory.insertAdjacentHTML('beforeend', aiMsgHtml);
                } else {
                    const errorMsgHtml = `
                        <div class="flex items-start gap-2.5 mb-3 animate-fade-up">
                            <div class="w-7 h-7 rounded-lg bg-indigo-500/10 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div class="p-3 rounded-2xl rounded-tl-none bg-white/[0.04] border border-white/[0.06] text-xs text-slate-300 max-w-[85%] leading-relaxed text-left">
                                ${escapeHtml(data.message || 'Could not understand, please try again.')}
                            </div>
                        </div>
                    `;
                    chatHistory.insertAdjacentHTML('beforeend', errorMsgHtml);
                }
            } catch (error) {
                console.error(error);
                const typingElem = document.getElementById(typingId);
                if (typingElem) typingElem.remove();
                
                const errHtml = `
                    <div class="flex items-start gap-2.5 mb-3 animate-fade-up">
                        <div class="w-7 h-7 rounded-lg bg-red-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="p-3 rounded-2xl rounded-tl-none bg-white/[0.04] border border-white/[0.06] text-xs text-slate-300 max-w-[85%] leading-relaxed text-left">
                            Connection error: ${escapeHtml(error.message || 'Please try again.')}
                        </div>
                    </div>
                `;
                chatHistory.insertAdjacentHTML('beforeend', errHtml);
            } finally {
                btnText.textContent = 'Send';
                chatHistory.scrollTop = chatHistory.scrollHeight;
            }
        }

        function applyAiSuggestion(skill) {
            if (!skill) return;
            document.getElementById('skill').value = skill;
            document.getElementById('skill').form.submit();
        }

        function escapeHtml(str) {
            return str
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function escapeQuotes(str) {
            return str.replace(/'/g, "\\'");
        }

        }
    </script>
</body>
</html>
