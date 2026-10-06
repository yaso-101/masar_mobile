import "./form.js";
import "./map-student.js";
import "./map-driver.js";
import "leaflet/dist/leaflet.css";
import "leaflet-routing-machine/dist/leaflet-routing-machine.css";
import "./search-driver";
import "./sidebar.js";
import "./subscription.js";
import { initMapTracking } from "./map-tracking";

document.addEventListener("DOMContentLoaded", () => {
    initMapTracking();
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
