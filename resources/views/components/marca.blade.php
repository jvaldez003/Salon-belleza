{{--
    Logotipo tipográfico de Arrecife (mientras no haya logo definitivo).

    Uso:
      <x-marca />                       (sobre fondos claros)
      <x-marca tono="claro" />          (sobre fondo abismo)
      <x-marca :subtitulo="false" />    (solo "ARRECIFE")
--}}
@props(['tono' => 'oscuro', 'subtitulo' => true])

@php
    $nombre = $configuracionSitio->nombre_negocio ?? 'Arrecife';
@endphp

<span {{ $attributes->class(['inline-flex items-baseline gap-2.5 leading-none']) }}>
    <span @class([
        'font-display font-semibold text-2xl tracking-[.14em] uppercase',
        'text-tinta' => $tono === 'oscuro',
        'text-white' => $tono === 'claro',
    ])>{{ $nombre }}</span>
    @if ($subtitulo)
        <span @class([
            'font-display italic text-[.95rem] hidden sm:inline',
            'text-oceano' => $tono === 'oscuro',
            'text-agua'   => $tono === 'claro',
        ])>Beauty Studio</span>
    @endif
</span>
