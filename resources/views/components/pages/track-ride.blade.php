<x-layout>
    @if (!$ride || $ride->status === 'completed')
        <!-- STATIC PAGE: No Active Rides -->
        <div class="bg-slate-50 min-h-screen p-4 flex flex-col items-center justify-center gap-4 text-center">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 max-w-sm w-full">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl">
                    ☕
                </div>
                <h1 class="text-xl font-bold text-slate-800 mb-2">No Active Rides</h1>
                <p class="text-slate-500 mb-6">You have no pending rides right now. Enjoy your break!</p>
                <a href="/driver"
                    class="block w-full bg-slate-800 text-white font-bold py-3 rounded-xl shadow-md hover:bg-slate-700 transition">
                    Return to Dashboard
                </a>
            </div>
        </div>
    @else
        <!-- ACTIVE RIDE MAP PAGE -->
        <div class="bg-slate-50 min-h-screen p-4 flex flex-col gap-4">

            <!-- Top Bar -->
            <div class="bg-white p-4 pr-14 rounded-xl shadow-sm z-10 flex items-center gap-4 border border-slate-100">
                <div>
                    @if ($ride->status === 'accepted')
                        <h1 class="font-bold text-slate-800">Pickup {{ $ride->student->name }}</h1>
                    @elseif($ride->status === 'picked_up')
                        <h1 class="font-bold text-slate-800">Drop off at {{ $ride->college->name }}</h1>
                    @endif
                    <p class="text-xs text-slate-500" id="status-text">Locating...</p>
                </div>
            </div>

            <!-- The Map Container with Data Attributes -->
            <div id="driver-map" class="w-full h-[60vh] rounded-xl border border-slate-200 shadow-sm z-0 relative"
                data-ride-id="{{ $ride->id }}" data-status="{{ $ride->status }}"
                data-dest-lat="{{ $ride->status === 'accepted' ? $ride->pickup_lat : $ride->college->latitude ?? 0 }}"
                data-dest-lng="{{ $ride->status === 'accepted' ? $ride->pickup_long : $ride->college->longitude ?? 0 }}"
                data-dest-name="{{ $ride->status === 'accepted' ? $ride->student->name : $ride->college->name }}"
                data-icon-url="{{ $ride->status === 'accepted' ? 'https://cdn-icons-png.flaticon.com/512/1946/1946429.png' : 'https://cdn-icons-png.flaticon.com/512/2231/2231442.png' }}"
                data-active-color="{{ $ride->status === 'accepted' ? 'bg-emerald-600' : 'bg-blue-600' }}"
                data-hover-color="{{ $ride->status === 'accepted' ? 'hover:bg-emerald-700' : 'hover:bg-blue-700' }}"
                data-btn-text="{{ $ride->status === 'accepted' ? 'Confirm Pickup' : 'Arrived at College (Complete)' }}"
                data-zone-radius="{{ $ride->status === 'accepted' ? 75 : 200 }}">
            </div>

            <!-- Bottom Action Bar -->
            <div class="z-10 mt-2">
                @if ($ride->status === 'accepted')
                    <form action="/driver/pickup/{{ $ride->id }}" method="POST">
                        @csrf
                        <button type="submit" id="action-btn" disabled
                            class="w-full bg-slate-400 text-white font-bold py-4 rounded-xl shadow-md cursor-not-allowed transition-all duration-300">
                            Drive to Zone to Unlock
                        </button>
                    </form>
                @elseif($ride->status === 'picked_up')
                    <form action="/driver/dropoff" method="POST">
                        @csrf
                        <button type="submit" id="action-btn" disabled
                            class="w-full bg-slate-400 text-white font-bold py-4 rounded-xl shadow-md cursor-not-allowed transition-all duration-300">
                            Drive to Zone to Unlock
                        </button>
                    </form>
                @endif
            </div>

        </div>
    @endif
</x-layout>
