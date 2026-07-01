<x-hops.layout>
    <x-slot:title>{{ __('Agenda Comparison') }}</x-slot:title>

    <div class="relative w-full bg-white py-12 px-4 sm:px-6 lg:px-8 border-b border-hops-light overflow-hidden">
        <div class="absolute inset-0 flex justify-center items-center pointer-events-none opacity-5">
            <x-lucide-columns-4 class="w-[640px] h-[640px]" />
        </div>

        <div class="relative max-w-4xl mx-auto text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-hops-light text-hops-mid border border-hops-mid/20 mb-4">
                <x-lucide-beaker class="w-3.5 h-3.5" />
                {{ __('Virtual Laboratory Workspace') }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-hops-ink text-center tracking-tight">
                {{ __('Compare Runs Side-by-Side') }}
            </h1>
            <p class="text-sm text-gray-500 text-center mt-2 max-w-xl mx-auto">
                {{ __('Comparing experimental tuning history for agenda :name.', ['name' => $agenda->name]) }}
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('laboratory.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-hops-ink transition">
                <x-lucide-arrow-left class="w-3.5 h-3.5" />
                {{ __('Back to Laboratory') }}
            </a>
            @if(count($runs) > 0)
                <span class="text-xs text-gray-400 font-semibold">
                    {{ __('Comparing :count runs', ['count' => count($runs)]) }}
                </span>
            @endif
        </div>

        <div class="bg-white rounded-3xl border border-hops-light shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider w-1/4">
                                {{ __('Parameter / Hop') }}
                            </th>
                            @foreach($runs as $index => $run)
                                <th scope="col" class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    <div class="flex flex-col items-center">
                                        <span class="text-hops-ink font-black text-xs">Run #{{ $index + 1 }}</span>
                                        <span class="text-[9px] text-gray-400 font-medium mt-0.5">ID: {{ $run->id }}</span>
                                        <span class="text-[9px] text-gray-400 font-normal mt-0.5">{{ $run->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <!-- Section: Module Weights -->
                        <tr class="bg-gray-50/50">
                            <td colspan="{{ count($runs) + 1 }}" class="px-6 py-3 text-[10px] font-bold text-hops-darkest uppercase tracking-wider">
                                {{ __('Module Weights') }}
                            </td>
                        </tr>
                        @foreach($moduleRows as $key => $row)
                            <tr class="{{ $row['isDifferent'] ? 'bg-amber-50/20' : '' }} hover:bg-gray-50/30 transition-colors">
                                <td class="px-6 py-4 text-xs font-semibold text-gray-900 flex items-center gap-1.5">
                                    @if($row['isDifferent'])
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0 animate-pulse" title="{{ __('Difference detected') }}"></span>
                                    @endif
                                    {{ $row['label'] }}
                                </td>
                                @foreach($runs as $run)
                                    <td class="px-6 py-4 text-xs font-mono text-center {{ $row['isDifferent'] ? 'font-black text-hops-ink' : 'text-gray-500' }}">
                                        {{ round(($row['values'][$run->id] ?? 0) * 100) }}%
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        <!-- Section: Biochemical Weights -->
                        <tr class="bg-gray-50/50 border-t border-gray-100">
                            <td colspan="{{ count($runs) + 1 }}" class="px-6 py-3 text-[10px] font-bold text-hops-darkest uppercase tracking-wider">
                                {{ __('Biochemical Weights') }}
                            </td>
                        </tr>
                        @foreach($biochemicalRows as $key => $row)
                            <tr class="{{ $row['isDifferent'] ? 'bg-amber-50/20' : '' }} hover:bg-gray-50/30 transition-colors">
                                <td class="px-6 py-4 text-xs font-semibold text-gray-900 flex items-center gap-1.5">
                                    @if($row['isDifferent'])
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0 animate-pulse" title="{{ __('Difference detected') }}"></span>
                                    @endif
                                    {{ $row['label'] }}
                                </td>
                                @foreach($runs as $run)
                                    <td class="px-6 py-4 text-xs font-mono text-center {{ $row['isDifferent'] ? 'font-black text-hops-ink' : 'text-gray-500' }}">
                                        {{ round(($row['values'][$run->id] ?? 0) * 100) }}%
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        <!-- Section: Engine Hop Scores -->
                        <tr class="bg-gray-50/50 border-t border-gray-100">
                            <td colspan="{{ count($runs) + 1 }}" class="px-6 py-3 text-[10px] font-bold text-hops-darkest uppercase tracking-wider">
                                {{ __('Top Matches Similarity Score') }}
                            </td>
                        </tr>
                        @if(count($scoreRows) === 0)
                            <tr>
                                <td colspan="{{ count($runs) + 1 }}" class="px-6 py-8 text-center text-xs text-gray-400 italic">
                                    {{ __('No matches similarity scores calculated for these runs.') }}
                                </td>
                            </tr>
                        @else
                            @foreach($scoreRows as $row)
                                <tr class="{{ $row['isDifferent'] ? 'bg-amber-50/20' : '' }} hover:bg-gray-50/30 transition-colors">
                                    <td class="px-6 py-4 text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                        @if($row['isDifferent'])
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0 animate-pulse" title="{{ __('Difference detected') }}"></span>
                                        @endif
                                        <span class="uppercase">{{ $row['label'] }}</span>
                                    </td>
                                    @foreach($runs as $run)
                                        @php
                                            $val = $row['values'][$run->id];
                                            $isMax = ($val !== null && $val === $row['maxScore']);
                                        @endphp
                                        <td class="px-6 py-4 text-xs font-mono text-center">
                                            @if($val !== null)
                                                @if($isMax && $row['isDifferent'])
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-green-50 text-green-700 border border-green-200">
                                                        {{ round($val * 100) }}%
                                                    </span>
                                                @else
                                                    <span class="{{ $row['isDifferent'] ? 'font-black text-hops-ink' : 'text-gray-500' }}">
                                                        {{ round($val * 100) }}%
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-hops.layout>
