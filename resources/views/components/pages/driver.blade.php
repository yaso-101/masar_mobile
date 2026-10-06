<x-layout>
    <div class="min-h-screen bg-slate-50 p-4 pb-24">
        <div class="max-w-md mx-auto">

            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-slate-800">Plan Your Route</h1>
                <p class="text-slate-500 mt-2">Select your destination and available seats to find passengers.</p>
            </div>

            <form action="/driver/assign-student" method="POST"
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                @csrf

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

                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-slate-800 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-slate-900 active:scale-[0.98] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Find Passengers
                </button>

            </form>
        </div>
    </div>

</x-layout>
