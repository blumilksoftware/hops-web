@props([
    'history',
    'activeQuery' => null
])

<aside class="w-full lg:w-80 shrink-0 bg-white rounded-2xl border border-hops-light p-5 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-hops-ink uppercase tracking-wider flex items-center gap-2">
            <x-lucide-history class="w-4 h-4 text-hops-mid" />
            {{ __('Query History') }}
        </h3>
        <button type="button" @click="toggleSidebar()" class="text-gray-400 hover:text-hops-mid focus:outline-none p-1 rounded-lg hover:bg-gray-100 transition cursor-pointer" title="{{ __('Hide History') }}">
            <x-lucide-chevron-left class="w-4.5 h-4.5" />
        </button>
    </div>

    <div class="space-y-3 overflow-y-auto max-h-[600px] pr-1">
        @forelse($history as $query)
            <a href="{{ route('comparison.index', ['history_id' => $query['id']]) }}" 
               class="block p-3.5 rounded-xl border transition-all text-left group {{ $query['isActive'] ? 'border-hops-accent bg-hops-light/50 ring-1 ring-hops-accent' : 'border-gray-100 hover:border-hops-mid/30 hover:bg-gray-50' }}">
                <div class="flex items-start justify-between gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase px-2 py-0.5 rounded-md {{ $query['isNlp'] ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-hops-light text-hops-darkest border border-hops-mid/10' }}">
                        @if($query['isNlp'])
                            <x-lucide-message-square class="w-3 h-3" />
                            NLP
                        @else
                            <x-lucide-code class="w-3 h-3" />
                            Form/JSON
                        @endif
                    </span>
                    <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">
                        {{ $query['created_at']->diffForHumans() }}
                    </span>
                </div>

                <p class="text-xs font-semibold text-hops-ink line-clamp-2 mb-2 group-hover:text-hops-mid transition-colors">
                    @if($query['isNlp'])
                        "{{ $query['nlpQuery'] }}"
                    @else
                        {{ __('Structured Constraint Query') }}
                    @endif
                </p>

                <div class="flex flex-wrap gap-1 text-[10px] text-gray-400">
                    @if($query['aromaCount'] > 0)
                        <span class="bg-gray-100 px-1.5 py-0.5 rounded">
                            {{ $query['aromaCount'] }} {{ __('aromas') }}
                        </span>
                    @endif
                    @if($query['firstTarget'])
                        <span class="bg-gray-100 px-1.5 py-0.5 rounded">
                            {{ __('target') }}: {{ $query['firstTarget'] }}
                        </span>
                    @endif
                    @if($query['hasIngredients'])
                        <span class="bg-gray-100 px-1.5 py-0.5 rounded">
                            {{ __('chemistry') }}
                        </span>
                    @endif
                </div>
            </a>
        @empty
            <div class="text-center py-8 border border-dashed border-gray-200 rounded-2xl">
                <x-lucide-history class="w-8 h-8 mx-auto text-gray-300 mb-2" />
                <p class="text-xs text-gray-400 font-medium px-4">
                    {{ __('No queries executed yet. Run your first comparison query to see history.') }}
                </p>
            </div>
        @endforelse

        @if($history->hasPages())
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                @if($history->onFirstPage())
                    <span class="px-2.5 py-1.5 text-gray-400 bg-gray-50 border border-gray-100 rounded-lg cursor-not-allowed font-semibold">
                        &larr; {{ __('Prev') }}
                    </span>
                @else
                    <a href="{{ $history->previousPageUrl() }}" class="px-2.5 py-1.5 text-hops-ink bg-white border border-gray-200 rounded-lg hover:bg-gray-50 font-semibold transition">
                        &larr; {{ __('Prev') }}
                    </a>
                @endif

                <span class="text-gray-500 font-medium">
                    {{ $history->currentPage() }} / {{ $history->lastPage() }}
                </span>

                @if($history->hasMorePages())
                    <a href="{{ $history->nextPageUrl() }}" class="px-2.5 py-1.5 text-hops-ink bg-white border border-gray-200 rounded-lg hover:bg-gray-50 font-semibold transition">
                        {{ __('Next') }} &rarr;
                    </a>
                @else
                    <span class="px-2.5 py-1.5 text-gray-400 bg-gray-50 border border-gray-100 rounded-lg cursor-not-allowed font-semibold">
                        {{ __('Next') }} &rarr;
                    </span>
                @endif
            </div>
        @endif
    </div>
</aside>
