<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bkash-pink {
            background-color: #e2136e;
        }
        .bkash-pink-text {
            color: #e2136e;
        }
        .bkash-border:focus {
            border-color: #e2136e;
            outline: none;
        }
    </style>
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl overflow-hidden shadow-2xl animate-fade-in">
        
        {{-- bKash Header --}}
        <div class="bkash-pink p-6 text-center text-white relative">
            <div class="flex items-center justify-center gap-2 mb-2">
                {{-- Mock bKash SVG Logo --}}
                <svg class="w-12 h-12 fill-white" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="45" fill="none" stroke="white" stroke-width="4"/>
                    <path d="M30 40h40v8H30zm0 15h40v8H30z"/>
                    <path d="M42 30h16v40H42z"/>
                </svg>
                <span class="text-3xl font-black italic tracking-wider">bKash</span>
            </div>
            <p class="text-xs text-white/80 font-medium">Merchant Payment Checkout</p>
        </div>

        {{-- Booking / Invoice Summary --}}
        <div class="bg-slate-50 p-5 border-b border-slate-100 text-sm">
            <div class="flex justify-between items-center mb-2">
                <span class="text-slate-500">Provider</span>
                <span class="font-bold text-slate-800">{{ $booking->provider->name }}</span>
            </div>
            <div class="flex justify-between items-center mb-2">
                <span class="text-slate-500">Service</span>
                <span class="text-slate-700 font-medium">{{ $booking->provider->skills->pluck('name')->join(', ') }}</span>
            </div>
            <div class="flex justify-between items-center mb-3">
                <span class="text-slate-500">Duration</span>
                <span class="text-slate-700 font-medium">{{ $booking->duration }} {{ Str::plural('Hour', $booking->duration) }}</span>
            </div>
            <div class="border-t border-slate-200/60 pt-3 flex justify-between items-center">
                <span class="font-semibold text-slate-800">Total Amount</span>
                <span class="bkash-pink-text font-black text-xl">৳{{ number_format($booking->total_price) }}</span>
            </div>
        </div>

        {{-- Payment Form --}}
        <div class="p-6">
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-100 text-rose-600 p-3.5 rounded-xl text-xs mb-5">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('payment.process', $booking->id) }}" method="POST" class="space-y-5">
                @csrf
                
                {{-- Wallet Number --}}
                <div>
                    <label for="wallet_number" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">bKash Account Number</label>
                    <input type="text" name="wallet_number" id="wallet_number" maxlength="11" placeholder="e.g. 017XXXXXXXX" value="{{ old('wallet_number') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#e2136e] focus:ring-1 focus:ring-[#e2136e] transition-all text-center font-mono text-lg tracking-widest" required>
                    <span class="text-[10px] text-slate-400 block mt-1">Enter 11-digit bKash wallet number.</span>
                </div>

                {{-- PIN --}}
                <div>
                    <label for="pin" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Enter 4-Digit PIN</label>
                    <input type="password" name="pin" id="pin" maxlength="4" placeholder="••••" autocomplete="off"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#e2136e] focus:ring-1 focus:ring-[#e2136e] transition-all text-center font-mono text-lg tracking-widest" required>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('customer.bookings.index') }}" class="flex-1 text-center py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 text-xs font-bold transition-all">
                        CLOSE
                    </a>
                    <button type="submit" class="flex-1 py-3.5 rounded-xl bkash-pink hover:bg-[#d01063] text-white text-xs font-bold transition-all shadow-lg shadow-pink-500/10">
                        CONFIRM
                    </button>
                </div>
            </form>
        </div>

        {{-- Footer details --}}
        <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
            <span class="text-[10px] text-slate-400 font-medium">bKash Customer Support 16247</span>
        </div>
    </div>
</body>
</html>
