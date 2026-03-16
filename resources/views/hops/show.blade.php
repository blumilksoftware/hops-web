<x-hops.layout>
    <x-slot:title>{{ $hop->name }} - Hop Details</x-slot:title>

    <div class="bg-white">
        <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-2">
                    <li>
                        <a href="{{ route('hops.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Varieties</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2 text-sm font-medium text-green-600">{{ $hop->name }}</span>
                    </li>
                </ol>
            </nav>

            <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">
                <!-- Info Summary -->
                <div class="flex flex-col">
                    <h2 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl mb-4">{{ $hop->name }}</h2>
                    <div class="flex items-center space-x-4 mb-8">
                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800">
                             {{ $hop->country ?? 'Various' }}
                        </span>
                        <span class="text-gray-500 text-sm italic">
                            {{ $hop->alt_name ? "Also known as: {$hop->alt_name}" : "" }}
                        </span>
                    </div>

                    <div class="prose prose-green text-gray-500 max-w-none">
                        <p>{{ $hop->description ?? 'Detailed description for this hop variety is currently being compiled.' }}</p>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Aroma Descriptors</h3>
                        <div class="flex flex-wrap gap-2">
                            @if($hop->aroma_descriptors)
                                @foreach($hop->aroma_descriptors as $descriptor)
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-50 text-green-700 border border-green-100">
                                        {{ $descriptor }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-gray-400 text-sm italic">No descriptors available</span>
                            @endif
                        </div>
                    </div>

                    @if($hop->substitutes)
                        <div class="mt-10 border-t border-gray-100 pt-10">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Possible Substitutes</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase mb-2">Brewhouse</h4>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        @foreach($hop->substitutes['brewhouse'] ?? [] as $sub)
                                            <li>• {{ $sub }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-400 uppercase mb-2">Dry Hopping</h4>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        @foreach($hop->substitutes['dryhopping'] ?? [] as $sub)
                                            <li>• {{ $sub }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Biochemical Profile -->
                <div class="mt-16 lg:mt-0 bg-gray-50 rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-900 mb-8 border-b border-gray-200 pb-4">Biochemical Profile</h3>
                    
                    <div class="space-y-6">
                        @foreach([
                            'alpha_acid' => 'Alpha Acid (%)',
                            'beta_acid' => 'Beta Acid (%)',
                            'cohumulone' => 'Cohumulone (%)',
                            'total_oil' => 'Total Oil (ml/100g)',
                            'polyphenol' => 'Polyphenols (%)',
                            'xanthohumol' => 'Xanthohumol (%)',
                            'farnesene' => 'Farnesene (%)',
                            'linalool' => 'Linalool (%)'
                        ] as $field => $label)
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-500 font-medium">{{ $label }}</span>
                                    <span class="text-green-700 font-bold">
                                        @if($hop->{$field})
                                            @if($hop->{$field}->min === $hop->{$field}->max)
                                                {{ $hop->{$field}->min }}
                                            @else
                                                {{ $hop->{$field}->min }} - {{ $hop->{$field}->max }}
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="relative w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                     <div class="absolute top-0 bottom-0 bg-green-500 opacity-20" style="left: 0%; right: 0%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12 grid grid-cols-2 gap-6">
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                             <span class="text-xs font-semibold text-gray-400 uppercase block mb-1">Bitterness</span>
                             <span class="text-lg font-bold text-gray-900 capitalize">{{ $hop->bitterness->value ?? 'Balanced' }}</span>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                             <span class="text-xs font-semibold text-gray-400 uppercase block mb-1">Aromaticity</span>
                             <span class="text-lg font-bold text-gray-900 capitalize">{{ $hop->aromaticity->value ?? 'Medium' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-hops.layout>
