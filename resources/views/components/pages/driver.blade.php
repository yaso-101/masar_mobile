<x-layout>
    <div class="min-h-screen bg-slate-50 p-4 pb-24">
        <div class="max-w-md mx-auto">

            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-slate-800">Plan Your Route</h1>
                <p class="text-slate-500 mt-2">Select your destination and available seats to find passengers.</p>
            </div>

            @if (session('error'))
                <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="/driver/assign-student" method="POST"
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                @csrf

                <!-- Hidden inputs to store the driver's exact GPS location -->
                <input type="hidden" name="driver_lat" id="driver_lat">
                <input type="hidden" name="driver_long" id="driver_long">

                <div class="mb-6">
                    <label for="college_id" class="block text-slate-700 text-sm font-bold mb-2">
                        Destination College
                    </label>
                    <div class="relative">
                        <select name="college_id" id="college_id"
                            class="w-full p-4 appearance-none border border-slate-300 rounded-xl bg-slate-50 focus:ring-2 focus:ring-slate-800 focus:border-slate-800 outline-none transition"
                            required>
                            <option value="" disabled selected>Where are you driving to?</option>

                            @foreach ($colleges as $college)
                                <option value="{{ $college->id }}">
                                    {{ $college->name }}
                                </option>
                            @endforeach
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <label for="available_slots" class="block text-slate-700 text-sm font-bold mb-2">
                        Available Seats
                    </label>
                    <div class="relative">
                        <select name="available_slots" id="available_slots"
                            class="w-full p-4 appearance-none border border-slate-300 rounded-xl bg-slate-50 focus:ring-2 focus:ring-slate-800 focus:border-slate-800 outline-none transition"
                            required>
                            <option value="" disabled selected>How many passengers can you take?</option>
                            <option value="1">1 Seat</option>
                            <option value="2">2 Seats</option>
                            <option value="3">3 Seats</option>
                            <option value="4">4 Seats</option>
                            <option value="5">5 Seats</option>
                            <option value="6">6 Seats</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Submit button is disabled by default until JS finds their location -->
                <button type="submit" id="submit-ride-btn" disabled
                    class="w-full flex items-center justify-center gap-2 bg-slate-800 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-slate-900 active:scale-[0.98] transition-all opacity-50 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span id="btn-text">Finding Location...</span>
                </button>

            </form>
        </div>
    </div>

    <!-- Script to automatically fetch GPS coordinates -->
    <script>
        const submitBtn = document.getElementById('submit-ride-btn');
        const btnText = document.getElementById('btn-text');

        // As soon as the page loads, try to find the driver's location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Success! Fill the hidden inputs with the exact coordinates
                    document.getElementById('driver_lat').value = position.coords.latitude;
                    document.getElementById('driver_long').value = position.coords.longitude;

                    // Unlock the submit button and change the text
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    btnText.innerText = 'Find Passengers';
                },
                function(error) {
                    // They denied location access, or it failed
                    alert("Please enable location services so we can match you with the closest student.");

                    // Unlock the button anyway so they can still use the fallback (first-come, first-served) method
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    btnText.innerText = 'Find Passengers (Without GPS)';
                }
            );
        } else {
            // Browser doesn't support GPS, just unlock the button
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            btnText.innerText = 'Find Passengers';
        }
    </script>
</x-layout>
    