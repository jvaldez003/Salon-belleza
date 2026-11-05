@props(['active'])

<a {{ $attributes->class([
    'inline-flex items-center gap-1 px-1 pt-1 border-b-2 text-[0.9375rem] transition-colors',
    'border-oceano text-tinta font-semibold' => $active ?? false,
    'border-transparent text-texto hover:text-oceano hover:border-linea' => ! ($active ?? false),
]) }}>
    {{ $slot }}
</a>
