<x-hops.layout>
    <x-slot:title>{{ __('New Agenda') }}</x-slot:title>

    <div class="relative w-full bg-white py-12 px-4 sm:px-6 lg:px-8 border-b border-hops-light overflow-hidden">
        <div class="absolute inset-0 flex justify-center items-center pointer-events-none opacity-5">
            <x-lucide-beaker class="w-[640px] h-[640px]" />
        </div>

        <div class="relative max-w-4xl mx-auto text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-hops-light text-hops-mid border border-hops-mid/20 mb-4">
                <x-lucide-beaker class="w-3.5 h-3.5" />
                {{ __('Virtual Laboratory Workspace') }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-hops-ink text-center tracking-tight">
                {{ __('Create New Agenda') }}
            </h1>
            <p class="text-sm text-gray-500 text-center mt-2 max-w-xl mx-auto">
                {{ __('Define a name and optional base query for your experimental agenda.') }}
            </p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-6">
            <a href="{{ route('laboratory.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-hops-ink transition">
                <x-lucide-arrow-left class="w-3.5 h-3.5" />
                {{ __('Back to Laboratory') }}
            </a>
        </div>

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
                    <a href="{{ route('laboratory.index') }}"
                        class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-hops-ink transition">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-hops-ink text-white text-xs font-bold rounded-xl hover:bg-opacity-90 transition cursor-pointer">
                        <x-lucide-save class="w-3.5 h-3.5" />
                        {{ __('Save Agenda') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-hops.layout>
