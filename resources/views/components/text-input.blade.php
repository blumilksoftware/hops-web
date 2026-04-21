@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-hops-warm focus:border-hops-accent focus:ring-hops-accent rounded-md shadow-sm']) }}>
