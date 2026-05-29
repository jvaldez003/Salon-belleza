@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-rose-primary text-start text-base font-bold text-rose-deeper bg-rose-light/10 focus:outline-none focus:text-rose-deeper focus:bg-rose-light/20 focus:border-rose-dark transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-warm-600 hover:text-warm-800 hover:bg-cream hover:border-warm-300 focus:outline-none focus:text-warm-800 focus:bg-cream focus:border-warm-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
