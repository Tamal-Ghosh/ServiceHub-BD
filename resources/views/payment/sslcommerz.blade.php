<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSLCommerz Payment Gateway</title>
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
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-4xl w-full bg-white rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row border border-slate-200">
        
        {{-- Left Side: Merchant & Order Summary --}}
        <div class="w-full md:w-1/3 bg-slate-50 p-8 border-b md:border-b-0 md:border-r border-slate-200 flex flex-col justify-between">
            <div class="space-y-6">
                {{-- SSLCommerz Header Branding --}}
                <div class="flex items-center gap-2">
                    <span class="text-xl font-extrabold text-red-600 tracking-tighter">SSL</span>
                    <span class="text-xl font-bold text-slate-800 tracking-tight">Commerz</span>
                </div>
                
                {{-- Invoice Summary --}}
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Merchant</span>
                    <p class="text-sm font-semibold text-slate-800">ServiceHub BD Ltd.</p>
                </div>

                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Service Provider</span>
                    <p class="text-sm font-semibold text-slate-800">{{ $booking->provider->name }}</p>
                </div>

                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Skills</span>
                    <p class="text-sm font-medium text-slate-600">{{ $booking->provider->skills->pluck('name')->join(', ') }}</p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-200/80">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Amount</span>
                <h3 class="text-3xl font-black text-slate-800">৳{{ number_format($booking->total_price) }}</h3>
                <span class="text-[10px] text-slate-400 block mt-1">BDT currency including all charges</span>
            </div>
        </div>

        {{-- Right Side: Gateway Portal Form --}}
        <div class="flex-1 p-8 flex flex-col justify-between">
            
            <form action="{{ route('payment.sslcommerz.process', $booking->id) }}" method="POST" id="ssl-form" class="space-y-6">
                @csrf
                <input type="hidden" name="payment_method" id="selected_method" value="card">

                <div>
                    <h2 class="text-xl font-bold text-slate-800">Choose how to pay</h2>
                    <p class="text-xs text-slate-400 mt-1">Select card, mobile banking or net banking to complete payment</p>
                </div>

                {{-- Tabs Header --}}
                <div class="flex border-b border-slate-200 text-sm font-semibold">
                    <button type="button" onclick="setTab('card')" id="tab-btn-card" class="px-5 py-3 border-b-2 border-red-500 text-red-600 transition-all">
                        Cards
                    </button>
                    <button type="button" onclick="setTab('mobile')" id="tab-btn-mobile" class="px-5 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all">
                        Mobile Banking
                    </button>
                    <button type="button" onclick="setTab('net')" id="tab-btn-net" class="px-5 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all">
                        Net Banking
                    </button>
                </div>

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-red-50 text-red-600 text-xs border border-red-100">
                        <ul class="list-disc pl-4 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Tab Contents: Cards --}}
                <div id="content-card" class="space-y-4">
                    <div class="grid grid-cols-4 gap-3 mb-4">
                        {{-- Mock Card Brands --}}
                        <div class="p-2 border rounded-xl flex items-center justify-center bg-slate-50 font-bold text-slate-500 text-xs shadow-sm">VISA</div>
                        <div class="p-2 border rounded-xl flex items-center justify-center bg-slate-50 font-bold text-slate-500 text-xs shadow-sm">MasterCard</div>
                        <div class="p-2 border rounded-xl flex items-center justify-center bg-slate-50 font-bold text-slate-500 text-xs shadow-sm">AMEX</div>
                        <div class="p-2 border rounded-xl flex items-center justify-center bg-slate-50 font-bold text-slate-500 text-xs shadow-sm">Nexus</div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Card Number</label>
                            <input type="text" name="card_number" id="card_number" maxlength="16" placeholder="4111 2222 3333 4444" value="4111222233334444"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-red-500 transition-all font-mono">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Expiration Date</label>
                                <input type="text" placeholder="MM/YY" value="12/29"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-red-500 transition-all font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">CVV / CVC</label>
                                <input type="text" placeholder="123" value="123"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-red-500 transition-all font-mono">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab Contents: Mobile Banking --}}
                <div id="content-mobile" class="hidden space-y-4">
                    <div class="grid grid-cols-4 gap-3 mb-4">
                        {{-- Mock Mobile Brands --}}
                        <div class="p-2 border rounded-xl flex items-center justify-center bg-slate-50 font-bold text-slate-500 text-xs shadow-sm">Nagad</div>
                        <div class="p-2 border rounded-xl flex items-center justify-center bg-slate-50 font-bold text-slate-500 text-xs shadow-sm">Rocket</div>
                        <div class="p-2 border rounded-xl flex items-center justify-center bg-slate-50 font-bold text-slate-500 text-xs shadow-sm">bKash</div>
                        <div class="p-2 border rounded-xl flex items-center justify-center bg-slate-50 font-bold text-slate-500 text-xs shadow-sm">Upay</div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Mobile Banking Wallet Number</label>
                        <input type="text" name="mobile_number" id="mobile_number" maxlength="11" placeholder="017XXXXXXXX" value="01712345678"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-red-500 transition-all font-mono">
                    </div>
                </div>

                {{-- Tab Contents: Net Banking --}}
                <div id="content-net" class="hidden space-y-4">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-500 space-y-2">
                        <p class="font-semibold text-slate-700">Choose Bank:</p>
                        <select class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-800 bg-white">
                            <option>CityTouch (City Bank)</option>
                            <option>Islami Bank Bangladesh Ltd</option>
                            <option>Mutual Trust Bank (MTB)</option>
                            <option>Bank Asia Net Banking</option>
                        </select>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-4 pt-6">
                    <a href="{{ route('payment.show', $booking->id) }}" class="flex-1 text-center py-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all">
                        GO BACK
                    </a>
                    <button type="submit" class="flex-1 py-4 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold transition-all shadow-lg shadow-red-600/10">
                        CONFIRM PAYMENT
                    </button>
                </div>

            </form>

            <div class="mt-8 text-center border-t border-slate-100 pt-4">
                <span class="text-[10px] text-slate-400 font-medium">Secured by SSLCOMMERZ — Authorized Payment Gateway of Bangladesh</span>
            </div>

        </div>

    </div>

    <script>
        function setTab(tab) {
            // Update hidden input
            document.getElementById('selected_method').value = tab;

            // Reset buttons
            document.getElementById('tab-btn-card').className = "px-5 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all";
            document.getElementById('tab-btn-mobile').className = "px-5 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all";
            document.getElementById('tab-btn-net').className = "px-5 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-all";

            // Reset contents
            document.getElementById('content-card').classList.add('hidden');
            document.getElementById('content-mobile').classList.add('hidden');
            document.getElementById('content-net').classList.add('hidden');

            // Set active
            if (tab === 'card') {
                document.getElementById('tab-btn-card').className = "px-5 py-3 border-b-2 border-red-500 text-red-600 transition-all";
                document.getElementById('content-card').classList.remove('hidden');
            } else if (tab === 'mobile') {
                document.getElementById('tab-btn-mobile').className = "px-5 py-3 border-b-2 border-red-500 text-red-600 transition-all";
                document.getElementById('content-mobile').classList.remove('hidden');
            } else if (tab === 'net') {
                document.getElementById('tab-btn-net').className = "px-5 py-3 border-b-2 border-red-500 text-red-600 transition-all";
                document.getElementById('content-net').classList.remove('hidden');
            }
        }
    </script>

</body>
</html>
