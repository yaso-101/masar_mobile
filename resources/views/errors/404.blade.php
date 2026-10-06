<x-layout>
    <div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-4">

        <div class="bg-white p-10 rounded-2xl shadow-sm border border-slate-100 text-center max-w-lg w-full">

            <!-- 404 Icon/Graphic -->
            <div class="flex justify-center mb-6 text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h1 class="text-4xl font-extrabold text-slate-800 mb-2">404</h1>
            <h2 class="text-xl font-bold text-slate-600 mb-4">Page Not Found</h2>

            <p class="text-slate-500 mb-8">
                The page you are looking for doesn't exist, or you don't have permission to view it.
            </p>

            <!-- Back to Dashboard Button -->
            <a href="/"
               class="inline-flex items-center justify-center gap-2 w-full bg-slate-800 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:bg-slate-900 active:scale-[0.98] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back Home
            </a>

        </div>

    </div>
</x-layout>
