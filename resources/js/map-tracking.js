export function initMapTracking() {
    const mapElement = document.getElementById("driver-map");
    if (!mapElement) return; // Guard clause: only runs when the driver map is present

    // Extract variables from data attributes
    const destLat = parseFloat(mapElement.dataset.destLat);
    const destLng = parseFloat(mapElement.dataset.destLng);
    const destName = mapElement.dataset.destName;
    const iconUrl = mapElement.dataset.iconUrl;
    const activeColor = mapElement.dataset.activeColor;
    const hoverColor = mapElement.dataset.hoverColor;
    const btnText = mapElement.dataset.btnText;
    const ZONE_RADIUS = parseInt(mapElement.dataset.zoneRadius, 10);

    const actionBtn = document.getElementById("action-btn");
    const statusText = document.getElementById("status-text");

    // Initialize Map
    const map = L.map("driver-map", {
        zoomControl: true,
        scrollWheelZoom: true,
    }).setView([destLat, destLng], 14);

    // Esri Tile Layer (Replaces OpenStreetMap to fix 403 blocks in mobile WebViews)
    L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}",
        {
            maxZoom: 19,
            attribution: "Tiles &copy; Esri",
        },
    ).addTo(map);

    // Destination Marker
    const destIcon = L.icon({
        iconUrl: iconUrl,
        iconSize: [40, 40],
        iconAnchor: [20, 40],
    });
    // textContent, not HTML: destName can be a student's self-chosen name
    const destPopup = document.createElement("b");
    destPopup.textContent = destName;
    L.marker([destLat, destLng], { icon: destIcon })
        .addTo(map)
        .bindPopup(destPopup);

    // Draw Unlock Zone Circle
    L.circle([destLat, destLng], {
        color: "#10b981",
        fillColor: "#10b981",
        fillOpacity: 0.2,
        radius: ZONE_RADIUS,
    }).addTo(map);

    let driverMarker = null;
    let routeControl = null;

    // Live Geolocation Tracking
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            (position) => {
                const driverLat = position.coords.latitude;
                const driverLng = position.coords.longitude;

                const carIcon = L.icon({
                    iconUrl:
                        "https://cdn-icons-png.flaticon.com/512/744/744403.png",
                    iconSize: [48, 48],
                    iconAnchor: [24, 24],
                });

                if (driverMarker) {
                    driverMarker.setLatLng([driverLat, driverLng]);
                } else {
                    driverMarker = L.marker([driverLat, driverLng], {
                        icon: carIcon,
                    }).addTo(map);
                }

                // 🚀 NEW: Broadcast location to the server so the student(s) can see it
                // (the server works out which rides this driver is on)
                fetch("/driver/update-location", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]',
                        )?.content,
                    },
                    body: JSON.stringify({
                        lat: driverLat,
                        lng: driverLng,
                    }),
                }).catch((err) => console.error("Broadcast failed:", err));

                // Routing Machine Line
                if (!routeControl && window.L && window.L.Routing) {
                    routeControl = L.Routing.control({
                        waypoints: [
                            L.latLng(driverLat, driverLng),
                            L.latLng(destLat, destLng),
                        ],
                        lineOptions: {
                            styles: [
                                { color: "#059669", opacity: 0.8, weight: 6 },
                            ],
                        },
                        show: false,
                        addWaypoints: false,
                        routeWhileDragging: false,
                        fitSelectedRoutes: true,
                        createMarker: () => null,
                    }).addTo(map);
                } else if (routeControl) {
                    // Update the route line as the driver moves closer
                    routeControl.setWaypoints([
                        L.latLng(driverLat, driverLng),
                        L.latLng(destLat, destLng),
                    ]);
                }

                // Distance & Unlock Logic
                const distance = Math.round(
                    map.distance([driverLat, driverLng], [destLat, destLng]),
                );

                if (distance <= ZONE_RADIUS) {
                    if (actionBtn) {
                        actionBtn.disabled = false;
                        actionBtn.className = `w-full ${activeColor} ${hoverColor} text-white font-bold py-4 rounded-xl shadow-md transition-all duration-300 active:scale-[0.98]`;
                        actionBtn.innerText = btnText;
                    }
                    if (statusText) {
                        statusText.innerText = `Arrived in zone! (${distance}m away)`;
                        statusText.className =
                            "text-xs text-emerald-600 font-bold";
                    }
                } else {
                    if (actionBtn) {
                        actionBtn.disabled = true;
                        actionBtn.className =
                            "w-full bg-slate-400 text-white font-bold py-4 rounded-xl shadow-md cursor-not-allowed transition-all duration-300";
                        actionBtn.innerText = `Move ${distance - ZONE_RADIUS}m closer to unlock`;
                    }
                    if (statusText) {
                        statusText.innerText = `Driving... ${distance}m away`;
                        statusText.className = "text-xs text-slate-500";
                    }
                }
            },
            (error) => {
                alert("Please enable GPS/Location services for live tracking.");
            },
            {
                enableHighAccuracy: true,
                maximumAge: 10000,
                timeout: 5000,
            },
        );
    } else {
        alert("Geolocation is not supported by your browser.");
    }
}
