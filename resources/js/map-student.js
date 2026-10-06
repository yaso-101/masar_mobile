document.addEventListener('DOMContentLoaded', () => {
    const mapContainer = document.getElementById('tracking-map');

    // Only run this code if the tracking map exists on the current page
    if (mapContainer) {

        // 1. Read the destination from the HTML attributes
        // (our pickup point, or the college once we've been picked up)
        const destLat = parseFloat(mapContainer.getAttribute('data-dest-lat'));
        const destLng = parseFloat(mapContainer.getAttribute('data-dest-lng'));
        const destName = mapContainer.getAttribute('data-dest-name');
        const rideId = mapContainer.getAttribute('data-ride-id');
        const status = mapContainer.getAttribute('data-status');
        const statusText = document.getElementById('driver-status');

        // 2. Initialize the map (make sure Leaflet 'L' is available)
        if (typeof L !== 'undefined') {
            const map = L.map('tracking-map', {
                zoomControl: false
            }).setView([destLat, destLng], 15);

            // 3. Add the OpenStreetMap tiles
            // Replace whatever tileLayer you currently have with this exact snippet:
L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
    maxZoom: 19,
    attribution: 'Tiles &copy; Esri'
}).addTo(map);

            // 4. Drop the read-only destination marker
            // (textContent, not HTML, so a name can never inject code)
            const popup = document.createElement('b');
            popup.textContent = destName;
            const marker = L.marker([destLat, destLng]).addTo(map);
            marker.bindPopup(popup).openPopup();

            // 5. 🚕 Live driver location (sent by the driver's tracking page through Reverb)
            if (window.Echo && rideId) {
                const carIcon = L.icon({
                    iconUrl: 'https://cdn-icons-png.flaticon.com/512/744/744403.png',
                    iconSize: [48, 48],
                    iconAnchor: [24, 24],
                });
                let driverMarker = null;
                let routeControl = null;

                window.Echo.private(`ride-tracking.${rideId}`)
                    .listen('.driver.moved', (e) => {
                        const driverPos = L.latLng(e.lat, e.lng);
                        const destPos = L.latLng(destLat, destLng);

                        if (driverMarker) {
                            driverMarker.setLatLng(driverPos);
                        } else {
                            driverMarker = L.marker(driverPos, { icon: carIcon }).addTo(map);
                            // First update: zoom so both the driver and the destination are visible
                            map.fitBounds([driverPos, destPos], { padding: [60, 60] });
                        }

                        // 6. Routing Machine Line (same as the driver's map)
                        if (!routeControl && window.L.Routing) {
                            routeControl = L.Routing.control({
                                waypoints: [driverPos, destPos],
                                lineOptions: {
                                    styles: [
                                        { color: '#059669', opacity: 0.8, weight: 6 },
                                    ],
                                },
                                show: false,
                                addWaypoints: false,
                                routeWhileDragging: false,
                                // Only re-zoom when the route leaves the screen, so the student can pan/zoom freely
                                fitSelectedRoutes: 'smart',
                                createMarker: () => null,
                            }).addTo(map);
                        } else if (routeControl) {
                            // Update the route line as the driver moves
                            routeControl.setWaypoints([driverPos, destPos]);
                        }

                        // 7. Status card
                        if (statusText) {
                            const distance = Math.round(map.distance(driverPos, destPos));
                            statusText.innerText = status === 'picked_up'
                                ? `${distance}m to ${destName}`
                                : `Driver is ${distance}m away from your pickup point`;
                        }
                    });
            }
        }
    }
});
