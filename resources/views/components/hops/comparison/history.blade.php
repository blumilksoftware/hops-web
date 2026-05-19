@props([
    'history',
    'activeQuery' => null
])

<aside class="w-full lg:w-80 shrink-0 bg-white rounded-2xl border border-hops-light p-5 shadow-sm">
    <h3 class="text-sm font-bold text-hops-ink uppercase tracking-wider mb-4 flex items-center gap-2">
        <x-lucide-history class="w-4 h-4 text-hops-mid" />
        {{ __('Query History') }}
    </h3>

    <div class="space-y-3 overflow-y-auto max-h-[600px] pr-1">
        @forelse($history as $q)
            @php
                $isActive = $activeQuery && $activeQuery->id === $q->id;
                $isNlp = isset($q->query['_nlp_query']);
            @endphp
            <a href="{{ route('comparison.index', ['history_id' => $q->id]) }}" 
               class="block p-3.5 rounded-xl border transition-all text-left group {{ $isActive ? 'border-hops-accent bg-hops-light/50 ring-1 ring-hops-accent' : 'border-gray-100 hover:border-hops-mid/30 hover:bg-gray-50' }}">
                <div class="flex items-start justify-between gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase px-2 py-0.5 rounded-md {{ $isNlp ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-hops-light text-hops-darkest border border-hops-mid/10' }}">
                        @if($isNlp)
                            <x-lucide-message-square class="w-3 h-3" />
                            NLP
                        @else
                            <x-lucide-code class="w-3 h-3" />
                            Form/JSON
                        @endif
                    </span>
                    <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">
                        {{ $q->created_at->diffForHumans() }}
                    </span>
                </div>

                <p class="text-xs font-semibold text-hops-ink line-clamp-2 mb-2 group-hover:text-hops-mid transition-colors">
                    @if($isNlp)
                        "{{ $q->query['_nlp_query'] }}"
                    @else
                        {{ __('Structured Constraint Query') }}
                    @endif
                </p>

                <div class="flex flex-wrap gap-1 text-[10px] text-gray-400">
                    @if(isset($q->query['aroma']['present']) && !empty($q->query['aroma']['present']))
                        <span class="bg-gray-100 px-1.5 py-0.5 rounded">
                            {{ count($q->query['aroma']['present']) }} {{ __('aromas') }}
                        </span>
                    @endif
                    @if(isset($q->query['target']['present']) && !empty($q->query['target']['present']))
                        <span class="bg-gray-100 px-1.5 py-0.5 rounded">
                            {{ __('target') }}: {{ $q->query['target']['present'][0] }}
                        </span>
                    @endif
                    @if(isset($q->query['ingredients']) && !empty($q->query['ingredients']))
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
    </div>
</aside>
