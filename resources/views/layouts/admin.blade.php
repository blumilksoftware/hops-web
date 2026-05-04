<x-app-layout>
    @isset($header)
        <x-slot name="header">
            {{ $header }}
        </x-slot>
    @endisset

    <div class="bg-hops-light border-b border-hops-warm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="-mb-px flex space-x-8" aria-label="Admin Navigation">
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'border-hops-accent text-hops-dark' : 'border-transparent text-hops-mid hover:text-hops-warm hover:border-hops-warm' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    {{ __('Users') }}
                </a>
                <a href="{{ route('admin.hops.index') }}" class="{{ request()->routeIs('admin.hops.*') ? 'border-hops-accent text-hops-dark' : 'border-transparent text-hops-mid hover:text-hops-warm hover:border-hops-warm' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    {{ __('Hops') }}
                </a>
                <a href="{{ route('admin.hop-queries.index') }}" class="{{ request()->routeIs('admin.hop-queries.*') ? 'border-hops-accent text-hops-dark' : 'border-transparent text-hops-mid hover:text-hops-warm hover:border-hops-warm' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    {{ __('Hop Queries') }}
                </a>
            </nav>
        </div>
    </div>

    {{ $slot }}
</x-app-layout>
