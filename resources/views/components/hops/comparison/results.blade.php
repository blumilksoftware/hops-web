@props([
    'activeQuery' => null,
    'results' => []
])

@if($activeQuery && !empty($results))
    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-hops-ink flex items-center gap-2">
                <x-phosphor-scales-bold class="w-5 h-5 text-hops-mid" />
                {{ __('Similarity Search Results') }}
            </h2>
            
            @if(isset($activeQuery->response['metadata']))
                <div class="flex items-center gap-3 text-xs text-gray-500 font-medium">
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
