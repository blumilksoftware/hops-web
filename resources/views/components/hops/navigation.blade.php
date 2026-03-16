<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-12 items-center">
            <div class="flex space-x-8">
                <a href="{{ route('hops.index') }}" 
                   class="{{ request()->routeIs('hops.index') ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-green-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    All Varieties
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                    About Hops
                </a>
            </div>
        </div>
    </div>
</nav>
