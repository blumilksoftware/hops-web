@props(['hop'])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
    <div class="p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">
                    <a href="{{ route('hops.show', $hop) }}" class="hover:text-green-700">
                        {{ $hop->name }}
                    </a>
                </h3>
                <p class="text-sm text-gray-500 leading-tight">
                    {{ $hop->country ?? 'Unknown origin' }}
                </p>
            </div>
            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">
                {{ $hop->aromaticity->value ?? 'Balanced' }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm mt-4">
            <div class="bg-gray-50 p-3 rounded-lg">
                <span class="text-gray-500 block text-xs uppercase tracking-wider font-semibold mb-1">Alpha Acid</span>
                <span class="text-gray-900 font-bold">
                    @if($hop->alpha_acid?->min === $hop->alpha_acid?->max)
                        {{ $hop->alpha_acid?->min }}%
                    @else
                        {{ $hop->alpha_acid?->min ?? 'N/A' }} - {{ $hop->alpha_acid?->max ?? 'N/A' }}%
                    @endif
                </span>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <span class="text-gray-500 block text-xs uppercase tracking-wider font-semibold mb-1">Beta Acid</span>
                <span class="text-gray-900 font-bold">
                    @if($hop->beta_acid?->min === $hop->beta_acid?->max)
                        {{ $hop->beta_acid?->min }}%
                    @else
                        {{ $hop->beta_acid?->min ?? 'N/A' }} - {{ $hop->beta_acid?->max ?? 'N/A' }}%
                    @endif
                </span>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <div class="flex space-x-1">
                @if($hop->aroma_citrusy) <span title="Citrusy" class="w-2 h-2 rounded-full bg-orange-400"></span> @endif
                @if($hop->aroma_fruity) <span title="Fruity" class="w-2 h-2 rounded-full bg-red-400"></span> @endif
                @if($hop->aroma_floral) <span title="Floral" class="w-2 h-2 rounded-full bg-pink-400"></span> @endif
                @if($hop->aroma_herbal) <span title="Herbal" class="w-2 h-2 rounded-full bg-green-400"></span> @endif
            </div>
            <a href="{{ route('hops.show', $hop) }}" class="text-sm font-semibold text-green-700 hover:text-green-800 flex items-center group">
                Full Details
                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>
