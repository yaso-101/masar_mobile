<x-layout>
    <div class="relative h-screen w-full bg-slate-50 overflow-hidden">

        <!-- 🗺️ THE LIVE TRACKING MAP -->
        <!-- Destination = our pickup point, or the college once we've been picked up (same as the driver's map) -->
        <div id="tracking-map"
            data-dest-lat="{{ $ride->status === 'picked_up' ? $ride->college->latitude : $ride->pickup_lat }}"
            data-dest-lng="{{ $ride->status === 'picked_up' ? $ride->college->longitude : $ride->pickup_long }}"
            data-dest-name="{{ $ride->status === 'picked_up' ? $ride->college->name : 'Pickup Location' }}"
            data-ride-id="{{ $ride->id }}" data-status="{{ $ride->status }}"
            class="absolute inset-0 w-full h-full bg-slate-200 z-0">
        </div>

        <!-- 🚕 Driver status card (text is updated live by map-student.js) -->
        <div
            class="absolute bottom-24 left-4 right-4 z-10 max-w-md mx-auto bg-white p-4 rounded-2xl shadow-lg border border-slate-100">
            <h2 class="font-bold text-slate-800">{{ $ride->driver?->name ?? 'Your driver' }}</h2>
            <p id="driver-status" class="text-sm text-slate-500">
                @if ($ride->status === 'picked_up')
                    Heading to {{ $ride->college->name }}
                @else
                    Waiting for your driver's live location...
                @endif
            </p>
        </div>

    </div>
</x-layout>
