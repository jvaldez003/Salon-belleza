@props(['active'])

<a {{ $attributes->class([
    'block w-full px-4 py-2.5 border-l-4 text-start text-base transition-colors',
    'border-oceano text-tinta font-semibold bg-bruma' => $active ?? false,
    'border-transparent text-texto hover:text-oceano hover:bg-bruma' => ! ($active ?? false),
]) }}>
    {{ $slot }}
</a>
