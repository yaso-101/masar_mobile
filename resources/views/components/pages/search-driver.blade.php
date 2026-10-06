<x-layout>
    <div id="ride-data" data-ride-id="{{ $ride->id }}" class="hidden"></div>
    <div class="min-h-screen bg-emerald-50 flex flex-col items-center justify-center p-4">

        <div class="relative flex items-center justify-center w-40 h-40 mb-10">
            <div class="absolute w-full h-full bg-emerald-300 rounded-full animate-ping opacity-60"></div>

            <div class="absolute w-24 h-24 bg-emerald-400 rounded-full animate-ping opacity-40"
                style="animation-delay: 0.5s;"></div>

            <div
                class="relative z-10 w-16 h-16 bg-emerald-600 text-white rounded-full flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 animate-bounce" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-emerald-800 mb-2">Finding your driver...</h2>
        <p class="text-emerald-600 text-center max-w-xs mb-10">
            Sit tight! We are pinging the closest available drivers to your location.
        </p>

        <button type="button"
            class="px-6 py-2 bg-white border-2 border-red-100 text-red-500 font-bold rounded-full shadow-sm hover:bg-red-50 active:scale-95 transition-transform">
            Cancel Ride
        </button>
    </div>
</x-layout>
