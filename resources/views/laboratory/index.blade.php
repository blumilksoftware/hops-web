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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if(session("success"))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex gap-3 items-center">
                <x-lucide-check-circle class="w-4 h-4 text-green-600 shrink-0" />
                <p class="text-sm text-green-700 font-medium">{{ session("success") }}</p>
            </div>
        @endif

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-hops-ink flex items-center gap-2">
                <x-lucide-layers class="w-5 h-5 text-hops-mid" />
                {{ __('Experimental Agendas') }}
            </h2>
            <a href="{{ route('laboratory.agendas.create') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-hops-ink text-white text-xs font-bold rounded-xl hover:bg-opacity-90 transition">
                <x-lucide-plus class="w-4 h-4" />
                {{ __('New Agenda') }}
            </a>
        </div>

        @if($agendas->isEmpty())
            <div class="p-12 text-center bg-white rounded-3xl border border-hops-light">
                <x-lucide-beaker class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <h3 class="text-sm font-bold text-hops-ink uppercase">{{ __('No agendas yet') }}</h3>
                <p class="text-xs text-gray-400 mt-2 max-w-md mx-auto">
                    {{ __('Create your first experimental agenda to start tracking comparison runs with custom parameter configurations.') }}
                </p>
                <a href="{{ route('laboratory.agendas.create') }}"
                    class="inline-flex items-center gap-1.5 mt-6 px-4 py-2 bg-hops-ink text-white text-xs font-bold rounded-xl hover:bg-opacity-90 transition">
                    <x-lucide-plus class="w-4 h-4" />
                    {{ __('Create First Agenda') }}
                </a>
            </div>
        @else
            <div class="bg-white rounded-3xl border border-hops-light shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Name') }}</th>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Created') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($agendas as $agenda)
                                <tr class="hover:bg-gray-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-sm text-hops-ink uppercase">{{ $agenda->name }}</span>
                                        <span class="block text-[10px] text-gray-400 mt-0.5">ID: {{ $agenda->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 font-medium whitespace-nowrap">
                                        {{ $agenda->created_at->format("M d, Y H:i") }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $agendas->links() }}
            </div>
        @endif
    </div>
</x-hops.layout>
