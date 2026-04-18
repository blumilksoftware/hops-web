<x-hops.layout>
    <x-slot:title>{{ $hop->name }} - Hop Details</x-slot:title>

    <div class="bg-hops-light selection:bg-hops-accent selection:text-hops-ink min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-2">
                    <li>
                        <a href="{{ route('hops.index') }}"
                            class="text-sm font-medium text-gray-500 hover:text-hops-ink transition">{{ __('Varieties') }}</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2 text-sm font-medium text-hops-mid">{{ $hop->name }}</span>
                    </li>
                </ol>
            </nav>

            <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">
                <div class="flex flex-col bg-white p-8 rounded-3xl border border-hops-light shadow-sm">
                    <h2 class="text-4xl font-extrabold tracking-tight text-hops-ink sm:text-5xl mb-4">
                        {{ $hop->name }}</h2>
                    <div class="flex items-center space-x-4 mb-8">
                        <span
                            class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold text-hops-mid  border-hops-mid">
                            {{ $hop->country ?? __('Various') }}
                        </span>
                        <span class="text-gray-400 text-sm italic">
                            {{ $hop->alt_name ? __('Also known as:') . " {$hop->alt_name}" : '' }}
                        </span>
                    </div>

                    <div class="prose max-w-none text-gray-500 mb-8">
                        <p>{{ $hop->description ?? __('Detailed description for this hop variety is currently being compiled.') }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-hops-ink uppercase tracking-wider mb-4">
                            {{ __('Aroma Profile') }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($hop->getActiveAromas() as $index => $aroma)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border border-gray-300">
                                    {{ $aroma->label() }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-sm font-bold text-hops-ink uppercase tracking-wider mb-4">
                            {{ __('Aroma Descriptors') }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @if ($hop->aroma_descriptors)
                                @foreach ($hop->aroma_descriptors as $descriptor)
                                    <span
                                        class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-white text-hops-mid border border-hops-mid">
                                        {{ $descriptor }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-gray-400 text-sm italic">{{ __('No descriptors available') }}</span>
                            @endif
                        </div>
                    </div>

                    @if ($hop->substitutes)
                        <div class="mt-10 border-t border-hops-light pt-10">
                            <h3 class="text-sm font-bold text-hops-ink uppercase tracking-wider mb-4">
                                {{ __('Possible Substitutes') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase mb-2">
                                        {{ __('Brewhouse') }}</h4>
                                    <ul class="text-sm text-hops-ink space-y-1">
                                        @foreach ($hop->substitutes['brewhouse'] ?? [] as $sub)
                                            <li>• {{ $sub }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase mb-2">
                                        {{ __('Dry Hopping') }}</h4>
                                    <ul class="text-sm text-hops-ink space-y-1">
                                        @foreach ($hop->substitutes['dryhopping'] ?? [] as $sub)
                                            <li>• {{ $sub }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-16 lg:mt-0 bg-white rounded-3xl p-8 border border-hops-light shadow-sm">
                    <h3 class="text-xl font-bold text-hops-ink mb-8 border-b border-hops-light pb-4">
                        {{ __('Biochemical Profile') }}</h3>

                    <div class="space-y-6">
                        @foreach ([
        'alpha_acid' => __('Alpha Acid (%)'),
        'beta_acid' => __('Beta Acid (%)'),
        'cohumulone' => __('Cohumulone (%)'),
        'total_oil' => __('Total Oil (ml/100g)'),
        'polyphenol' => __('Polyphenols (%)'),
        'xanthohumol' => __('Xanthohumol (%)'),
        'farnesene' => __('Farnesene (%)'),
        'linalool' => __('Linalool (%)'),
    ] as $field => $label)
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-400 font-medium">{{ $label }}</span>
                                    <span class="text-hops-ink font-bold">
                                        @if ($hop->{$field})
                                            @if ($hop->{$field}->min === $hop->{$field}->max)
                                                {{ $hop->{$field}->min }}
                                            @else
                                                {{ $hop->{$field}->min }} - {{ $hop->{$field}->max }}
                                            @endif
                                        @else
                                            {{ __('N/A') }}
                                        @endif
                                    </span>
                                </div>
                                <div
                                    class="relative w-full h-2 bg-hops-light rounded-full overflow-hidden border border-gray-100">
                                    <div class="absolute top-0 bottom-0 bg-hops-accent" style="left: 0%; right: 0%;">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12 grid grid-cols-2 gap-6">
                        <div class="bg-hops-light p-4 rounded-xl border border-hops-light text-center">
                            <span
                                class="text-xs font-semibold text-gray-400 uppercase block mb-1">{{ __('Bitterness') }}</span>
                            <span
                                class="text-lg font-bold text-hops-ink capitalize">{{ $hop->bitterness ? __($hop->bitterness->value) : __('Balanced') }}</span>
                        </div>
                        <div class="bg-hops-light p-4 rounded-xl border border-hops-light text-center">
                            <span
                                class="text-xs font-semibold text-gray-400 uppercase block mb-1">{{ __('Aromaticity') }}</span>
                            <span
                                class="text-lg font-bold text-hops-ink capitalize">{{ $hop->aromaticity ? __($hop->aromaticity->value) : __('Medium') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-hops.layout>
