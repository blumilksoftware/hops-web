@props(['hop'])

<a href="{{ route('hops.show', $hop) }}"
    class="group block bg-white rounded-2xl shadow-sm border border-hops-light p-4 hover:shadow-md transition-shadow relative">

    <div class="absolute top-4 right-4 z-10" x-data="{ selected: false }">
        <button @click.prevent="selected = !selected"
            :class="selected ? 'bg-hops-accent text-hops-ink' : 'bg-hops-ink text-white'"
            class="w-8 h-8 rounded-full flex items-center justify-center transition-colors">
            <x-phosphor-scales-bold class="w-4 h-4"/>
        </button>
    </div>

    <div class="w-full h-32 flex items-center justify-center mb-3">
        @if ($hop->image_url)
            <img src="{{ $hop->image_url }}" alt="{{ $hop->name }}" class="w-full h-full object-contain">
        @else
            <x-lucide-hop class="opacity-25 w-16 h-16" />
        @endif
    </div>

    <h3 class="text-sm font-bold uppercase text-hops-ink mt-3">
        {{ $hop->name }}
    </h3>
    <p class="text-xs text-gray-400 mt-1">
        {{ $hop->country ?? __('Unknown origin') }}
    </p>

    <div class="flex flex-col ">
        <div class="flex flex-wrap gap-1 mt-3">
            @foreach ($hop->getActiveAromas() as $index => $aroma)
                <span class="ml-1 mr-1 text-xs font-semibold py-0.5 px-1 ">
                    {{ __($aroma->label()) }}
                </span>
            @endforeach
        </div>


    </div>
</a>
