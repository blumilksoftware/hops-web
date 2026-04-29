<header class="bg-green-800 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="bg-green-600 p-2 rounded-lg">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">{{ __('Hop Variety Browser') }}</h1>
        </div>
        <div>
            @if (Route::has('login'))
                <nav class="flex space-x-4">
                    @auth
                        <a href="{{ url('/') }}"
                            class="text-green-100 hover:text-white transition">{{ __('Home') }}</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-green-100 hover:text-white transition">{{ __('Log in') }}</a>
                    @endauth
                </nav>
            @endif
        </div>
    </div>
</header>
