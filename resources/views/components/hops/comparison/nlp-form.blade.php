@props([
    'activeQuery' => null
])

<div x-show="activeTab === 'nlp'" style="display: none;" class="space-y-6">
    <form action="{{ route('comparison.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="type" value="nlp">
        
        <div>
            <label for="nlp-input" class="block text-xs font-bold text-hops-ink uppercase tracking-wider mb-2">
                {{ __('Describe your desired hop variety') }}
            </label>
            <textarea id="nlp-input" 
                      name="natural_language_query"
                      x-model="nlpText"
                      rows="4"
                      placeholder="{{ __('e.g., I want a hop resembling Citra, extremely citrusy and tropical but with less bitterness...') }}"
                      class="w-full text-sm rounded-2xl border-gray-200 shadow-2xs focus:border-hops-mid focus:ring-hops-mid px-4 py-3 resize-none bg-gray-50/20"></textarea>
        </div>

        <div class="space-y-2">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">
                {{ __('Or try one of these suggestions') }}:
            </span>
            <div class="flex flex-col sm:flex-row gap-2">
                <template x-for="suggestion in suggestions" :key="suggestion">
                    <button type="button" 
                            @click="nlpText = suggestion"
                            class="text-left text-xs bg-hops-light hover:bg-hops-accent/15 border border-hops-mid/10 hover:border-hops-accent/30 text-hops-darkest rounded-xl px-3 py-2 transition cursor-pointer font-medium flex-1">
                        <span x-text="suggestion"></span>
                    </button>
                </template>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" 
                    class="bg-hops-ink text-white font-bold text-xs rounded-full px-6 py-3 hover:bg-hops-mid hover:shadow-md transition flex items-center gap-1.5 cursor-pointer">
                <x-phosphor-scales-bold class="w-4 h-4 text-hops-accent" />
                {{ __('Run NLP Search') }}
            </button>
        </div>
    </form>
</div>
