<x-hops.layout>
    <x-slot:title>Browse Hop Varieties</x-slot:title>

    <div class="flex h-full">
        <x-hops.filters :filters="$filters" />

        <div class="flex-grow bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Varieties</h2>
                        <p class="mt-2 text-sm text-gray-500">
                            Discover the perfect hops for your next brew.
                        </p>
                    </div>
                    <div class="text-sm font-medium text-gray-500">
                        Showing {{ $hops->firstItem() ?? 0 }}-{{ $hops->lastItem() ?? 0 }} of {{ $hops->total() }} hops
                    </div>
                </div>

                @if($hops->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No hops found</h3>
                        <p class="mt-2 text-sm text-gray-500">Try adjusting your filters or clearing them to see more varieties.</p>
                        <div class="mt-6">
                            <a href="{{ route('hops.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                Clear Filters
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                        @foreach($hops as $hop)
                            <x-hops.hop-card :hop="$hop" />
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $hops->links('components.hops.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-hops.layout>
