<div class="min-h-screen bg-emerald-50 p-4 pb-24">

    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold text-emerald-800 mb-6">Request a Ride</h1>

        <!-- THE FORM -->
        <form action="/studentfirst" method="POST">
            @csrf

            <!-- 1. College Dropdown (Dynamic Loop) -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Destination</label>
                <select name="college_id" id="college-select"
                    class="w-full p-3 border border-gray-300 rounded-lg bg-white shadow-sm" required>
                    <option value="" disabled selected>Select College</option>

                    <!-- Loop through all colleges passed from the Controller -->
                    @foreach ($colleges as $college)
                        <option value="{{ $college->id }}" data-lat="{{ $college->latitude }}"
                            data-lng="{{ $college->longitude }}">
                            {{ $college->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- 2. Show Current Location Button -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Pickup Location</label>
                <button type="button" onclick="openMap()"
                    class="w-full bg-white border-2 border-emerald-500 text-emerald-700 font-bold py-4 px-4 rounded-lg shadow-sm flex items-center justify-center gap-2 hover:bg-emerald-50 active:scale-95 transition-transform">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                    <span id="location-btn-text">Select Location on Map</span>
                </button>

                <!-- Hidden Inputs -->
                <input type="hidden" id="pickup_lat" name="pickup_lat">
                <input type="hidden" id="pickup_long" name="pickup_long">

                @error('pickup_lat')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- ============================================== -->
            <!-- INLINE MAP (Appears between buttons) -->
            <!-- ============================================== -->
            <div id="ride-map-container" class="map-inline">

                <!-- The Leaflet Map -->
                <div id="map" class="w-full h-full z-0"></div>

                <!-- 3. Precise Location Button (Floating) -->
                <button type="button" onclick="locateUser()" class="locate-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>

                <!-- Close Button (Top Right) -->
                <button type="button" onclick="closeMap()"
                    class="absolute top-2 right-2 z-[1001] bg-white rounded-md p-1 shadow-sm text-gray-500 hover:text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- 4. Save Info Button (Always Visible) -->
            <div class="mt-6">
                <button type="submit"
                    class="w-full bg-emerald-600 text-white font-bold py-3 rounded-lg shadow hover:bg-emerald-700 transition">
                    Save Info
                </button>
            </div>

        </form>
    </div>
</div>
