<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden Access</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS (via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    {{-- Decorative Background Glows --}}
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-rose-500/10 rounded-full blur-3xl -z-10 animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl -z-10 animate-pulse" style="animation-delay: 2s;"></div>

    <div class="max-w-md w-full text-center">
        {{-- Card Container --}}
        <div class="backdrop-blur-xl bg-white/[0.02] border border-white/10 p-8 rounded-3xl shadow-2xl relative">
            {{-- Glowing Icon --}}
            <div class="w-20 h-20 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-rose-500/5">
                <svg class="w-10 h-10 text-rose-400 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            {{-- Error Code --}}
            <h1 class="text-7xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rose-400 to-rose-600 mb-2">
                403
            </h1>
            <h2 class="text-xl font-bold text-white mb-3">Access Denied</h2>
            
            <p class="text-slate-400 text-sm leading-relaxed mb-8">
                You do not have the required permissions to access this workspace resource. If you believe this is an error, please log in with correct account credentials.
            </p>

            {{-- Actions --}}
            <div class="space-y-3">
                <a href="/" class="block w-full py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white text-sm font-semibold transition-all shadow-md shadow-indigo-600/15">
                    Return to Homepage
                </a>
                <button onclick="window.history.back()" class="block w-full py-3 px-4 rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/5 text-slate-300 hover:text-white text-sm font-semibold transition-all">
                    Go Back
                </button>
            </div>
        </div>
    </div>
</body>
</html>
