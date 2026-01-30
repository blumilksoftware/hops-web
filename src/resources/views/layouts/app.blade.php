<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="bg-gray-100 font-sans antialiased" x-data="{ mobileMenuOpen: false }">

    <nav class="bg-white border-b border-gray-200 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                <div class="flex items-center flex-shrink-0">
                    <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">{{ config('app.name') }}</a>
                </div>

                <div class="hidden sm:flex sm:space-x-8">
                    <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium text-gray-900">Home</a>
                    <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">About</a>
                    <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">Contact</a>
                </div>

                <div class="flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                        <span x-show="!mobileMenuOpen">&#9776;</span>
                        <span x-show="mobileMenuOpen">&times;</span>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" class="sm:hidden">
            <div class="pt-2 pb-4 space-y-1">
                <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-indigo-500 bg-indigo-50 text-indigo-700 text-base font-medium">Home</a>
                <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 text-base font-medium">About</a>
                <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 text-base font-medium">Contact</a>
            </div>
        </div>
    </nav>

    <main class="flex-1">
        @yield('content')
    </main>
</body>
</html>
