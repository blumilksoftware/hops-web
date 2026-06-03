@props(['disabled' => false])

@php
    $name = $attributes->get('name');
    $hasError = false;
    if ($name && isset($errors)) {
        $dotName = str_replace(['[', ']'], ['.', ''], $name);
        $dotName = rtrim($dotName, '.');
        
        foreach ($errors->getBags() as $bag) {
            if ($bag->has($name) || $bag->has($dotName)) {
                $hasError = true;
                break;
            }
        }
    }
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => ($hasError ? 'border-red-500 focus:border-red-500 focus:ring-red-500 text-red-900 placeholder-red-300' : 'border-hops-warm focus:border-hops-accent focus:ring-hops-accent') . ' rounded-md shadow-sm']) }}>
