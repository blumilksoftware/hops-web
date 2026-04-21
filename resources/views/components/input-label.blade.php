@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-hops-dark']) }}>
    {{ $value ?? $slot }}
</label>
