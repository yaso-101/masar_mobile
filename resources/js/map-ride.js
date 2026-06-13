// resources/js/ride-map.js

let map;
let marker;

// Initialize the map (Call this when the map is opened)
function initMap() {
    if (!map) {
        // Default view
        map = L.map('map').setView([20, 0], 2);

        // OpenStreetMap Tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Click on map to place marker
        map.on('click', function(e) {
            placeMarker(e.latlng);
        });
    }

    // Fix rendering issues if map was hidden
    setTimeout(() => {
        map.invalidateSize();
    }, 200);
}

// 1. Open Map (Inline)
window.openMap = function() {
    document.getElementById('ride-map-container').classList.add('active');
    initMap();
};

// 2. Close Map (Inline)
window.closeMap = function() {
    document.getElementById('ride-map-container').classList.remove('active');
};

// 3. Place Marker Logic
function placeMarker(latlng) {
    if (marker) {
        map.removeLayer(marker);
    }
    marker = L.marker(latlng).addTo(map);

    // Update hidden inputs
    const latInput = document.getElementById('pickup_lat');
    const lngInput = document.getElementById('pickup_long');

    if (latInput) latInput.value = latlng.lat;
    if (lngInput) lngInput.value = latlng.lng;
}

// 4. The "Precise Location" (GPS) Button
window.locateUser = function() {
    if (!navigator.geolocation) {
        alert("Geolocation is not supported by your browser.");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const latlng = { lat: lat, lng: lng };

            // Fly to location
            map.flyTo(latlng, 16);

            // Drop pin
            placeMarker(latlng);
        },
        (error) => {
            let msg = "Unable to retrieve your location.";
            if (error.code === 1) msg = "Permission denied. Please allow location access.";
            alert(msg);
        },
        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        }
    );
};

// 5. Confirm Location
window.confirmLocation = function() {
    const lat = document.getElementById('pickup_lat').value;

    if (!lat) {
        alert("Please tap on the map to select a location first.");
        return;
    }

    // Update UI
    const btnText = document.getElementById('location-btn-text');
    if (btnText) {
        btnText.innerText = "Location Selected ✓";
        btnText.classList.add("text-emerald-600");
    }

    closeMap(); // Hide the inline map
};
