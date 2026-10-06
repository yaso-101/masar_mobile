@auth
    @php
        $user = auth()->user();
        $role = $user->getRoleNames()->first();
    @endphp

    <!-- ☰ MENU BUTTON (top-right, opens the sidebar) -->
    <button type="button" id="sidebar-toggle" onclick="openSidebar()" aria-label="Open menu" aria-controls="sidebar"
        aria-expanded="false"
        class="fixed top-4 right-4 z-[1500] w-10 h-10 flex items-center justify-center rounded-full bg-white text-slate-700 shadow-md border border-slate-100 hover:text-emerald-600 active:scale-95 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- DARK BACKDROP (tap outside the sidebar to close it) -->
    <div id="sidebar-backdrop" onclick="closeSidebar()"
        class="fixed inset-0 z-[2000] bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300"></div>

    <!-- THE SIDEBAR (hidden off-screen to the right until opened) -->
    <aside id="sidebar" aria-label="Account menu"
        class="fixed top-0 right-0 z-[2001] h-full w-72 max-w-[85%] bg-white shadow-2xl flex flex-col translate-x-full invisible transition-all duration-300">

        <!-- 👤 Profile Header -->
        <div class="p-6 bg-emerald-600 text-white">
            <div class="flex justify-between items-start">
                <div class="h-14 w-14 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                </div>
                <button type="button" onclick="closeSidebar()" aria-label="Close menu"
                    class="p-1 rounded-md text-white/80 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <p class="mt-4 font-bold text-lg truncate">{{ $user->name }}</p>
            <p class="text-sm text-emerald-100 truncate">{{ $user->email }}</p>

            @if ($role)
                <span class="inline-block mt-2 px-2 py-0.5 rounded-full bg-white/20 text-xs font-semibold capitalize">
                    {{ $role }}
                </span>
            @endif
        </div>

        <!-- 🔗 Links (add more account items here) -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="/profile"
                class="flex items-center gap-3 px-3 py-3 rounded-xl font-medium {{ Request::is('profile') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Edit Profile
            </a>
        </nav>

        <!-- 🚪 Log Out (pinned to the bottom) -->
        <form action="/logout" method="POST" class="p-4 border-t border-slate-100">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-3 rounded-xl font-medium text-red-600 hover:bg-red-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Log Out
            </button>
        </form>
    </aside>
@endauth
