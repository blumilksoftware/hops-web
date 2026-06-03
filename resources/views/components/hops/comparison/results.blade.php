@props([
    'activeQuery' => null,
    'results' => []
])

@if($activeQuery && !empty($results))
    <div class="space-y-6" x-data="{ viewType: 'table' }">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-hops-ink flex items-center gap-2">
                <x-phosphor-scales-bold class="w-5 h-5 text-hops-mid" />
                {{ __('Similarity Search Results') }}
            </h2>
            
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex bg-gray-100 p-0.5 rounded-lg border border-gray-200/50">
                    <button @click="viewType = 'table'" :class="viewType === 'table' ? 'bg-white text-hops-ink shadow-xs' : 'text-gray-400 hover:text-hops-ink'" class="px-2 py-1 rounded-md text-[10px] font-bold transition flex items-center gap-1 cursor-pointer">
                        <x-lucide-table class="w-3.5 h-3.5" />
                        {{ __('Table') }}
                    </button>
                    <button @click="viewType = 'grid'" :class="viewType === 'grid' ? 'bg-white text-hops-ink shadow-xs' : 'text-gray-400 hover:text-hops-ink'" class="px-2 py-1 rounded-md text-[10px] font-bold transition flex items-center gap-1 cursor-pointer">
                        <x-lucide-grid class="w-3.5 h-3.5" />
                        {{ __('Cards') }}
                    </button>
                </div>

                @if(isset($activeQuery->response['metadata']))
                    <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                        <span class="bg-white border border-gray-100 rounded-full px-3 py-1 flex items-center gap-1">
                            <x-lucide-clock class="w-3.5 h-3.5 text-gray-400" />
                            {{ $activeQuery->response['metadata']['execution_time_ms'] }}ms
                        </span>
                        @if(!empty($activeQuery->response['metadata']['modules_used']))
                            <span class="bg-white border border-gray-100 rounded-full px-3 py-1 flex items-center gap-1">
                                <x-lucide-layers class="w-3.5 h-3.5 text-gray-400" />
                                {{ count($activeQuery->response['metadata']['modules_used']) }} {{ __('Modules') }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div x-show="viewType === 'table'" x-transition class="bg-white rounded-3xl border border-hops-light shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider w-12 text-center">{{ __('Rank') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider w-1/4">{{ __('Hop Variety') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider w-1/5">{{ __('Similarity') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider w-1/4">{{ __('Active Aromas') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Breakdown / Insights') }}</th>
                            <th scope="col" class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider w-20">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($results as $index => $result)
                            <tr class="hover:bg-gray-50/30 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-hops-light text-hops-darkest font-bold text-xs">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        @if($result['hop'])
                                            <span class="font-bold text-sm text-hops-ink uppercase">{{ $result['hop']->name }}</span>
                                            <span class="text-[10px] text-gray-400 mt-0.5">{{ $result['hop']->country ?? __('Unknown origin') }}</span>
                                        @else
                                            <span class="font-bold text-sm text-hops-ink uppercase">{{ strtoupper($result['slug']) }}</span>
                                            <span class="text-[10px] text-gray-400 mt-0.5">{{ __('Unknown Origin') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-black text-hops-darkest min-w-[36px]">
                                            {{ round($result['score'] * 100) }}%
                                        </span>
                                        <div class="w-24 bg-gray-100 h-2 rounded-full overflow-hidden shrink-0">
                                            <div class="bg-hops-accent h-full transition-all duration-1000" style="width: {{ $result['score'] * 100 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if(!empty($result['activeAromas']))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($result['activeAromas'] as $aromaLabel)
                                                <span class="text-[9px] font-semibold border border-hops-mid/20 rounded py-0.5 px-1 bg-hops-light text-hops-darkest whitespace-nowrap">
                                                    {{ __($aromaLabel) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-[10px] text-gray-400 italic">{{ __('None') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if(!empty($result['explainability']))
                                        <div class="space-y-1">
                                            @foreach($result['explainability'] as $module => $explanation)
                                                <div class="flex items-start gap-1">
                                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-hops-accent mt-1.5 shrink-0"></span>
                                                    <p class="text-[11px] text-hops-ink leading-relaxed">
                                                        <strong class="capitalize text-hops-dark text-[10px]">{{ $module }}:</strong> 
                                                        {{ $explanation }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-[10px] text-gray-400 italic">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    @if($result['hop'])
                                        <a href="{{ route('hops.show', $result['hop']) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-hops-mid hover:text-hops-darkest transition-colors">
                                            {{ __('Details') }}
                                            <x-lucide-external-link class="w-3.5 h-3.5" />
                                        </a>
                                    @else
                                        <span class="text-[11px] text-gray-400 cursor-not-allowed italic">
                                            {{ __('Unavailable') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="viewType === 'grid'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($results as $result)
                @if($result['hop'])
                    <a href="{{ route('hops.show', $result['hop']) }}" target="_blank" class="bg-white rounded-2xl border border-hops-light p-5 shadow-xs flex flex-col justify-between hover:shadow-md hover:border-hops-mid/30 transition-all relative overflow-hidden group block">
                @else
                    <div class="bg-white rounded-2xl border border-hops-light p-5 shadow-xs flex flex-col justify-between relative overflow-hidden group">
                @endif
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-50">
                        <div class="bg-hops-accent h-full transition-all duration-1000" style="width: {{ $result['score'] * 100 }}%"></div>
                    </div>

                    <div>
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                @if($result['hop'])
                                    <h3 class="text-base font-bold uppercase text-hops-ink group-hover:text-hops-mid transition-colors flex items-center gap-1">
                                        {{ $result['hop']->name }}
                                        <x-lucide-external-link class="w-3.5 h-3.5 text-gray-400 group-hover:text-hops-mid transition-colors" />
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $result['hop']->country ?? __('Unknown origin') }}
                                    </p>
                                @else
                                    <h3 class="text-base font-bold uppercase text-hops-ink">{{ strtoupper($result['slug']) }}</h3>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Unknown Origin') }}</p>
                                @endif
                            </div>

                            <div class="flex flex-col items-end">
                                <span class="text-sm font-black text-hops-darkest">
                                    {{ round($result['score'] * 100) }}%
                                </span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">
                                    {{ __('Similarity') }}
                                </span>
                            </div>
                        </div>

                        @if(!empty($result['explainability']))
                            <div class="space-y-2 mb-4 bg-gray-50/50 rounded-xl p-3.5 border border-gray-100/50">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1">
                                    <x-lucide-lightbulb class="w-3.5 h-3.5 text-hops-warm" />
                                    {{ __('Similarity Breakdown') }}
                                </h4>
                                <div class="space-y-1.5">
                                    @foreach($result['explainability'] as $module => $explanation)
                                        <div class="flex items-start gap-1.5">
                                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-hops-accent mt-1.5 shrink-0"></span>
                                            <p class="text-xs text-hops-ink leading-relaxed">
                                                <strong class="capitalize text-hops-dark">{{ $module }}:</strong> 
                                                {{ $explanation }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(!empty($result['activeAromas']))
                        <div class="flex flex-wrap gap-1.5 mt-2 mb-4">
                            @foreach($result['activeAromas'] as $aromaLabel)
                                <span class="text-[10px] font-semibold border border-hops-mid/20 rounded-md py-0.5 px-1.5 bg-hops-light text-hops-darkest">
                                    {{ __($aromaLabel) }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                @if($result['hop'])
                    </a>
                @else
                    </div>
                @endif
            @endforeach
        </div>

        <div class="bg-white rounded-3xl border border-hops-light p-6 shadow-xs" x-data="{ showMetadata: false, showQueryJson: false }">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                <h3 class="text-sm font-bold text-hops-ink uppercase tracking-wider flex items-center gap-2">
                    <x-lucide-database class="w-4.5 h-4.5 text-hops-mid" />
                    {{ __('Query JSON & Metadata Inspector') }}
                </h3>
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ __('Developer Tools') }}</span>
            </div>

            <div class="space-y-4">
                <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-2xs">
                    <button @click="showQueryJson = !showQueryJson" class="w-full flex items-center justify-between px-5 py-4 bg-gray-50/50 hover:bg-gray-100/50 transition-colors text-left cursor-pointer">
                        <span class="text-xs font-bold text-hops-darkest flex items-center gap-2">
                            <x-lucide-code class="w-4 h-4 text-hops-mid" />
                            {{ __('Query JSON DTO Payload') }}
                        </span>
                        <span class="text-[11px] text-gray-500 font-semibold flex items-center gap-1">
                            <span x-text="showQueryJson ? '{{ __('Hide') }}' : '{{ __('Show') }}'"></span>
                            <span :class="showQueryJson ? 'rotate-180' : ''" class="transition-transform duration-200 inline-block">
                                <x-lucide-chevron-down class="w-4 h-4" />
                            </span>
                        </span>
                    </button>
                    <div x-show="showQueryJson" x-transition class="p-4 bg-gray-900 border-t border-gray-800">
                        <pre class="font-mono text-[11px] text-green-400 overflow-x-auto max-h-80 whitespace-pre-wrap leading-relaxed"><code class="language-json">{{ json_encode($activeQuery->query, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                </div>

                <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-2xs">
                    <button @click="showMetadata = !showMetadata" class="w-full flex items-center justify-between px-5 py-4 bg-gray-50/50 hover:bg-gray-100/50 transition-colors text-left cursor-pointer">
                        <span class="text-xs font-bold text-hops-darkest flex items-center gap-2">
                            <x-lucide-layers class="w-4 h-4 text-hops-mid" />
                            {{ __('Engine Response Metadata (Expandable JSON)') }}
                        </span>
                        <span class="text-[11px] text-gray-500 font-semibold flex items-center gap-1">
                            <span x-text="showMetadata ? '{{ __('Hide') }}' : '{{ __('Show') }}'"></span>
                            <span :class="showMetadata ? 'rotate-180' : ''" class="transition-transform duration-200 inline-block">
                                <x-lucide-chevron-down class="w-4 h-4" />
                            </span>
                        </span>
                    </button>
                    <div x-show="showMetadata" x-transition class="p-4 bg-gray-900 border-t border-gray-800">
                        <pre class="font-mono text-[11px] text-green-400 overflow-x-auto max-h-80 whitespace-pre-wrap leading-relaxed"><code class="language-json">{{ json_encode($activeQuery->response['metadata'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                </div>
            </div>
        </div>

    </div>
@elseif($activeQuery)
    <div class="p-12 text-center bg-white rounded-3xl border border-hops-light">
        <x-phosphor-scales-bold class="w-12 h-12 text-gray-300 mx-auto mb-3" />
        <h3 class="text-sm font-bold text-hops-ink uppercase">{{ __('No matching results found') }}</h3>
        <p class="text-xs text-gray-400 mt-2">
            {{ __('The engine could not find any matches matching your specified profile criteria. Try relaxing your query bounds.') }}
        </p>
    </div>
@endif
