@props([
    'activeQuery' => null
])

<div x-show="activeTab === 'form'" style="display: none;" x-data="jsonBuilder({{ json_encode($activeQuery ? $activeQuery->query : null) }})">
    <form action="{{ route('comparison.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="type" value="form">
        
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            
            <div class="xl:col-span-7 space-y-6">
                
                <div class="bg-gray-50/50 rounded-2xl border border-gray-100 p-4 space-y-4">
                    <h4 class="text-xs font-bold text-hops-darkest uppercase tracking-wide border-b border-gray-200/50 pb-2">
                        {{ __('1. Name Targets & Descriptors') }}
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('Target Hops (Present)') }}
                            </label>
                            <input type="text" 
                                   placeholder="e.g., Citra, Mosaic"
                                   x-model="formInput.target_present"
                                   @input="updateTarget()"
                                   class="w-full text-xs rounded-xl border-gray-200 focus:border-hops-mid focus:ring-hops-mid px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('Target Hops (Absent)') }}
                            </label>
                            <input type="text" 
                                   placeholder="e.g., Cascade"
                                   x-model="formInput.target_absent"
                                   @input="updateTarget()"
                                   class="w-full text-xs rounded-xl border-gray-200 focus:border-hops-mid focus:ring-hops-mid px-3 py-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('Description Words (Present)') }}
                            </label>
                            <input type="text" 
                                   placeholder="e.g., tropical, juice"
                                   x-model="formInput.description_present"
                                   @input="updateDescription()"
                                   class="w-full text-xs rounded-xl border-gray-200 focus:border-hops-mid focus:ring-hops-mid px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('Description Words (Absent)') }}
                            </label>
                            <input type="text" 
                                   placeholder="e.g., piney, garlic"
                                   x-model="formInput.description_absent"
                                   @input="updateDescription()"
                                   class="w-full text-xs rounded-xl border-gray-200 focus:border-hops-mid focus:ring-hops-mid px-3 py-2">
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50/50 rounded-2xl border border-gray-100 p-4 space-y-4">
                    <h4 class="text-xs font-bold text-hops-darkest uppercase tracking-wide border-b border-gray-200/50 pb-2">
                        {{ __('2. Aroma Profiles') }}
                    </h4>

                    <div>
                        <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">
                            {{ __('Include Aromas (Present)') }}
                        </span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach(\HopsWeb\Enums\HopDescriptor::cases() as $desc)
                                <label class="flex items-center gap-1.5 cursor-pointer text-xs">
                                    <input type="checkbox" 
                                           value="{{ $desc->value }}"
                                           x-model="query.aroma.present"
                                           @change="generateJson()"
                                           class="rounded border-gray-300 text-hops-mid focus:ring-hops-mid w-4 h-4">
                                    <span class="font-medium text-hops-ink">{{ __($desc->value) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-2 border-t border-dashed border-gray-200">
                        <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">
                            {{ __('Exclude Aromas (Absent)') }}
                        </span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach(\HopsWeb\Enums\HopDescriptor::cases() as $desc)
                                <label class="flex items-center gap-1.5 cursor-pointer text-xs">
                                    <input type="checkbox" 
                                           value="{{ $desc->value }}"
                                           x-model="query.aroma.absent"
                                           @change="generateJson()"
                                           class="rounded border-gray-300 text-red-500 focus:ring-red-400 w-4 h-4">
                                    <span class="font-medium text-hops-ink">{{ __($desc->value) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50/50 rounded-2xl border border-gray-100 p-4 space-y-4">
                    <h4 class="text-xs font-bold text-hops-darkest uppercase tracking-wide border-b border-gray-200/50 pb-2">
                        {{ __('3. Biochemical Properties (Ranges)') }}
                    </h4>

                    <div class="space-y-3.5">
                        <template x-for="(spec, key) in ingredientMeta" :key="key">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-2.5 last:border-0 last:pb-0">
                                <div class="flex items-center gap-2 sm:w-1/3">
                                    <input type="checkbox" 
                                           x-model="query.ingredients[key].enabled"
                                           @change="generateJson()"
                                           :id="'enable-' + key"
                                           class="rounded border-gray-300 text-hops-mid focus:ring-hops-mid w-4 h-4">
                                    <label :for="'enable-' + key" class="text-xs font-bold text-hops-ink uppercase cursor-pointer" x-text="spec.label"></label>
                                </div>

                                <div class="flex items-center gap-2 flex-grow sm:w-2/3" x-show="query.ingredients[key].enabled" x-transition>
                                    <div class="flex items-center gap-1.5 flex-1">
                                        <span class="text-[10px] text-gray-400">Min</span>
                                        <input type="number" 
                                               step="0.01" 
                                               x-model.number="query.ingredients[key].min" 
                                               @input="generateJson()"
                                               class="w-full text-xs rounded-lg border-gray-200 px-2 py-1 focus:border-hops-mid focus:ring-hops-mid">
                                    </div>
                                    <div class="flex items-center gap-1.5 flex-1">
                                        <span class="text-[10px] text-gray-400">Max</span>
                                        <input type="number" 
                                               step="0.01" 
                                               x-model.number="query.ingredients[key].max" 
                                               @input="generateJson()"
                                               class="w-full text-xs rounded-lg border-gray-200 px-2 py-1 focus:border-hops-mid focus:ring-hops-mid">
                                    </div>
                                </div>
                                <div class="text-xs text-gray-400 text-right sm:w-2/3" x-show="!query.ingredients[key].enabled">
                                    {{ __('Any content') }}
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-gray-50/50 rounded-2xl border border-gray-100 p-4 space-y-4">
                    <h4 class="text-xs font-bold text-hops-darkest uppercase tracking-wide border-b border-gray-200/50 pb-2">
                        {{ __('4. Bittering & Aromaticity Feelings') }}
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('Bitterness Level') }}
                            </label>
                            <select x-model="query.feeling.bitterness"
                                    @change="generateJson()"
                                    class="w-full text-xs rounded-xl border-gray-200 focus:border-hops-mid focus:ring-hops-mid px-3 py-2 bg-white">
                                <option value="">{{ __('Any') }}</option>
                                @foreach(\HopsWeb\Enums\Bitterness::cases() as $bitter)
                                    <option value="{{ $bitter->value }}">{{ __($bitter->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">
                                {{ __('Aromaticity Level') }}
                            </label>
                            <select x-model="query.feeling.aromaticity"
                                    @change="generateJson()"
                                    class="w-full text-xs rounded-xl border-gray-200 focus:border-hops-mid focus:ring-hops-mid px-3 py-2 bg-white">
                                <option value="">{{ __('Any') }}</option>
                                @foreach(\HopsWeb\Enums\Aromaticity::cases() as $aroma)
                                    <option value="{{ $aroma->value }}">{{ __($aroma->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="xl:col-span-5 flex flex-col h-full space-y-4">
                <div class="flex-grow bg-gray-900 rounded-2xl border border-gray-800 p-4 flex flex-col shadow-inner">
                    <div class="flex items-center justify-between border-b border-gray-800 pb-2 mb-3">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-green-400">
                            <x-lucide-code class="w-3.5 h-3.5" />
                            {{ __('Live JSON DTO editor') }}
                        </span>
                        <span class="text-[9px] text-gray-500">
                            {{ __('Bidirectional Binding') }}
                        </span>
                    </div>

                    <textarea name="query_json"
                              x-model="rawJson"
                              @input="parseJson()"
                              rows="18"
                              class="w-full font-mono text-xs text-green-400 bg-transparent border-0 focus:ring-0 p-0 resize-none flex-grow"
                              placeholder="{}"></textarea>
                </div>

                <div class="p-4 bg-hops-light rounded-xl border border-hops-mid/10 flex items-start gap-2.5">
                    <x-lucide-info class="w-5 h-5 text-hops-mid mt-0.5 shrink-0" />
                    <p class="text-[10px] text-hops-dark font-medium leading-relaxed">
                        {{ __('You can directly edit the raw JSON brackets above. If the JSON is structurally valid, the visual form layout on the left will immediately reflect your edits.') }}
                    </p>
                </div>
            </div>

        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" 
                    class="bg-hops-ink text-white font-bold text-xs rounded-full px-6 py-3 hover:bg-hops-mid hover:shadow-md transition flex items-center gap-1.5 cursor-pointer">
                <x-phosphor-scales-bold class="w-4 h-4 text-hops-accent" />
                {{ __('Execute Comparison Query') }}
            </button>
        </div>
    </form>
</div>
