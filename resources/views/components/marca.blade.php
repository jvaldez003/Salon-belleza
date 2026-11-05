{{--
    Logotipo horizontal de Arrecife: la concha del logo (public/img/logo-concha.png) + el nombre en Bodoni.
    El logo original es vertical (concha arriba, texto abajo); para encabezados y menús se usa esta versión horizontal.
    El logo completo está en public/img/logo-completo.png (solo sobre fondos claros: su texto es negro).

    Uso:
      <x-marca />                       sobre fondos claros
      <x-marca tono="claro" />          sobre fondo abismo
      <x-marca :subtitulo="false" />    sin "Beauty Studio"
      <x-marca :icono="false" />        solo texto
--}}
@props(['tono' => 'oscuro', 'subtitulo' => true, 'icono' => true])

@php
    $nombre = $configuracionSitio->nombre_negocio ?? 'Arrecife';
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-2.5 leading-none']) }}>
    @if ($icono)
        <img src="{{ asset('img/logo-concha.png') }}" alt="" width="171" height="160"
             @class(['h-9 w-auto shrink-0', 'drop-shadow-[0_0_6px_rgb(127_216_255/0.25)]' => $tono === 'claro'])>
    @endif
    <span class="inline-flex items-baseline gap-2.5">
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
</span>
