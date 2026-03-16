@props(['filters'])

<aside class="bg-white border-r border-gray-200 w-80 flex-shrink-0 hidden lg:block overflow-y-auto"
       x-data="{ 
           autoSubmit() {
               this.$refs.filterForm.submit();
           }
       }">
    <div class="p-6">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-lg font-bold text-gray-900">Filters</h2>
            <a href="{{ route('hops.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">Clear all</a>
        </div>

        <form action="{{ route('hops.index') }}" method="GET" x-ref="filterForm">
            <div class="space-y-8">
                <!-- Aroma Categories -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Aroma Profile</h3>
                    <div class="space-y-3">
                        @foreach([
                            'aroma_citrusy' => 'Citrusy',
                            'aroma_fruity' => 'Fruity',
                            'aroma_floral' => 'Floral',
                            'aroma_herbal' => 'Herbal',
                            'aroma_spicy' => 'Spicy',
                            'aroma_resinous' => 'Resinous',
                            'aroma_sugarlike' => 'Sweet/Sugarlike',
                            'aroma_misc' => 'Miscellaneous'
                        ] as $key => $label)
                            <label class="flex items-center group cursor-pointer">
                                <input type="checkbox" name="{{ $key }}" value="1" 
                                       @change="autoSubmit()"
                                       {{ ($filters[$key] ?? '') == '1' ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-900">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Range Sliders (Simplified as Number Inputs for Filter) -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Biochemical Properties</h3>
                    <div class="space-y-6">
                        @foreach([
                            'alpha_acid' => 'Alpha Acid (%)',
                            'beta_acid' => 'Beta Acid (%)',
                            'cohumulone' => 'Cohumulone (%)',
                            'total_oil' => 'Total Oil (ml/100g)'
                        ] as $key => $label)
                            <div class="space-y-2">
                                <span class="text-xs font-medium text-gray-500 uppercase">{{ $label }}</span>
                                <div class="flex items-center space-x-2">
                                    <input type="number" name="{{ $key }}_min" value="{{ $filters[$key . '_min'] ?? '' }}" 
                                           placeholder="Min" step="0.1"
                                           @change.debounce.500ms="autoSubmit()"
                                           class="w-full text-xs rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500">
                                    <span class="text-gray-400">-</span>
                                    <input type="number" name="{{ $key }}_max" value="{{ $filters[$key . '_max'] ?? '' }}" 
                                           placeholder="Max" step="0.1"
                                           @change.debounce.500ms="autoSubmit()"
                                           class="w-full text-xs rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Enums -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Characteristics</h3>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-gray-500 uppercase">Bitterness</label>
                            <select name="bitterness" @change="autoSubmit()"
                                    class="w-full text-sm rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500">
                                <option value="all">Any</option>
                                @foreach(\HopsWeb\Enums\Bitterness::cases() as $case)
                                    <option value="{{ $case->value }}" {{ ($filters['bitterness'] ?? '') == $case->value ? 'selected' : '' }}>
                                        {{ ucfirst($case->value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-gray-500 uppercase">Aromaticity</label>
                            <select name="aromaticity" @change="autoSubmit()"
                                    class="w-full text-sm rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500">
                                <option value="all">Any</option>
                                @foreach(\HopsWeb\Enums\Aromaticity::cases() as $case)
                                    <option value="{{ $case->value }}" {{ ($filters['aromaticity'] ?? '') == $case->value ? 'selected' : '' }}>
                                        {{ ucfirst($case->value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <noscript>
                <div class="mt-8">
                    <button type="submit" class="w-full bg-green-700 text-white rounded-md py-2 text-sm font-semibold hover:bg-green-800 transition">
                        Apply Filters
                    </button>
                </div>
            </noscript>
        </form>
    </div>
</aside>
