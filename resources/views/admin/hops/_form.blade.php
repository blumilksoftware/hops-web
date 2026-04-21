<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                      :value="old('name', $hop->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="slug" :value="__('Slug')" />
        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full font-mono"
                      :value="old('slug', $hop->slug ?? '')" required />
        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="alt_name" :value="__('Alt Name')" />
        <x-text-input id="alt_name" name="alt_name" type="text" class="mt-1 block w-full"
                      :value="old('alt_name', $hop->alt_name ?? '')" />
        <x-input-error :messages="$errors->get('alt_name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="country" :value="__('Country')" />
        <x-text-input id="country" name="country" type="text" class="mt-1 block w-full"
                      :value="old('country', $hop->country ?? '')" />
        <x-input-error :messages="$errors->get('country')" class="mt-2" />
    </div>
</div>

<div>
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4"
              class="mt-1 block w-full border-hops-warm focus:border-hops-accent focus:ring-hops-accent rounded-md shadow-sm"
    >{{ old('description', $hop->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div>
    <h3 class="text-sm font-semibold text-hops-darkest uppercase tracking-wider mb-3 mt-2">
        {{ __('Biochemical Ranges (%)') }}
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            'alpha_acid' => 'Alpha Acid',
            'beta_acid' => 'Beta Acid',
            'cohumulone' => 'Cohumulone',
            'total_oil' => 'Total Oil',
            'polyphenol' => 'Polyphenol',
            'xanthohumol' => 'Xanthohumol',
            'farnesene' => 'Farnesene',
            'linalool' => 'Linalool',
        ] as $field => $label)
            <div class="bg-hops-light border border-hops-warm rounded-md p-3">
                <p class="text-xs font-semibold text-hops-darkest uppercase tracking-wider mb-2">{{ $label }}</p>
                <div class="flex gap-2">
                    <div class="flex-1">
                        <x-input-label :for="$field . '_min'" :value="__('Min')" class="text-xs" />
                        <x-text-input :id="$field . '_min'" :name="$field . '_min'" type="number" step="0.01"
                                      class="mt-1 block w-full text-sm"
                                      :value="old($field . '_min', isset($hop) ? $hop->getRawOriginal($field . '_min') : '')" />
                    </div>
                    <div class="flex-1">
                        <x-input-label :for="$field . '_max'" :value="__('Max')" class="text-xs" />
                        <x-text-input :id="$field . '_max'" :name="$field . '_max'" type="number" step="0.01"
                                      class="mt-1 block w-full text-sm"
                                      :value="old($field . '_max', isset($hop) ? $hop->getRawOriginal($field . '_max') : '')" />
                    </div>
                </div>
                <x-input-error :messages="$errors->get($field . '_min')" class="mt-1 text-xs" />
                <x-input-error :messages="$errors->get($field . '_max')" class="mt-1 text-xs" />
            </div>
        @endforeach
    </div>
</div>

<div>
    <h3 class="text-sm font-semibold text-hops-darkest uppercase tracking-wider mb-3 mt-2">
        {{ __('Aroma Scores (0–10)') }}
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            'aroma_citrusy' => 'Citrusy',
            'aroma_fruity' => 'Fruity',
            'aroma_floral' => 'Floral',
            'aroma_herbal' => 'Herbal',
            'aroma_spicy' => 'Spicy',
            'aroma_resinous' => 'Resinous',
            'aroma_sugarlike' => 'Sugarlike',
            'aroma_misc' => 'Misc',
        ] as $field => $label)
            <div>
                <x-input-label :for="$field" :value="__($label)" />
                <x-text-input :id="$field" :name="$field" type="number" min="0" max="10"
                              class="mt-1 block w-full"
                              :value="old($field, $hop->$field ?? '')" />
                <x-input-error :messages="$errors->get($field)" class="mt-2" />
            </div>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label :value="__('Descriptors')" />
        <div class="mt-2 grid grid-cols-2 gap-2">
            @foreach(\HopsWeb\Enums\HopDescriptor::cases() as $case)
                <label class="flex items-center gap-2 text-sm text-hops-ink">
                    <input type="checkbox" name="descriptors[]" value="{{ $case->value }}"
                           class="rounded border-hops-warm text-hops-accent focus:ring-hops-accent"
                           {{ in_array($case->value, old('descriptors', isset($hop) && $hop->descriptors ? $hop->descriptors : [])) ? 'checked' : '' }}
                    />
                    {{ ucfirst($case->value) }}
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <x-input-label :value="__('Lineage')" />
        <div class="mt-2 grid grid-cols-1 gap-2">
            @foreach(\HopsWeb\Enums\HopLineage::cases() as $case)
                <label class="flex items-center gap-2 text-sm text-hops-ink">
                    <input type="checkbox" name="lineage[]" value="{{ $case->value }}"
                           class="rounded border-hops-warm text-hops-accent focus:ring-hops-accent"
                           {{ in_array($case->value, old('lineage', isset($hop) && $hop->lineage ? $hop->lineage : [])) ? 'checked' : '' }}
                    />
                    {{ $case->name }}
                </label>
            @endforeach
        </div>
    </div>
</div>

<div>
    <h3 class="text-sm font-semibold text-hops-darkest uppercase tracking-wider mb-3 mt-2">
        {{ __('Classification') }}
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        <div>
            <x-input-label for="aromaticity" :value="__('Aromaticity')" />
            <select id="aromaticity" name="aromaticity"
                    class="mt-1 block w-full border-hops-warm focus:border-hops-accent focus:ring-hops-accent rounded-md shadow-sm">
                <option value="">—</option>
                @foreach(\HopsWeb\Enums\Aromaticity::cases() as $case)
                    <option value="{{ $case->value }}"
                            {{ old('aromaticity', $hop->aromaticity?->value ?? '') === $case->value ? 'selected' : '' }}>
                        {{ ucfirst($case->value) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label for="thiols" :value="__('Thiols')" />
            <select id="thiols" name="thiols"
                    class="mt-1 block w-full border-hops-warm focus:border-hops-accent focus:ring-hops-accent rounded-md shadow-sm">
                <option value="">—</option>
                @foreach(\HopsWeb\Enums\Aromaticity::cases() as $case)
                    <option value="{{ $case->value }}"
                            {{ old('thiols', $hop->thiols?->value ?? '') === $case->value ? 'selected' : '' }}>
                        {{ ucfirst($case->value) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label for="bitterness" :value="__('Bitterness')" />
            <select id="bitterness" name="bitterness"
                    class="mt-1 block w-full border-hops-warm focus:border-hops-accent focus:ring-hops-accent rounded-md shadow-sm">
                <option value="">—</option>
                @foreach(\HopsWeb\Enums\Bitterness::cases() as $case)
                    <option value="{{ $case->value }}"
                            {{ old('bitterness', $hop->bitterness?->value ?? '') === $case->value ? 'selected' : '' }}>
                        {{ ucfirst($case->value) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label for="maturity" :value="__('Maturity')" />
            <select id="maturity" name="maturity"
                    class="mt-1 block w-full border-hops-warm focus:border-hops-accent focus:ring-hops-accent rounded-md shadow-sm">
                <option value="">—</option>
                @foreach(\HopsWeb\Enums\HopMaturity::cases() as $case)
                    <option value="{{ $case->value }}"
                            {{ old('maturity', $hop->maturity?->value ?? '') === $case->value ? 'selected' : '' }}>
                        {{ $case->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label :value="__('Yield (kg/ha)')" />
            <div class="flex gap-2 mt-1">
                <div class="flex-1">
                    <x-input-label for="yield_min" :value="__('Min')" class="text-xs" />
                    <x-text-input id="yield_min" name="yield_min" type="number" min="0"
                                  class="mt-1 block w-full"
                                  :value="old('yield_min', $hop->yield_min ?? '')" />
                    <x-input-error :messages="$errors->get('yield_min')" class="mt-1 text-xs" />
                </div>
                <div class="flex-1">
                    <x-input-label for="yield_max" :value="__('Max')" class="text-xs" />
                    <x-text-input id="yield_max" name="yield_max" type="number" min="0"
                                  class="mt-1 block w-full"
                                  :value="old('yield_max', $hop->yield_max ?? '')" />
                    <x-input-error :messages="$errors->get('yield_max')" class="mt-1 text-xs" />
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <h3 class="text-sm font-semibold text-hops-darkest uppercase tracking-wider mb-3 mt-2">
        {{ __('Disease Resistance') }}
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            'wilt_disease' => 'Wilt Disease',
            'downy_mildew' => 'Downy Mildew',
            'powdery_mildew' => 'Powdery Mildew',
            'aphid' => 'Aphid',
        ] as $field => $label)
            <div>
                <x-input-label :for="$field" :value="__($label)" />
                <select id="{{ $field }}" name="{{ $field }}"
                        class="mt-1 block w-full border-hops-warm focus:border-hops-accent focus:ring-hops-accent rounded-md shadow-sm">
                    <option value="">—</option>
                    @foreach(\HopsWeb\Enums\Resistance::cases() as $case)
                        <option value="{{ $case->value }}"
                                {{ old($field, $hop->$field?->value ?? '') === $case->value ? 'selected' : '' }}>
                            {{ ucfirst($case->value) }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div>
</div>
