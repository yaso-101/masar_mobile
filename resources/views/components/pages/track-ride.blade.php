<x-layout>
    <div class="bg-slate-50 min-h-screen p-4 flex flex-col gap-4">

        <!-- Top Bar -->
        <div class="bg-white p-4 rounded-xl shadow-sm z-10 flex items-center gap-4 border border-slate-100">
            <a href="/dashboard" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                @if($ride->status === 'accepted')
                    <h1 class="font-bold text-slate-800">Pickup {{ $ride->student->name }}</h1>
                @elseif($ride->status === 'picked_up')
                    <h1 class="font-bold text-slate-800">Drop off at {{ $ride->college->name }}</h1>
                @endif
                <p class="text-xs text-slate-500" id="status-text">Locating...</p>
            </div>
        </div>

        <!-- The Map Container -->
        <div id="driver-map" class="w-full h-[60vh] rounded-xl border border-slate-200 shadow-sm z-0 relative"></div>

        <!-- Bottom Action Bar -->
        <div class="z-10 mt-2">
            @if($ride->status === 'accepted')
                <form action="/driver/pickup/{{ $ride->id }}" method="POST">
                    @csrf
                    <!-- Button is disabled and gray by default -->
                    <button type="submit" id="action-btn" disabled class="w-full bg-slate-400 text-white font-bold py-4 rounded-xl shadow-md cursor-not-allowed transition-all duration-300">
                        Drive to Zone to Unlock
                    </button>
                </form>
            @elseif($ride->status === 'picked_up')
                <form action="/driver/dropoff" method="POST">
                    @csrf
                    <!-- Button is disabled and gray by default -->
                    <button type="submit" id="action-btn" disabled class="w-full bg-slate-400 text-white font-bold py-4 rounded-xl shadow-md cursor-not-allowed transition-all duration-300">
                        Drive to Zone to Unlock
                    </button>
                </form>
            @endif
        </div>

    </div>

    <!-- Leaflet Core CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Leaflet Routing Machine CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script>
        // 1. DYNAMIC COORDINATES
        @if($ride->status === 'accepted')
            const destLat = {{ $ride->pickup_lat }};
            const destLng = {{ $ride->pickup_long }};
            const destName = "{{ $ride->student->name }}";
            const iconUrl = 'https://cdn-icons-png.flaticon.com/512/1946/1946429.png';
            const activeColor = 'bg-emerald-600';
            const hoverColor = 'hover:bg-emerald-700';
            const btnText = 'Confirm Pickup';
        @elseif($ride->status === 'picked_up')
            const destLat = {{ $ride->college->lat ?? 0 }};
            const destLng = {{ $ride->college->long ?? 0 }};
            const destName = "{{ $ride->college->name }}";
            const iconUrl = 'https://cdn-icons-png.flaticon.com/512/2231/2231442.png';
            const activeColor = 'bg-blue-600';
            const hoverColor = 'hover:bg-blue-700';
            const btnText = 'Arrived at College (Complete)';
        @endif

        const ZONE_RADIUS = 200; // 200 meters unlock zone
        const actionBtn = document.getElementById('action-btn');
        const statusText = document.getElementById('status-text');

        // Initialize the map
        const map = L.map('driver-map', {
            zoomControl: true,
            scrollWheelZoom: true
        }).setView([destLat, destLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Custom Icons
        const destIcon = L.icon({ iconUrl: iconUrl, iconSize: [40, 40], iconAnchor: [20, 40] });
        const carIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/744/744403.png', iconSize: [48, 48], iconAnchor: [24, 24] });

        // Place Destination Marker
        L.marker([destLat, destLng], {icon: destIcon}).addTo(map).bindPopup(`<b>${destName}</b>`);

        // Draw the Unlock Zone (Green Circle)
        L.circle([destLat, destLng], {
            color: '#10b981',
            fillColor: '#10b981',
            fillOpacity: 0.2,
            radius: ZONE_RADIUS
        }).addTo(map);

        let driverMarker = null;
        let routeControl = null;

        // 2. LIVE GPS TRACKING (watchPosition updates automatically as driver moves)
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(function(position) {
                const driverLat = position.coords.latitude;
                const driverLng = position.coords.longitude;

                // Update or create Car Marker
                if (driverMarker) {
                    driverMarker.setLatLng([driverLat, driverLng]);
                } else {
                    driverMarker = L.marker([driverLat, driverLng], {icon: carIcon}).addTo(map);
                }

                // Draw Route (Only once to prevent flickering)
                if (!routeControl) {
                    routeControl = L.Routing.control({
                        waypoints: [ L.latLng(driverLat, driverLng), L.latLng(destLat, destLng) ],
                        lineOptions: { styles: [{color: '#059669', opacity: 0.8, weight: 6}] },
                        show: false, addWaypoints: false, routeWhileDragging: false, fitSelectedRoutes: true,
                        createMarker: function() { return null; }
                    }).addTo(map);
                }

                // 3. DISTANCE CALCULATION & BUTTON UNLOCK LOGIC
                const distance = Math.round(map.distance([driverLat, driverLng], [destLat, destLng]));

                if (distance <= ZONE_RADIUS) {
                    // Inside Zone: Unlock Button
                    actionBtn.disabled = false;
                    actionBtn.classList.remove('bg-slate-400', 'cursor-not-allowed');
                    actionBtn.classList.add(activeColor, hoverColor, 'active:scale-[0.98]');
                    actionBtn.innerText = btnText;
                    statusText.innerText = `Arrived in zone! (${distance}m away)`;
                    statusText.classList.add('text-emerald-600', 'font-bold');
                } else {
                    // Outside Zone: Lock Button
                    actionBtn.disabled = true;
                    actionBtn.classList.remove(activeColor, hoverColor, 'active:scale-[0.98]');
                    actionBtn.classList.add('bg-slate-400', 'cursor-not-allowed');
                    actionBtn.innerText = `Move ${distance - ZONE_RADIUS}m closer to unlock`;
                    statusText.innerText = `Driving... ${distance}m away`;
                    statusText.classList.remove('text-emerald-600', 'font-bold');
                }

            }, function(error) {
                alert("Please enable GPS/Location services for live tracking.");
            }, {
                enableHighAccuracy: true,
                maximumAge: 10000,
                timeout: 5000
            });
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    </script>
</x-layout>
