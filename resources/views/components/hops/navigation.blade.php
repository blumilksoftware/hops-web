<nav class="bg-white border-b border-hops-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="text-xl font-bold flex items-center text-hops-ink">
                    <x-lucide-hop class="w-6 h-6" />
                    <span class="ml-1">{{ __('hops') }}</span>
                </a>
            </div>

            <div class="hidden sm:flex space-x-8 items-center">
                <a href="/"
                    class="{{ request()->is('/') ? 'text-hops-mid font-semibold' : 'text-gray-500 hover:text-hops-ink' }} text-sm transition">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('comparison.index') }}"
                    class="{{ request()->routeIs('comparison.*') ? 'text-hops-mid font-semibold' : 'text-gray-500 hover:text-hops-ink' }} text-sm transition">
                    {{ __('Comparison') }}
                </a>
                @if(Auth::user()?->is_team_member)
                <a href="{{ route('laboratory.dashboard') }}"
                    class="{{ request()->routeIs('laboratory.*') ? 'text-hops-mid font-semibold' : 'text-gray-500 hover:text-hops-ink' }} text-sm transition">
                    {{ __('Laboratory') }}
                </a>
                @endif
            </div>

            <div class="flex items-center space-x-4">
                <a href="#"
                    class="bg-hops-ink text-white rounded-full px-5 py-2 text-sm font-semibold hover:bg-opacity-90 transition">
                    {{ __('Contact') }}
                </a>
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex cursor-pointer items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <x-coolicon-hamburger-md class="w-6 h-6" />
                        </button>
                    </x-slot>
                    
                    @if(Auth::user())
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                    @else
                    <x-slot name="content">
                        <x-dropdown-link :href="route('login')">
                            {{ __('Log In') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('register')">
                            {{ __('Register') }}
                        </x-dropdown-link>
                    </x-slot>
                    @endif
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
