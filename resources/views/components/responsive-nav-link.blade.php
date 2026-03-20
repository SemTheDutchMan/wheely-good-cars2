@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-item nav-item-block nav-item-active'
            : 'nav-item nav-item-block';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
