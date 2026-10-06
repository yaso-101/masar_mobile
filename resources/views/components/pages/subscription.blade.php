<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<!-- Stand-alone page (no bottom nav, no sidebar): unpaid users see this and nothing else -->

<body class="bg-emerald-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <!-- Main White Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-emerald-100 text-center">

            <!-- 🏦 FIB LOGO -->
            <img src="{{ asset('images/fib-logo.svg') }}" alt="FIB - First Iraqi Bank" class="h-16 mx-auto mb-6">

            <h1 class="text-2xl font-bold text-slate-800 mb-2">Subscription Required</h1>
            <p class="text-slate-500 text-sm mb-6">Send the subscription fee with FIB to this number:</p>

            <!-- 📞 NUMBER TO PAY + COPY BUTTON -->
            @if ($payToNumber)
                <div
                    class="flex items-center justify-between gap-3 bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6">
                    <span id="pay-to-number" data-copy="{{ preg_replace('/\s+/', '', $payToNumber) }}" dir="ltr"
                        class="text-xl font-bold tracking-wider text-slate-800">{{ $payToNumber }}</span>

                    <button type="button" onclick="copyPayNumber()"
                        class="flex items-center gap-1.5 shrink-0 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg active:scale-95 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span id="copy-label">Copy</span>
                    </button>
                </div>
            @else
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6 text-slate-400">
                    Payment number not set yet
                </div>
            @endif

            <p class="text-sm text-slate-500 mb-6">
                Pay from the FIB account of your registered number
                <b class="text-slate-700" dir="ltr">{{ $phoneNumber ?? '—' }}</b>.
                Your account unlocks once we confirm your payment.
            </p>

            <!-- Reloads this page: if they've been marked as paid, it sends them into the app -->
            <a href="/subscription"
                class="block w-full border-2 border-emerald-500 text-emerald-700 font-bold py-3 rounded-xl hover:bg-emerald-50 active:scale-[0.98] transition">
                I've paid, check again
            </a>
        </div>

        <!-- Log out (in case this is the wrong account) -->
        <form action="/logout" method="POST" class="text-center mt-6">
            @csrf
            <button type="submit" class="text-sm text-slate-400 hover:text-red-500 transition">
                Log out
            </button>
        </form>

    </div>

</body>

</html>
