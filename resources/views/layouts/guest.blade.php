<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ServiceHub BD') — Local Service Booking</title>
    <meta name="description" content="Find and book trusted local service providers in Bangladesh — electricians, plumbers, AC technicians, tutors and more.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
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

    {{-- Theme Toggle --}}
    <div class="absolute top-6 right-6 z-50">
        <button onclick="toggleTheme()" class="w-10 h-10 rounded-xl bg-white/[0.06] border border-white/[0.1] hover:bg-white/[0.1] text-slate-300 hover:text-white flex items-center justify-center transition-all shadow-lg" title="Toggle Theme">
            <svg class="sunIcon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <svg class="moonIcon w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>
    </div>

    {{-- Animated gradient background --}}
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-indigo-950/50 to-slate-950"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(99,102,241,0.15),_transparent_50%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,_rgba(139,92,246,0.1),_transparent_50%)]"></div>

        {{-- Floating orbs --}}
        <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-float-delayed"></div>
        <div class="absolute top-1/2 right-1/3 w-64 h-64 bg-blue-500/8 rounded-full blur-3xl animate-float"></div>

        {{-- Grid pattern overlay --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, rgba(255,255,255,0.8) 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    <div class="relative min-h-screen flex flex-col items-center justify-center px-4 py-8">
        @yield('content')
        @if (isset($slot))
            {{ $slot }}
        @endif
    </div>

</body>
</html>
