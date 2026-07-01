<x-hops.layout>
    <x-slot:title>{{ __('Laboratory') }}</x-slot:title>

    <div class="relative w-full bg-white py-12 px-4 sm:px-6 lg:px-8 border-b border-hops-light overflow-hidden">
        <div class="absolute inset-0 flex justify-center items-center pointer-events-none opacity-5">
            <x-lucide-beaker class="w-[640px] h-[640px]" />
        </div>

        <div class="relative max-w-4xl mx-auto text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-hops-light text-hops-mid border border-hops-mid/20 mb-4">
                <x-lucide-beaker class="w-3.5 h-3.5 animate-pulse" />
                {{ __('Virtual Laboratory Workspace') }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-hops-ink text-center tracking-tight">
                {{ __('Model Tuning & Parameter Exploration') }}
            </h1>
            <p class="text-sm text-gray-500 text-center mt-2 max-w-xl mx-auto">
                {{ __('Create and manage experimental agendas to compare custom similarity weights and inspect historical engine responses.') }}
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ activeTab: '{{ $activeTab ?? 'dashboard' }}' }">
        @if(session("success"))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex gap-3 items-center">
                <x-lucide-check-circle class="w-4 h-4 text-green-600 shrink-0" />
                <p class="text-sm text-green-700 font-medium">{{ session("success") }}</p>
            </div>
        @endif

        <div class="flex border-b border-gray-200 mb-8 gap-6">
            <button @click="activeTab = 'dashboard'"
                :class="activeTab === 'dashboard' ? 'border-hops-ink text-hops-ink font-bold border-b-2' : 'border-transparent text-gray-400 hover:text-gray-600'"
                class="flex items-center gap-2 pb-4 text-sm font-semibold transition cursor-pointer select-none">
                <x-lucide-layout-dashboard class="w-4 h-4" />
                {{ __('Dashboard') }}
            </button>
            <button @click="activeTab = 'create'"
                :class="activeTab === 'create' ? 'border-hops-ink text-hops-ink font-bold border-b-2' : 'border-transparent text-gray-400 hover:text-gray-600'"
                class="flex items-center gap-2 pb-4 text-sm font-semibold transition cursor-pointer select-none">
                <x-lucide-plus-circle class="w-4 h-4" />
                {{ __('Create New Agenda') }}
            </button>
        </div>

        <div x-show="activeTab === 'dashboard'" x-transition>
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-white p-6 rounded-3xl border border-hops-light shadow-2xs flex items-center gap-4">
                    <div class="p-3.5 bg-hops-light text-hops-mid rounded-2xl">
                        <x-lucide-clipboard-list class="w-6 h-6" />
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-hops-ink">{{ $stats["total_agendas"] }}</span>
                        <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider">{{ __("Total Agendas") }}</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-hops-light shadow-2xs flex items-center gap-4">
                    <div class="p-3.5 bg-hops-light text-hops-mid rounded-2xl">
                        <x-lucide-git-commit class="w-6 h-6 rotate-90" />
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-hops-ink">{{ $stats["total_runs"] }}</span>
                        <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider">{{ __("Experimental Runs") }}</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-hops-light shadow-2xs flex items-center gap-4">
                    <div class="p-3.5 bg-hops-light text-hops-mid rounded-2xl">
                        <x-lucide-users class="w-6 h-6" />
                    </div>
                    <div>
                        <span class="block text-2xl font-black text-hops-ink">{{ $stats["active_researchers"] }}</span>
                        <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider">{{ __("Active Researchers") }}</span>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-hops-light shadow-2xs flex items-center gap-4">
                    <div class="p-3.5 bg-hops-light text-hops-mid rounded-2xl">
                        <x-lucide-activity class="w-6 h-6" />
                    </div>
                    <div class="overflow-hidden">
                        <span class="block text-sm font-bold text-hops-ink truncate">
                            {{ $stats["last_activity"] ? $stats["last_activity"]->diffForHumans() : __("No activity yet") }}
                        </span>
                        <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider">{{ __("Last Activity") }}</span>
                    </div>
                </div>
            </div>

            <!-- Agendas List Section -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-hops-ink flex items-center gap-2">
                        <x-lucide-layers class="w-5 h-5 text-hops-mid" />
                        {{ __('Persistent Experimental Agendas') }}
                    </h2>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-400 font-semibold bg-white px-3 py-1 rounded-full border border-gray-100">
                            {{ $agendas->total() }} {{ __('Agendas total') }}
                        </span>
                        <button @click="activeTab = 'create'"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-hops-ink text-white text-xs font-bold rounded-xl hover:bg-opacity-90 transition cursor-pointer">
                            <x-lucide-plus class="w-4 h-4" />
                            {{ __('New Agenda') }}
                        </button>
                    </div>
                </div>

                @if($agendas->isEmpty())
                    <div class="p-12 text-center bg-white rounded-3xl border border-hops-light">
                        <x-lucide-beaker class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                        <h3 class="text-sm font-bold text-hops-ink uppercase">{{ __('No experimental agendas found') }}</h3>
                        <p class="text-xs text-gray-400 mt-2 max-w-md mx-auto">
                            {{ __('There are no agendas configured in the system. Agendas serve as persistent experimental sessions grouping comparison queries with multiple configuration runs.') }}
                        </p>
                        <button @click="activeTab = 'create'"
                            class="inline-flex items-center gap-1.5 mt-6 px-4 py-2 bg-hops-ink text-white text-xs font-bold rounded-xl hover:bg-opacity-90 transition cursor-pointer">
                            <x-lucide-plus class="w-4 h-4" />
                            {{ __('Create First Agenda') }}
                        </button>
                    </div>
                @else
                    @php
                        $nextDirectionForName = ($sort === 'name' && $direction === 'asc') ? 'desc' : 'asc';
                        $nextDirectionForCreated = ($sort === 'created_at' && $direction === 'asc') ? 'desc' : 'asc';
                    @endphp
                    <div class="bg-white rounded-3xl border border-hops-light shadow-2xs overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider w-1/3">
                                            <a href="{{ route('laboratory.index', ['sort' => 'name', 'direction' => $nextDirectionForName]) }}" class="inline-flex items-center gap-1 hover:text-hops-ink transition-colors">
                                                {{ __('Agenda / Experiment') }}
                                                @if($sort === 'name')
                                                    @if($direction === 'asc')
                                                        <x-lucide-arrow-up class="w-3.5 h-3.5 text-hops-ink" />
                                                    @else
                                                        <x-lucide-arrow-down class="w-3.5 h-3.5 text-hops-ink" />
                                                    @endif
                                                @else
                                                    <x-lucide-chevrons-up-down class="w-3.5 h-3.5 opacity-50" />
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Creator') }}</th>
                                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Query Highlights') }}</th>
                                        <th scope="col" class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider w-32">{{ __('Runs') }}</th>
                                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider w-36">
                                            <a href="{{ route('laboratory.index', ['sort' => 'created_at', 'direction' => $nextDirectionForCreated]) }}" class="inline-flex items-center gap-1 hover:text-hops-ink transition-colors">
                                                {{ __('Created') }}
                                                @if($sort === 'created_at')
                                                    @if($direction === 'asc')
                                                        <x-lucide-arrow-up class="w-3.5 h-3.5 text-hops-ink" />
                                                    @else
                                                        <x-lucide-arrow-down class="w-3.5 h-3.5 text-hops-ink" />
                                                    @endif
                                                @else
                                                    <x-lucide-chevrons-up-down class="w-3.5 h-3.5 opacity-50" />
                                                @endif
                                            </a>
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider w-48">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                @foreach($agendas as $agenda)
                                    <tbody x-data="{ expanded: false }" class="divide-y divide-gray-100 bg-white">
                                        <tr class="hover:bg-gray-50/30 transition-colors">
                                            <td class="px-6 py-4 w-1/3">
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-sm text-hops-ink uppercase">{{ $agenda->name }}</span>
                                                    <span class="text-[10px] text-gray-400 mt-0.5">ID: {{ $agenda->id }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 font-medium whitespace-nowrap">
                                                {{ $agenda->user->name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-2 items-center">
                                                    @if(!empty($agenda->query["target"]["present"]))
                                                        <div x-data="{ showAllTargets: false }" class="flex flex-wrap gap-1 items-center">
                                                            @foreach($agenda->query["target"]["present"] as $index => $hop)
                                                                <span x-show="showAllTargets || {{ $index }} < 2"
                                                                    class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-medium bg-hops-light text-hops-dark border border-hops-mid/10 capitalize">
                                                                    <x-lucide-hop class="w-2.5 h-2.5" />
                                                                    {{ $hop }}
                                                                </span>
                                                            @endforeach
                                                            @if(count($agenda->query["target"]["present"]) > 2)
                                                                <button x-show="!showAllTargets" @click.stop="showAllTargets = true"
                                                                    class="text-[9px] text-gray-400 hover:text-hops-mid font-semibold cursor-pointer select-none">
                                                                    +{{ count($agenda->query["target"]["present"]) - 2 }} more
                                                                </button>
                                                                <button x-show="showAllTargets" @click.stop="showAllTargets = false"
                                                                    class="text-[9px] text-gray-400 hover:text-hops-mid font-semibold cursor-pointer select-none">
                                                                    {{ __("less") }}
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if(!empty($agenda->query["aroma"]["present"]))
                                                        <div x-data="{ showAllAromas: false }" class="flex flex-wrap gap-1 items-center">
                                                            @foreach($agenda->query["aroma"]["present"] as $index => $aroma)
                                                                <span x-show="showAllAromas || {{ $index }} < 2"
                                                                    class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-800 border border-amber-200/50 capitalize">
                                                                    <x-lucide-wind class="w-2.5 h-2.5" />
                                                                    {{ $aroma }}
                                                                </span>
                                                            @endforeach
                                                            @if(count($agenda->query["aroma"]["present"]) > 2)
                                                                <button x-show="!showAllAromas" @click.stop="showAllAromas = true"
                                                                    class="text-[9px] text-gray-400 hover:text-hops-mid font-semibold cursor-pointer select-none">
                                                                    +{{ count($agenda->query["aroma"]["present"]) - 2 }} more
                                                                </button>
                                                                <button x-show="showAllAromas" @click.stop="showAllAromas = false"
                                                                    class="text-[9px] text-gray-400 hover:text-hops-mid font-semibold cursor-pointer select-none">
                                                                    {{ __("less") }}
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if(empty($agenda->query["target"]["present"]) && empty($agenda->query["aroma"]["present"]))
                                                        <span class="text-[10px] text-gray-400 italic">{{ __("Empty query bounds") }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center w-32 whitespace-nowrap">
                                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold bg-hops-light text-hops-darkest">
                                                    {{ $agenda->results->count() }} {{ trans_choice("run|runs", $agenda->results->count()) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 font-medium w-36 whitespace-nowrap">
                                                {{ $agenda->created_at->format("M d, Y H:i") }}
                                            </td>
                                            <td class="px-6 py-4 text-right w-48 whitespace-nowrap">
                                                <div class="flex items-center justify-end gap-3">
                                                    <a href="{{ route('laboratory.agendas.runs.create', $agenda) }}"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-hops-ink text-white text-xs font-bold rounded-xl hover:bg-opacity-90 transition">
                                                        <x-lucide-sliders-horizontal class="w-3.5 h-3.5" />
                                                        {{ __('New Run') }}
                                                    </a>
                                                    <button @click="expanded = !expanded" class="inline-flex items-center gap-1 text-[11px] font-bold text-hops-mid hover:text-hops-darkest transition-colors cursor-pointer select-none">
                                                        <span x-text="expanded ? '{{ __("Collapse") }}' : '{{ __("Inspect") }}'"></span>
                                                        <span :class="expanded ? 'rotate-180' : ''" class="transition-transform duration-200 inline-block">
                                                            <x-lucide-chevron-down class="w-3.5 h-3.5" />
                                                        </span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr x-show="expanded" x-transition class="bg-gray-50/70">
                                            <td colspan="6" class="px-6 py-6 border-t border-gray-100">
                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                                                    <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-3xs text-left">
                                                        <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                                                            <span class="text-xs font-bold text-hops-darkest uppercase tracking-wider flex items-center gap-1.5">
                                                                <x-lucide-code class="w-4 h-4 text-hops-mid" />
                                                                {{ __('Base Query Payload') }}
                                                            </span>
                                                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">{{ __('JSON DTO') }}</span>
                                                        </div>
                                                        <div class="bg-gray-900 rounded-xl p-4 overflow-hidden border border-gray-800">
                                                            <pre class="font-mono text-[10px] text-green-400 overflow-x-auto max-h-72 whitespace-pre-wrap leading-relaxed"><code>{{ json_encode($agenda->query, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                        </div>
                                                    </div>

                                                    <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-3xs text-left">
                                                        <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                                                            <span class="text-xs font-bold text-hops-darkest uppercase tracking-wider flex items-center gap-1.5">
                                                                <x-lucide-git-commit class="w-4 h-4 text-hops-mid rotate-90" />
                                                                {{ __('Experimental Configuration Runs') }}
                                                            </span>
                                                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">{{ __('Tuning History') }}</span>
                                                        </div>

                                                        @if($agenda->results->isEmpty())
                                                            <div class="p-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                                                <x-lucide-flask-conical class="w-8 h-8 text-gray-300 mx-auto mb-2" />
                                                                <p class="text-xs text-gray-400 italic">{{ __('No runs recorded for this agenda yet.') }}</p>
                                                            </div>
                                                        @else
                                                            <div class="space-y-4 max-h-[340px] overflow-y-auto pr-1">
                                                                @foreach($agenda->results as $runIndex => $run)
                                                                    <div class="border border-gray-100 hover:border-hops-mid/20 rounded-xl p-3.5 bg-gray-50/30 transition-all">
                                                                        <div class="flex items-center justify-between mb-2">
                                                                            <span class="text-[11px] font-black text-hops-darkest uppercase tracking-wider">
                                                                                {{ __('Run #') }}{{ $runIndex + 1 }}
                                                                            </span>
                                                                            <span class="text-[10px] text-gray-400 font-medium">
                                                                                {{ $run->created_at->diffForHumans() }}
                                                                            </span>
                                                                        </div>

                                                                        @if(!empty($run->parameters["weights"]))
                                                                            <div class="mb-3">
                                                                                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __("Module Weights") }}</span>
                                                                                <div class="grid grid-cols-4 gap-1.5 text-center">
                                                                                    @foreach($run->parameters["weights"] as $key => $weight)
                                                                                        <div class="bg-white border border-gray-100 rounded px-1.5 py-0.5">
                                                                                            <span class="block text-[9px] font-bold text-gray-400 capitalize">{{ $key }}</span>
                                                                                            <span class="block text-xs font-black text-hops-ink">{{ round($weight * 100) }}%</span>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        @if(!empty($run->parameters["biochemical_weights"]))
                                                                            <div class="mb-3">
                                                                                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __("Biochemical Weights") }}</span>
                                                                                <div class="grid grid-cols-4 gap-1.5 text-center">
                                                                                    @foreach($run->parameters["biochemical_weights"] as $key => $weight)
                                                                                        <div class="bg-white border border-gray-100 rounded px-1.5 py-0.5">
                                                                                            <span class="block text-[9px] font-bold text-gray-400 capitalize truncate" title="{{ str_replace('_', ' ', $key) }}">{{ str_replace('_', ' ', $key) }}</span>
                                                                                            <span class="block text-xs font-black text-hops-ink">{{ round($weight * 100) }}%</span>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        @if(!empty($run->response))
                                                                            <div>
                                                                                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">{{ __("Top Matches Similarity Score") }}</span>
                                                                                <div class="space-y-1.5">
                                                                                    @foreach(array_slice($run->response, 0, 3) as $matchIndex => $match)
                                                                                        <div class="flex items-center justify-between text-xs bg-white border border-gray-100 rounded-lg px-2.5 py-1.5">
                                                                                            <div class="flex items-center gap-1.5">
                                                                                                <span class="text-[10px] font-bold text-gray-400">{{ $matchIndex + 1 }}.</span>
                                                                                                <span class="font-bold text-hops-ink uppercase">{{ $match["name"] }}</span>
                                                                                            </div>
                                                                                            <div class="flex items-center gap-2">
                                                                                                <span class="font-black text-hops-darkest">{{ round(($match["score"] ?? 0) * 100) }}%</span>
                                                                                                <div class="w-12 bg-gray-100 h-1.5 rounded-full overflow-hidden shrink-0">
                                                                                                    <div class="bg-hops-accent h-full" style="width: {{ ($match["score"] ?? 0) * 100 }}%"></div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                 @endforeach
                                                             </div>
                                                         @endif
                                                     </div>
                                                 </div>
                                             </td>
                                         </tr>
                                     </tbody>
                                 @endforeach
                             </table>
                         </div>
                     </div>

                     <div class="mt-6">
                         {{ $agendas->links() }}
                     </div>
                 @endif
             </div>
         </div>

         <!-- Create Agenda Tab Content -->
         <div x-show="activeTab === 'create'" x-transition>
             <div class="max-w-3xl mx-auto">
                 <div class="bg-white rounded-3xl border border-hops-light shadow-2xs overflow-hidden">
                     <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-2">
                         <x-lucide-clipboard-list class="w-4 h-4 text-hops-mid" />
                         <span class="text-sm font-bold text-hops-ink">{{ __('Agenda Details') }}</span>
                     </div>

                     <form method="POST" action="{{ route('laboratory.agendas.store') }}" class="p-6 space-y-6">
                         @csrf

                         @if($errors->any())
                             <div class="p-4 bg-red-50 border border-red-100 rounded-2xl flex gap-3 items-start">
                                 <x-lucide-alert-circle class="w-4 h-4 text-red-500 mt-0.5 shrink-0" />
                                 <ul class="text-sm text-red-700 space-y-1">
                                     @foreach($errors->all() as $error)
                                         <li>{{ $error }}</li>
                                     @endforeach
                                 </ul>
                             </div>
                         @endif

                         <div>
                             <x-input-label for="name" :value="__('Agenda Name')" />
                             <x-text-input
                                 id="name"
                                 name="name"
                                 type="text"
                                 class="mt-1 block w-full"
                                 :value="old('name')"
                                 placeholder="{{ __('e.g. Alpha tuning session') }}"
                                 required
                                 autofocus
                             />
                             <x-input-error :messages="$errors->get('name')" class="mt-2" />
                         </div>

                         <div>
                             <x-input-label for="query_json" :value="__('Base Query (JSON, optional)')" />
                             <p class="text-xs text-gray-400 mt-1 mb-2">
                                 {{ __('Provide a JSON object defining the base query constraints. Leave empty to start with no constraints.') }}
                             </p>
                             <textarea
                                 id="query_json"
                                 name="query_json"
                                 rows="12"
                                 class="mt-1 block w-full rounded-md shadow-sm font-mono text-xs {{ $errors->has('query_json') ? 'border-red-500 focus:border-red-500 focus:ring-red-500 text-red-900' : 'border-hops-warm focus:border-hops-accent focus:ring-hops-accent' }}"
                                 placeholder='{
   "target": { "present": [], "absent": [] },
   "aroma": { "present": [], "absent": [] },
   "description": { "present": [], "absent": [] },
   "ingredients": {
     "alphas": null, "betas": null, "cohumulones": null,
     "polyphenols": null, "xanthohumol": null,
     "oils": null, "farnesenes": null, "linalool": null
   },
   "feeling": { "bitterness": null, "aromaticity": null }
 }'
                             >{{ old('query_json') }}</textarea>
                             <x-input-error :messages="$errors->get('query_json')" class="mt-2" />
                         </div>

                         <div class="flex items-center justify-end gap-3 pt-2">
                             <button type="button" @click="activeTab = 'dashboard'"
                                 class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-hops-ink transition cursor-pointer">
                                 {{ __('Cancel') }}
                             </button>
                             <button type="submit"
                                 class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-hops-ink text-white text-xs font-bold rounded-xl hover:bg-opacity-90 transition cursor-pointer">
                                 <x-lucide-save class="w-3.5 h-3.5" />
                                 {{ __('Save Agenda') }}
                             </button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
    </div>
</x-hops.layout>
