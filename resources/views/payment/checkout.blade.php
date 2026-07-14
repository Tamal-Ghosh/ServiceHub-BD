<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Checkout — ServiceHub BD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center p-4">
    
    {{-- Animated Orbs Background --}}
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-indigo-950/40 to-slate-950"></div>
        <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-md w-full backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] rounded-3xl overflow-hidden shadow-2xl shadow-black/40 animate-fade-in text-white">
        
        {{-- Header --}}
        <div class="p-6 text-center border-b border-white/[0.08] bg-white/[0.02]">
            <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">Payment Checkout</h1>
            <p class="text-xs text-slate-400 mt-1">Complete your service booking payment securely</p>
        </div>

        {{-- Invoice Summary Card --}}
        <div class="bg-white/[0.01] p-6 border-b border-white/[0.06] text-sm space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-slate-400">Provider</span>
                <span class="font-semibold text-white">{{ $booking->provider->name }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-400">Service</span>
                <span class="text-slate-300 font-medium">{{ $booking->provider->skills->pluck('name')->join(', ') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-400">Duration</span>
                <span class="text-slate-300 font-medium">{{ $booking->duration }} {{ Str::plural('Hour', $booking->duration) }}</span>
            </div>
            <div class="border-t border-white/[0.08] pt-4 flex justify-between items-center">
                <span class="font-bold text-slate-200">Total Price</span>
                <span class="text-indigo-400 font-extrabold text-2xl">৳{{ number_format($booking->total_price) }}</span>
            </div>
        </div>

        {{-- Gateway & Actions --}}
        <div class="p-6 space-y-6">
            
            {{-- Gateway Information Badge --}}
            <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/[0.06] text-center space-y-4">
                <div class="flex justify-center items-center gap-2">
                    <span class="text-xs text-slate-400">Secured & Authorized by</span>
                    <span class="text-base font-extrabold text-red-500 tracking-tighter">SSL</span>
                    <span class="text-base font-bold text-slate-300 tracking-tight">Commerz</span>
                </div>
                <div class="flex flex-wrap justify-center gap-2 text-[10px]">
                    <span class="px-2.5 py-1 rounded-full bg-white/[0.04] text-slate-300 border border-white/[0.02]">Cards</span>
                    <span class="px-2.5 py-1 rounded-full bg-white/[0.04] text-slate-300 border border-white/[0.02]">Mobile Banking (bKash/Nagad)</span>
                    <span class="px-2.5 py-1 rounded-full bg-white/[0.04] text-slate-300 border border-white/[0.02]">Net Banking</span>
                </div>
            </div>

            {{-- error display --}}
            @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-3.5 rounded-2xl text-xs">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Checkout Action Form --}}
            <form action="{{ route('payment.sslcommerz.initiate', $booking->id) }}" method="POST">
                @csrf
                <div class="flex items-center gap-3">
                    <a href="{{ route('customer.bookings.index') }}" class="flex-1 text-center py-4 rounded-xl bg-white/[0.05] hover:bg-white/[0.1] text-slate-300 text-xs font-bold transition-all border border-white/[0.04]">
                        CANCEL
                    </a>
                    <button type="submit" class="flex-1 py-4 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white text-xs font-bold transition-all shadow-lg shadow-red-600/25">
                        PAY VIA SSLCOMMERZ
                    </button>
                </div>
            </form>

        </div>

    </div>

</body>
</html>
