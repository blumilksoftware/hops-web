@props(['filters'])

<aside class="bg-white w-full overflow-y-auto rounded-2xl border border-hops-light p-6">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-lg font-bold text-hops-ink">{{ __('Filters') }}</h2>
        <a href="{{ route('hops.index') }}"
            class="text-sm text-hops-mid hover:text-hops-dark font-medium transition">{{ __('Clear all') }}</a>
    </div>

    <form action="{{ route('hops.index') }}" method="GET" x-ref="filterForm">
        @if (request()->has('countries'))
            @foreach (request()->input('countries') as $country)
                <input type="hidden" name="countries[]" value="{{ $country }}">
            @endforeach
        @endif

        <div class="space-y-8">
            <div>
                <h3 class="text-sm font-semibold text-hops-ink uppercase tracking-wider mb-4">{{ __('Aroma Profile') }}
                </h3>
                <div class="space-y-3">
                    @foreach (\HopsWeb\Enums\AromaProfile::cases() as $profile)
                        <label class="flex items-center group cursor-pointer">
                            <input type="checkbox" name="{{ $profile->value }}" value="1"
                                {{ ($filters[$profile->value] ?? '') == '1' ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-hops-mid focus:ring-hops-mid">
                            <span
                                class="ml-3 text-sm text-gray-600 group-hover:text-hops-ink transition-colors">{{ __($profile->label()) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-hops-ink uppercase tracking-wider mb-4">
                    {{ __('Biochemical Properties') }}</h3>
                <div class="space-y-6">
                    @foreach ([
        'alpha_acid' => __('Alpha Acid (%)'),
        'beta_acid' => __('Beta Acid (%)'),
        'cohumulone' => __('Cohumulone (%)'),
        'total_oil' => __('Total Oil (ml/100g)'),
    ] as $key => $label)
                        <div class="space-y-2" x-data="{
                            min: '{{ $filters[$key . '_min'] ?? '' }}',
                            max: '{{ $filters[$key . '_max'] ?? '' }}'
                        }">
                            <span class="text-xs font-medium text-gray-500 uppercase">{{ $label }}</span>
                            <div class="flex items-center space-x-2">
                                <input type="number" name="{{ $key }}_min" x-model="min"
                                    placeholder="{{ __('Min') }}" step="0.1" min="0"
                                    :max="max || 100"
                                    class="w-full text-xs rounded-md border-gray-300 focus:border-hops-mid focus:ring-hops-mid">
                                <span class="text-gray-400">-</span>
                                <input type="number" name="{{ $key }}_max" x-model="max"
                                    placeholder="{{ __('Max') }}" step="0.1" :min="min || 0"
                                    max="100"
                                    class="w-full text-xs rounded-md border-gray-300 focus:border-hops-mid focus:ring-hops-mid">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-hops-ink uppercase tracking-wider mb-4">
                    {{ __('Characteristics') }}</h3>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-gray-500 uppercase">{{ __('Bitterness') }}</label>
                        <select name="bitterness"
                            class="w-full text-sm rounded-md border-gray-300 focus:border-hops-mid focus:ring-hops-mid">
                            <option value="all">{{ __('Any') }}</option>
                            @foreach (\HopsWeb\Enums\Bitterness::cases() as $case)
                                <option value="{{ $case->value }}"
                                    {{ ($filters['bitterness'] ?? '') == $case->value ? 'selected' : '' }}>
                                    {{ $case->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-gray-500 uppercase">{{ __('Aromaticity') }}</label>
                        <select name="aromaticity"
                            class="w-full text-sm rounded-md border-gray-300 focus:border-hops-mid focus:ring-hops-mid">
                            <option value="all">{{ __('Any') }}</option>
                            @foreach (\HopsWeb\Enums\Aromaticity::cases() as $case)
                                <option value="{{ $case->value }}"
                                    {{ ($filters['aromaticity'] ?? '') == $case->value ? 'selected' : '' }}>
                                    {{ $case->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <button type="submit"
                class="w-full bg-hops-ink text-white rounded-md py-2 text-sm font-semibold hover:opacity-90 transition">
                {{ __('Apply filters') }}
            </button>
        </div>
    </form>
</aside>
