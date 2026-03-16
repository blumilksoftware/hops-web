@props(['hop'])


<a href="{{ route('hops.show', $hop) }}" class="group block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
    <div class="p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-700 transition-colors">
                    {{ $hop->name }}
                </h3>
                <p class="text-sm text-gray-500 leading-tight">
                    {{ $hop->country ?? 'Unknown origin' }}
                </p>
            </div>

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
            <div class="bg-gray-50 p-3 rounded-lg">
                <span class="text-gray-500 block text-xs uppercase tracking-wider font-semibold mb-1">Cohumulone</span>
                <span class="text-gray-900 font-bold">
                    @if($hop->cohumulone?->min === $hop->cohumulone?->max)
                        {{ $hop->cohumulone?->min }}%
                    @else
                        {{ $hop->cohumulone?->min ?? 'N/A' }} - {{ $hop->cohumulone?->max ?? 'N/A' }}%
                    @endif
                </span>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <span class="text-gray-500 block text-xs uppercase tracking-wider font-semibold mb-1">Total Oil</span>
                <span class="text-gray-900 font-bold">
                    @if($hop->total_oil?->min === $hop->total_oil?->max)
                        {{ $hop->total_oil?->min }}%
                    @else
                        {{ $hop->total_oil?->min ?? 'N/A' }} - {{ $hop->total_oil?->max ?? 'N/A' }}%
                    @endif
                </span>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            
            <div class="text-sm font-semibold text-green-700 group-hover:text-green-800 flex items-center transition-colors">
                Full Details
                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </div>
    </div>
</a>

