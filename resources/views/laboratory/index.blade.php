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
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if(session("success"))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex gap-3 items-center">
                <x-lucide-check-circle class="w-4 h-4 text-green-600 shrink-0" />
                <p class="text-sm text-green-700 font-medium">{{ session("success") }}</p>
            </div>
        @endif

        @if($agendas->isEmpty())
            <div class="p-12 text-center bg-white rounded-3xl border border-hops-light">
                <x-lucide-beaker class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <h3 class="text-sm font-bold text-hops-ink uppercase">{{ __('No agendas yet') }}</h3>
            </div>
        @else
            <div class="space-y-4">
                @foreach($agendas as $agenda)
                    <div class="bg-white rounded-2xl border border-hops-light shadow-2xs p-5 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-sm text-hops-ink uppercase">{{ $agenda->name }}</span>
                            <span class="block text-[10px] text-gray-400 mt-0.5">{{ $agenda->created_at->format("M d, Y H:i") }}</span>
                        </div>
                        <a href="{{ route('laboratory.agendas.runs.create', $agenda) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-hops-ink text-white text-xs font-bold rounded-xl hover:bg-opacity-90 transition">
                            <x-lucide-sliders-horizontal class="w-3.5 h-3.5" />
                            {{ __('New Parameter Set') }}
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $agendas->links() }}
            </div>
        @endif
    </div>
</x-hops.layout>
