<x-hops.layout>
    <x-slot:title>{{ __('Hop Similarity Comparison Engine') }}</x-slot:title>

    <div class="relative w-full bg-white py-12 px-4 sm:px-6 lg:px-8 border-b border-hops-light overflow-hidden">
        <div class="absolute inset-0 flex justify-center items-center pointer-events-none opacity-5">
            <x-lucide-hop class="w-[640px] h-[640px]" />
        </div>

        <div class="relative max-w-4xl mx-auto text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-hops-light text-hops-mid border border-hops-mid/20 mb-4">
                <x-phosphor-scales-bold class="w-3.5 h-3.5" />
                {{ __('Comparison Engine Workspace') }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-hops-ink text-center tracking-tight">
                {{ __('Find The Perfect Hop Match') }}
            </h1>
            <p class="text-sm text-gray-500 text-center mt-2 max-w-xl mx-auto">
                {{ __('Query our advanced similarity engine using natural language descriptions or construct structured constraints via visual and JSON configurations.') }}
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{
        activeTab: '{{ ($activeQuery && !isset($activeQuery->query['_nlp_query'])) ? 'form' : 'nlp' }}',
        nlpText: '{{ $activeQuery->query['_nlp_query'] ?? '' }}',
        suggestions: [
            '{{ __('I want a citrusy and fruity hop with high bitterness') }}',
            '{{ __('Looking for Saaz substitute with floral notes and low alpha acid') }}',
            '{{ __('High total oil hop with herbal aroma and medium bitterness') }}'
        ]
    }">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            <x-hops.comparison.history :history="$history" :activeQuery="$activeQuery" />

            <div class="flex-grow w-full space-y-8">
                
                <div class="bg-white rounded-3xl border border-hops-light shadow-sm overflow-hidden">
                    <div class="flex border-b border-gray-100 bg-gray-50/50 p-2 gap-1">
                        <button @click="activeTab = 'nlp'"
                                :class="activeTab === 'nlp' ? 'bg-white text-hops-ink shadow-xs border border-gray-100' : 'text-gray-500 hover:text-hops-ink hover:bg-gray-100/50'"
                                class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <x-lucide-message-circle class="w-4 h-4" />
                            {{ __('Natural Language') }}
                        </button>
                        <button @click="activeTab = 'form'"
                                :class="activeTab === 'form' ? 'bg-white text-hops-ink shadow-xs border border-gray-100' : 'text-gray-500 hover:text-hops-ink hover:bg-gray-100/50'"
                                class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <x-sui-filtering class="w-4 h-4" />
                            {{ __('JSON & Visual Builder') }}
                        </button>
                    </div>

                    <div class="p-6">
                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                                <h4 class="text-xs font-bold text-red-700 uppercase tracking-wider mb-2 flex items-center gap-1">
                                    <x-eva-alert-circle class="w-4 h-4" />
                                    {{ __('Validation Errors') }}
                                </h4>
                                <ul class="list-disc pl-4 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-xs text-red-600 font-medium">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <x-hops.comparison.nlp-form :activeQuery="$activeQuery" />

                        <x-hops.comparison.builder-form :activeQuery="$activeQuery" />

                    </div>
                </div>

                <x-hops.comparison.results :activeQuery="$activeQuery" :results="$results" :hops="$hops" />

            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('jsonBuilder', (initialQuery) => {
                const defaultQuery = {
                    target: { present: [], absent: [] },
                    aroma: { present: [], absent: [] },
                    description: { present: [], absent: [] },
                    ingredients: {
                        alphas: { enabled: false, min: null, max: null },
                        betas: { enabled: false, min: null, max: null },
                        cohumulones: { enabled: false, min: null, max: null },
                        polyphenols: { enabled: false, min: null, max: null },
                        xanthohumol: { enabled: false, min: null, max: null },
                        oils: { enabled: false, min: null, max: null },
                        farnesenes: { enabled: false, min: null, max: null },
                        linalool: { enabled: false, min: null, max: null }
                    },
                    feeling: { bitterness: '', aromaticity: '' }
                };

                let activeQuery = defaultQuery;
                if (initialQuery) {
                    activeQuery = { ...defaultQuery };
                    
                    if (initialQuery.target) {
                        activeQuery.target = {
                            present: initialQuery.target.present || [],
                            absent: initialQuery.target.absent || []
                        };
                    }
                    if (initialQuery.aroma) {
                        activeQuery.aroma = {
                            present: initialQuery.aroma.present || [],
                            absent: initialQuery.aroma.absent || []
                        };
                    }
                    if (initialQuery.description) {
                        activeQuery.description = {
                            present: initialQuery.description.present || [],
                            absent: initialQuery.description.absent || []
                        };
                    }
                    if (initialQuery.feeling) {
                        activeQuery.feeling = {
                            bitterness: initialQuery.feeling.bitterness || '',
                            aromaticity: initialQuery.feeling.aromaticity || ''
                        };
                    }
                    
                    if (initialQuery.ingredients) {
                        for (let k in activeQuery.ingredients) {
                            if (initialQuery.ingredients[k] !== undefined && initialQuery.ingredients[k] !== null) {
                                activeQuery.ingredients[k].enabled = true;
                                if (typeof initialQuery.ingredients[k] === 'object') {
                                    activeQuery.ingredients[k].min = initialQuery.ingredients[k].min ?? null;
                                    activeQuery.ingredients[k].max = initialQuery.ingredients[k].max ?? null;
                                } else {
                                    activeQuery.ingredients[k].min = initialQuery.ingredients[k];
                                    activeQuery.ingredients[k].max = initialQuery.ingredients[k];
                                }
                            }
                        }
                    }
                }

                const formInput = {
                    target_present: activeQuery.target.present.join(', '),
                    target_absent: activeQuery.target.absent.join(', '),
                    description_present: activeQuery.description.present.join(', '),
                    description_absent: activeQuery.description.absent.join(', ')
                };

                return {
                    query: activeQuery,
                    formInput: formInput,
                    rawJson: '{}',
                    
                    ingredientMeta: {
                        alphas: { label: 'Alpha Acid (%)' },
                        betas: { label: 'Beta Acid (%)' },
                        cohumulones: { label: 'Cohumulone (%)' },
                        polyphenols: { label: 'Polyphenols (%)' },
                        xanthohumol: { label: 'Xanthohumol (%)' },
                        oils: { label: 'Total Oil (ml/100g)' },
                        farnesenes: { label: 'Farnesene (%)' },
                        linalool: { label: 'Linalool (%)' }
                    },

                    init() {
                        this.generateJson();
                    },

                    updateTarget() {
                        this.query.target.present = this.formInput.target_present
                            .split(',')
                            .map(x => x.trim())
                            .filter(x => x.length > 0);
                        this.query.target.absent = this.formInput.target_absent
                            .split(',')
                            .map(x => x.trim())
                            .filter(x => x.length > 0);
                        this.generateJson();
                    },

                    updateDescription() {
                        this.query.description.present = this.formInput.description_present
                            .split(',')
                            .map(x => x.trim())
                            .filter(x => x.length > 0);
                        this.query.description.absent = this.formInput.description_absent
                            .split(',')
                            .map(x => x.trim())
                            .filter(x => x.length > 0);
                        this.generateJson();
                    },

                    generateJson() {
                        const payload = {};

                        if (this.query.target.present.length > 0 || this.query.target.absent.length > 0) {
                            payload.target = {};
                            if (this.query.target.present.length > 0) payload.target.present = this.query.target.present;
                            if (this.query.target.absent.length > 0) payload.target.absent = this.query.target.absent;
                        }

                        if (this.query.aroma.present.length > 0 || this.query.aroma.absent.length > 0) {
                            payload.aroma = {};
                            if (this.query.aroma.present.length > 0) payload.aroma.present = this.query.aroma.present;
                            if (this.query.aroma.absent.length > 0) payload.aroma.absent = this.query.aroma.absent;
                        }

                        if (this.query.description.present.length > 0 || this.query.description.absent.length > 0) {
                            payload.description = {};
                            if (this.query.description.present.length > 0) payload.description.present = this.query.description.present;
                            if (this.query.description.absent.length > 0) payload.description.absent = this.query.description.absent;
                        }

                        if (this.query.feeling.bitterness || this.query.feeling.aromaticity) {
                            payload.feeling = {};
                            if (this.query.feeling.bitterness) payload.feeling.bitterness = this.query.feeling.bitterness;
                            if (this.query.feeling.aromaticity) payload.feeling.aromaticity = this.query.feeling.aromaticity;
                        }

                        let hasIngredients = false;
                        const ingPayload = {};
                        for (let k in this.query.ingredients) {
                            const spec = this.query.ingredients[k];
                            if (spec.enabled) {
                                hasIngredients = true;
                                if (spec.min !== null && spec.max !== null) {
                                    if (spec.min === spec.max) {
                                        ingPayload[k] = spec.min;
                                    } else {
                                        ingPayload[k] = { min: spec.min, max: spec.max };
                                    }
                                } else if (spec.min !== null) {
                                    ingPayload[k] = { min: spec.min };
                                } else if (spec.max !== null) {
                                    ingPayload[k] = { max: spec.max };
                                } else {
                                    ingPayload[k] = null;
                                }
                            }
                        }
                        if (hasIngredients) {
                            payload.ingredients = ingPayload;
                        }

                        this.rawJson = JSON.stringify(payload, null, 2);
                    },

                    parseJson() {
                        try {
                            const parsed = JSON.parse(this.rawJson);
                            if (parsed && typeof parsed === 'object') {
                                
                                this.query.target.present = (parsed.target && parsed.target.present) || [];
                                this.query.target.absent = (parsed.target && parsed.target.absent) || [];
                                this.formInput.target_present = this.query.target.present.join(', ');
                                this.formInput.target_absent = this.query.target.absent.join(', ');

                                this.query.description.present = (parsed.description && parsed.description.present) || [];
                                this.query.description.absent = (parsed.description && parsed.description.absent) || [];
                                this.formInput.description_present = this.query.description.present.join(', ');
                                this.formInput.description_absent = this.query.description.absent.join(', ');

                                this.query.aroma.present = (parsed.aroma && parsed.aroma.present) || [];
                                this.query.aroma.absent = (parsed.aroma && parsed.aroma.absent) || [];

                                this.query.feeling.bitterness = (parsed.feeling && parsed.feeling.bitterness) || '';
                                this.query.feeling.aromaticity = (parsed.feeling && parsed.feeling.aromaticity) || '';

                                for (let k in this.query.ingredients) {
                                    const ing = parsed.ingredients && parsed.ingredients[k];
                                    if (ing !== undefined && ing !== null) {
                                        this.query.ingredients[k].enabled = true;
                                        if (typeof ing === 'object') {
                                            this.query.ingredients[k].min = ing.min ?? null;
                                            this.query.ingredients[k].max = ing.max ?? null;
                                        } else {
                                            this.query.ingredients[k].min = ing;
                                            this.query.ingredients[k].max = ing;
                                        }
                                    } else {
                                        this.query.ingredients[k].enabled = false;
                                        this.query.ingredients[k].min = null;
                                        this.query.ingredients[k].max = null;
                                    }
                                }

                            }
                        } catch (e) {
                        }
                    }
                };
            });
        });
    </script>
</x-hops.layout>
