<x-layout>

    @props(['ride' => null])

    @php
        $driver = $ride?->driver;
    @endphp

    <div class="flex items-center justify-center p-4 mt-8 pb-24">
        <!-- Business Card Wrapper -->
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

            <!-- Card Header -->
            <div class="h-24 bg-slate-800"></div>

            <!-- Profile Picture Placeholder -->
            <div class="flex justify-center -mt-12">
                <div
                    class="h-24 w-24 bg-slate-100 rounded-full border-4 border-white flex items-center justify-center shadow-sm">
                    <svg class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 text-center">

                @if ($driver)
                    <!-- 🟢 DRIVER ASSIGNED -->
                    <h3 class="text-xl font-extrabold text-slate-800 mb-1">{{ $driver->name }}</h3>
                    <p class="text-sm font-medium text-emerald-600 mb-6">
                        Driver is on the way!
                    </p>

                    <div class="flex justify-center gap-8 text-slate-500 text-sm mb-6 pb-6 border-b border-slate-100">
                        <div class="text-center">
                            <span class="block font-bold text-slate-800 mb-1">Vehicle</span>
                            <span class="bg-slate-100 px-3 py-1 rounded-lg text-slate-700">Sedan</span>
                            <!-- Add dynamic vehicle fields if added to User later -->
                        </div>
                        <div class="text-center">
                            <span class="block font-bold text-slate-800 mb-1">License Plate</span>
                            <span class="bg-slate-100 px-3 py-1 rounded-lg text-slate-700">ABC-123</span>
                        </div>
                    </div>

                    <button
                        class="w-full bg-emerald-500 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-emerald-600 active:scale-[0.98] transition-all">
                        Contact Driver
                    </button>
                @else
                    <!-- 🔴 NO DRIVER YET -->
                    <h3 class="text-xl font-extrabold text-slate-300 mb-1">Waiting for Driver</h3>
                    <p class="text-sm font-medium text-emerald-500 mb-6 flex items-center justify-center gap-1">
                        <span class="relative flex h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        Searching for driver...
                    </p>

                    <div class="flex justify-center gap-8 text-slate-500 text-sm mb-6 pb-6 border-b border-slate-100">
                        <div class="text-center">
                            <span class="block font-bold text-slate-800 mb-1">Vehicle</span>
                            <span class="bg-slate-50 px-3 py-1 rounded-lg text-slate-300">---</span>
                        </div>
                        <div class="text-center">
                            <span class="block font-bold text-slate-800 mb-1">License Plate</span>
                            <span class="bg-slate-50 px-3 py-1 rounded-lg text-slate-300">---</span>
                        </div>
                    </div>

                    <!-- Contact button completely removed! -->
                @endif

            </div>
        </div>
    </div>
</x-layout>
