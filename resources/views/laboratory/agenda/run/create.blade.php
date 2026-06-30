<x-hops.layout>
    <x-slot:title>{{ __('New Parameter Set') }}</x-slot:title>

    <div class="relative w-full bg-white py-12 px-4 sm:px-6 lg:px-8 border-b border-hops-light overflow-hidden">
        <div class="absolute inset-0 flex justify-center items-center pointer-events-none opacity-5">
            <x-lucide-sliders-horizontal class="w-[640px] h-[640px]" />
        </div>

        <div class="relative max-w-4xl mx-auto text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-hops-light text-hops-mid border border-hops-mid/20 mb-4">
                <x-lucide-beaker class="w-3.5 h-3.5" />
                {{ __('Virtual Laboratory Workspace') }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-hops-ink text-center tracking-tight">
                {{ __('Configure Parameter Set') }}
            </h1>
            <p class="text-sm text-gray-500 text-center mt-2 max-w-xl mx-auto">
                {{ __('Adjust module and biochemical weights for agenda :name.', ['name' => $agenda->name]) }}
            </p>
        </div>
    </div>

    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
        x-data="{
            moduleWeights: {
                aroma: {{ old('module_weights.aroma', 0.25) }},
                biochemical: {{ old('module_weights.biochemical', 0.25) }},
                description: {{ old('module_weights.description', 0.25) }},
                feeling: {{ old('module_weights.feeling', 0.25) }}
            },
            biochemicalWeights: {
                alpha_acid: {{ old('biochemical_weights.alpha_acid', 0.125) }},
                beta_acid: {{ old('biochemical_weights.beta_acid', 0.125) }},
                cohumulone: {{ old('biochemical_weights.cohumulone', 0.125) }},
                total_oil: {{ old('biochemical_weights.total_oil', 0.125) }},
                polyphenol: {{ old('biochemical_weights.polyphenol', 0.125) }},
                xanthohumol: {{ old('biochemical_weights.xanthohumol', 0.125) }},
                farnesene: {{ old('biochemical_weights.farnesene', 0.125) }},
                linalool: {{ old('biochemical_weights.linalool', 0.125) }}
            },

            setModuleWeight(key, rawValue) {
                const value = Math.min(1, Math.max(0, parseFloat(rawValue) || 0));
                this.moduleWeights[key] = value;
                this.redistribute(this.moduleWeights, key);
            },

            setBiochemicalWeight(key, rawValue) {
                const value = Math.min(1, Math.max(0, parseFloat(rawValue) || 0));
                this.biochemicalWeights[key] = value;
                this.redistribute(this.biochemicalWeights, key);
            },

            redistribute(weights, changedKey) {
                const others = Object.keys(weights).filter(k => k !== changedKey);
                const remaining = Math.max(0, 1.0 - weights[changedKey]);
                const otherSum = others.reduce((s, k) => s + weights[k], 0);

                if (otherSum < 0.0001) {
                    const share = remaining / others.length;
                    others.forEach(k => { weights[k] = Math.round(share * 10000) / 10000; });
                } else {
                    others.forEach(k => {
                        weights[k] = Math.round((weights[k] / otherSum) * remaining * 10000) / 10000;
                    });
                }

                const sum = Object.values(weights).reduce((s, v) => s + v, 0);
                const diff = Math.round((1.0 - sum) * 10000) / 10000;

                if (Math.abs(diff) > 0) {
                    const lastKey = others[others.length - 1];
                    weights[lastKey] = Math.max(0, Math.round((weights[lastKey] + diff) * 10000) / 10000);
                }
            },

            pct(v) { return (parseFloat(v) * 100).toFixed(0) + '%'; },

            get parametersJson() {
                const fmt = v => Math.round(parseFloat(v) * 10000) / 10000;
                return JSON.stringify({
                    module_weights: Object.fromEntries(Object.entries(this.moduleWeights).map(([k, v]) => [k, fmt(v)])),
                    biochemical_weights: Object.fromEntries(Object.entries(this.biochemicalWeights).map(([k, v]) => [k, fmt(v)]))
                }, null, 2);
            }
        }"
    >
        <div class="mb-6">
            <a href="{{ route('laboratory.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-hops-ink transition">
                <x-lucide-arrow-left class="w-3.5 h-3.5" />
                {{ __('Back to Laboratory') }}
            </a>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex gap-3 items-start">
                <x-lucide-alert-circle class="w-4 h-4 text-red-500 mt-0.5 shrink-0" />
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('laboratory.agendas.runs.store', $agenda) }}">
            @csrf

            {{-- Hidden inputs carry the decimal values the backend validates --}}
            <template x-for="[key, val] in Object.entries(moduleWeights)" :key="'mw-' + key">
                <input type="hidden" :name="'module_weights[' + key + ']'" :value="val" />
            </template>
            <template x-for="[key, val] in Object.entries(biochemicalWeights)" :key="'bw-' + key">
                <input type="hidden" :name="'biochemical_weights[' + key + ']'" :value="val" />
            </template>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- Module Weights --}}
                <div class="bg-white rounded-3xl border border-hops-light shadow-2xs overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-lucide-layers class="w-4 h-4 text-hops-mid" />
                            <span class="text-sm font-bold text-hops-ink">{{ __('Module Weights') }}</span>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('always sums to 100%') }}</span>
                    </div>

                    <div class="p-6 space-y-6">
                        @foreach([
                            'aroma' => ['label' => 'Aroma', 'desc' => 'Sensory aroma profile'],
                            'biochemical' => ['label' => 'Biochemical', 'desc' => 'Chemical composition'],
                            'description' => ['label' => 'Description', 'desc' => 'Textual similarity'],
                            'feeling' => ['label' => 'Feeling', 'desc' => 'Bitterness & aromaticity'],
                        ] as $key => $meta)
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <span class="text-xs font-bold text-hops-ink">{{ __($meta['label']) }}</span>
                                        <span class="block text-[10px] text-gray-400">{{ __($meta['desc']) }}</span>
                                    </div>
                                    <span class="text-sm font-black text-hops-mid font-mono tabular-nums" x-text="pct(moduleWeights.{{ $key }})"></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input
                                        type="range"
                                        :value="moduleWeights.{{ $key }}"
                                        @input="setModuleWeight('{{ $key }}', $event.target.value)"
                                        min="0" max="1" step="0.01"
                                        class="flex-1 h-2 rounded-full accent-hops-accent cursor-pointer"
                                    />
                                    <input
                                        type="number"
                                        :value="Math.round(moduleWeights.{{ $key }} * 100)"
                                        @change="setModuleWeight('{{ $key }}', $event.target.value / 100)"
                                        min="0" max="100" step="1"
                                        class="w-16 text-xs text-center rounded-lg border-hops-warm focus:border-hops-accent focus:ring-hops-accent shadow-sm font-mono"
                                    />
                                    <span class="text-xs text-gray-400 w-3">%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Biochemical Weights --}}
                <div class="bg-white rounded-3xl border border-hops-light shadow-2xs overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-lucide-flask-conical class="w-4 h-4 text-hops-mid" />
                            <span class="text-sm font-bold text-hops-ink">{{ __('Biochemical Weights') }}</span>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('always sums to 100%') }}</span>
                    </div>

                    <div class="p-6 space-y-4">
                        @foreach([
                            'alpha_acid' => 'Alpha Acid (%)',
                            'beta_acid' => 'Beta Acid (%)',
                            'cohumulone' => 'Cohumulone (%)',
                            'total_oil' => 'Total Oil (ml/100g)',
                            'polyphenol' => 'Polyphenols (%)',
                            'xanthohumol' => 'Xanthohumol (%)',
                            'farnesene' => 'Farnesene (%)',
                            'linalool' => 'Linalool (%)',
                        ] as $key => $label)
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-bold text-hops-ink">{{ __($label) }}</span>
                                    <span class="text-sm font-black text-hops-mid font-mono tabular-nums" x-text="pct(biochemicalWeights.{{ $key }})"></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input
                                        type="range"
                                        :value="biochemicalWeights.{{ $key }}"
                                        @input="setBiochemicalWeight('{{ $key }}', $event.target.value)"
                                        min="0" max="1" step="0.01"
                                        class="flex-1 h-2 rounded-full accent-hops-accent cursor-pointer"
                                    />
                                    <input
                                        type="number"
                                        :value="Math.round(biochemicalWeights.{{ $key }} * 100)"
                                        @change="setBiochemicalWeight('{{ $key }}', $event.target.value / 100)"
                                        min="0" max="100" step="1"
                                        class="w-16 text-xs text-center rounded-lg border-hops-warm focus:border-hops-accent focus:ring-hops-accent shadow-sm font-mono"
                                    />
                                    <span class="text-xs text-gray-400 w-3">%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Weight Summary --}}
            <div class="mt-6 grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- Module Weights Summary --}}
                <div class="bg-white rounded-3xl border border-hops-light shadow-2xs overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-2">
                        <x-lucide-pie-chart class="w-4 h-4 text-hops-mid" />
                        <span class="text-sm font-bold text-hops-ink">{{ __('Module Weights Summary') }}</span>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach([
                            'aroma' => 'Aroma',
                            'biochemical' => 'Biochemical',
                            'description' => 'Description',
                            'feeling' => 'Feeling',
                        ] as $key => $label)
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500 w-24 shrink-0">{{ __($label) }}</span>
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-hops-accent rounded-full transition-all duration-200" :style="'width: ' + (moduleWeights.{{ $key }} * 100) + '%'"></div>
                                </div>
                                <span class="text-xs font-bold text-hops-mid font-mono tabular-nums w-10 text-right" x-text="pct(moduleWeights.{{ $key }})"></span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Biochemical Weights Summary --}}
                <div class="bg-white rounded-3xl border border-hops-light shadow-2xs overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-2">
                        <x-lucide-pie-chart class="w-4 h-4 text-hops-mid" />
                        <span class="text-sm font-bold text-hops-ink">{{ __('Biochemical Weights Summary') }}</span>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach([
                            'alpha_acid' => 'Alpha Acid',
                            'beta_acid' => 'Beta Acid',
                            'cohumulone' => 'Cohumulone',
                            'total_oil' => 'Total Oil',
                            'polyphenol' => 'Polyphenols',
                            'xanthohumol' => 'Xanthohumol',
                            'farnesene' => 'Farnesene',
                            'linalool' => 'Linalool',
                        ] as $key => $label)
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500 w-24 shrink-0">{{ __($label) }}</span>
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-hops-accent rounded-full transition-all duration-200" :style="'width: ' + (biochemicalWeights.{{ $key }} * 100) + '%'"></div>
                                </div>
                                <span class="text-xs font-bold text-hops-mid font-mono tabular-nums w-10 text-right" x-text="pct(biochemicalWeights.{{ $key }})"></span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- JSON Preview --}}
            <div class="mt-6 bg-white rounded-3xl border border-hops-light shadow-2xs overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <x-lucide-code class="w-4 h-4 text-hops-mid" />
                        <span class="text-sm font-bold text-hops-ink">{{ __('Generated Parameter Set JSON') }}</span>
                    </div>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">{{ __('Live Preview') }}</span>
                </div>
                <div class="p-6">
                    <div class="bg-gray-900 rounded-xl p-5 border border-gray-800">
                        <pre class="font-mono text-xs text-green-400 overflow-x-auto leading-relaxed whitespace-pre" x-text="parametersJson"></pre>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('laboratory.index') }}" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-hops-ink transition">
                    {{ __('Cancel') }}
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-hops-ink text-white text-xs font-bold rounded-xl hover:bg-opacity-90 transition cursor-pointer"
                >
                    <x-lucide-save class="w-3.5 h-3.5" />
                    {{ __('Save Parameter Set') }}
                </button>
            </div>
        </form>
    </div>
</x-hops.layout>
