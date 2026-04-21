<x-hops.layout>
    <x-slot:title>{{ __('Hop Varieties Search') }}</x-slot:title>

    <div class="relative w-full bg-white py-20 px-4 sm:px-6 lg:px-8 border-b border-hops-light overflow-hidden">
        <div class="absolute inset-0 flex justify-center items-center pointer-events-none opacity-5">
            <x-lucide-hop class="w-[640px] h-[640px]" />
        </div>

        <div class="relative max-w-3xl mx-auto text-center">
            <h1 class="text-4xl font-extrabold text-hops-ink text-center">
                {{ __('Hop search engine for modern breweries') }}</h1>
            <p class="text-sm text-gray-400 text-center mt-2">
                {{ __('Filter by aroma, alpha-acid content and beer style - All in one place.') }}</p>
            <a href="#"
                class="bg-hops-ink text-white rounded-full px-8 py-3 text-sm font-semibold mx-auto block w-fit mt-6 hover:bg-opacity-90 transition">
                {{ __('See demo') }}
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ showFilters: false }">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h2 class="text-2xl font-extrabold text-hops-ink">{{ __('Hop Varieties') }}</h2>
            <div class="flex flex-wrap items-center gap-3">
                @if (request()->except('page'))
                    <a href="{{ route('hops.index') }}"
                        class="text-sm font-medium text-red-500 hover:text-red-700 transition mr-2 flex items-center gap-1">
                        <x-eva-close class="w-8 h-8" />
                        {{ __('Clear filters') }}
                    </a>
                @endif
                <button @click="showFilters = !showFilters"
                    :class="showFilters ? 'bg-hops-ink text-white' : 'bg-transparent text-hops-ink hover:bg-hops-light'"
                    class="border border-hops-ink rounded-full px-6 py-1.5 text-sm font-bold transition flex items-center gap-2">
                    <x-sui-filtering class="w-5 h-5" />
                    {{ __('Filters') }}
                </button>

                <div class="relative" x-data="{ open: false, searchQuery: '' }" @click.away="open = false">
                    <button @click="open = !open"
                        class="border border-hops-ink rounded-full px-4 py-1.5 text-sm font-medium hover:bg-hops-light transition flex items-center text-hops-ink">
                        {{ __('Region') }}
                        <x-ri-arrow-down-s-line class="w-5 h-5" />
                    </button>
                    <div x-show="open" style="display: none;"
                        class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg z-50 p-4 border border-hops-light">
                        <form action="{{ route('hops.index') }}" method="GET" class="flex flex-col">
                            <div class="mb-3">
                                <input type="text" x-model="searchQuery" placeholder="{{ __('Search region...') }}"
                                    class="w-full text-sm rounded-md border-gray-300 focus:border-hops-mid focus:ring-hops-mid px-3 py-1.5">
                            </div>
                            @foreach (request()->except(['countries', 'page']) as $key => $value)
                                @if (is_array($value))
                                    @foreach ($value as $v)
                                        <input type="hidden" name="{{ $key }}[]"
                                            value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach

                            <div class="overflow-y-auto flex-1 pr-2 space-y-2 mb-4" style="max-height: 15rem;">
                                @foreach ($countries as $country)
                                    <label class="flex items-center gap-2 cursor-pointer group"
                                        x-show="searchQuery === '' || '{{ strtolower(addslashes($country)) }}'.includes(searchQuery.toLowerCase())">
                                        <input type="checkbox" name="countries[]" value="{{ $country }}"
                                            {{ in_array($country, request('countries', [])) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 text-hops-mid focus:ring-hops-mid">
                                        <span
                                            class="text-sm text-hops-ink group-hover:text-hops-mid transition">{{ $country }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-auto border-t border-gray-100 pt-3">
                                <button type="submit"
                                    class="w-full bg-hops-ink text-white rounded-md py-1.5 text-sm font-semibold hover:opacity-90 transition">
                                    {{ __('Apply') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-8">
            <div x-show="showFilters" style="display: none;" class="w-80 flex-shrink-0">
                <x-hops.filters :filters="$filters" />
            </div>

            <div class="flex-grow">
                @if ($hops->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-hops-light p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-hops-ink">{{ __('No hops found') }}</h3>
                        <p class="mt-2 text-sm text-gray-400">
                            {{ __('Try adjusting your filters or clear them to see more varieties.') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('hops.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-hops-ink bg-hops-accent hover:opacity-90 transition">
                                {{ __('Clear filters') }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6"
                        :class="showFilters ? 'lg:grid-cols-3' : 'lg:grid-cols-4'">
                        @foreach ($hops as $hop)
                            <x-hops.hop-card :hop="$hop" />
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $hops->links('components.pagination') }}
                    </div>
                @endif
            </div>
        </div>

        <div
            class="mt-20 bg-hops-ink rounded-3xl overflow-hidden mx-auto max-w-3xl flex flex-col md:flex-row items-center p-8 gap-8 justify-between relative shadow-xl">
            <div class="text-hops-accent shrink-0">
                <x-lucide-hop class="w-16 h-16" />
            </div>
            <div class="flex flex-col md:flex-row items-center gap-6 w-full justify-between">
                <h2 class="text-2xl font-extrabold text-white text-center md:text-left">
                    {{ __('Discover the full hop database') }}</h2>
                <a href="{{ route('hops.index') }}"
                    class="shrink-0 bg-hops-accent text-hops-ink rounded-full px-6 py-2 text-sm font-semibold hover:opacity-90 transition">
                    {{ __('Search now') }}
                </a>
            </div>
        </div>
    </div>
</x-hops.layout>
