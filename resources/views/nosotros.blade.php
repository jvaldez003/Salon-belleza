@extends('layouts.public')

@section('title', $configuracionSitio->nombre_negocio ?? 'Arrecife')
@section('subtitle', 'Quiénes somos')

@php
    // Fotos de cada sección: galería (seccion_imagenes) o, si está vacía, la foto única de Configuración
    $fotos = function (string $seccion, ?string $respaldo) use ($seccionImagenes) {
        $lista = ($seccionImagenes[$seccion] ?? collect())->pluck('imagen_url')->filter()->values();
        if ($lista->isEmpty() && $respaldo) $lista = collect([$respaldo]);
        return $lista;
    };
    $fotosQS     = $fotos('quienes_somos', $configuracionSitio->quienes_somos_imagen);
    $fotosMision = $fotos('mision', $configuracionSitio->mision_imagen);
    $fotosVision = $fotos('vision', $configuracionSitio->vision_imagen);

    $bloques = collect([
        ['id' => 'mision', 'titulo' => 'Misión', 'texto' => $configuracionSitio->mision_texto, 'fotos' => $fotosMision],
        ['id' => 'vision', 'titulo' => 'Visión', 'texto' => $configuracionSitio->vision_texto, 'fotos' => $fotosVision],
    ])->filter(fn ($b) => $b['texto'] || $b['fotos']->isNotEmpty())->values();
@endphp

@section('content')

{{-- ═════════ QUIÉNES SOMOS (superficie) ═════════ --}}
<section class="bg-superficie" aria-labelledby="titulo-qs">
    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-10 md:py-20 grid md:grid-cols-[1fr_1fr] gap-10 md:gap-16 items-center">
        <div class="grid gap-6 min-w-0">
            <h1 id="titulo-qs" data-revelar="palabras" class="opsz-max text-[clamp(2.75rem,1.4rem+5.5vw,6rem)] leading-[.95]">
                {{ Str::legible($configuracionSitio->quienes_somos_titulo ?: 'Quiénes somos') }}
            </h1>
            @if ($configuracionSitio->quienes_somos_texto)
                <div class="grid gap-4 text-lg leading-relaxed max-w-[58ch]">
                    @foreach (preg_split('/\n\s*\n/', Str::legible($configuracionSitio->quienes_somos_texto)) as $parrafo)
                        <p data-revelar="sube" style="--d: {{ 300 + $loop->index * 120 }}ms">{{ $parrafo }}</p>
                    @endforeach
                </div>
            @endif
            <div class="flex flex-wrap gap-3 pt-2" data-revelar="sube" style="--d: 550ms">
                <x-boton :href="$urlReserva">Reservar cita</x-boton>
                <x-boton variante="secundario" :href="url('/') . '#servicios'">Ver servicios y precios</x-boton>
            </div>
        </div>

        {{-- Una foto: retrato grande. Dos o más: pareja escalonada. --}}
        @if ($fotosQS->count() >= 2)
            <div class="grid grid-cols-2 gap-3 sm:gap-5 items-start">
                <x-foto :src="$fotosQS[0]" alt="El equipo de Arrecife" proporcion="3/4" foco="50% 30%" prioridad
                        data-revelar="foto" data-paralaje="0.06" style="--d: 150ms" />
                <x-foto :src="$fotosQS[1]" alt="El estudio Arrecife" proporcion="3/4" foco="50% 30%" class="mt-12 sm:mt-20"
                        data-revelar="foto" data-paralaje="0.16" style="--d: 400ms" />
            </div>
        @elseif ($fotosQS->count() === 1)
            <div class="max-w-md md:ml-auto w-full">
                <x-foto :src="$fotosQS[0]" alt="El equipo de Arrecife" proporcion="3/4" foco="50% 30%" prioridad
                        data-revelar="foto" data-paralaje="0.1" style="--d: 150ms" />
            </div>
        @endif
    </div>
</section>

{{-- ═════════ MISIÓN Y VISIÓN (bruma → bruma honda) ═════════ --}}
@foreach ($bloques as $b)
    @php $invertido = $loop->even; @endphp
    <section @class([
                'py-14 md:py-24',
                'bg-gradient-to-b from-superficie to-bruma' => $loop->first,
                'bg-gradient-to-b from-bruma to-bruma-honda' => ! $loop->first,
             ])
             aria-labelledby="titulo-{{ $b['id'] }}">
        <div class="max-w-arrecife mx-auto px-4 sm:px-6 grid md:grid-cols-[5fr_7fr] gap-8 md:gap-16 items-center">

            <div @class(['grid gap-5 min-w-0', 'md:order-2' => $invertido])>
                <h2 id="titulo-{{ $b['id'] }}" data-revelar="palabras" class="text-[clamp(2rem,1.3rem+3vw,3.5rem)] leading-none">{{ $b['titulo'] }}</h2>
                @if ($b['texto'])
                    <p data-revelar="lectura" class="font-display text-[clamp(1.2rem,1rem+.8vw,1.5rem)] leading-[1.5] text-oceano max-w-[40ch]">{{ Str::legible($b['texto']) }}</p>
                @endif
            </div>

            @if ($b['fotos']->isNotEmpty())
                @php $tira = $b['fotos']->take(3); @endphp
                {{-- Tira de fotos: se desliza en el celular, escalonada en pantallas grandes --}}
                <div @class(['min-w-0', 'md:order-1' => $invertido])>
                    <div class="grid grid-flow-col auto-cols-[68%] sm:auto-cols-[44%] md:auto-cols-fr gap-3 sm:gap-4 overflow-x-auto md:overflow-visible snap-x snap-mandatory -mx-4 px-4 md:mx-0 md:px-0 pb-2 items-start">
                        @foreach ($tira as $i => $foto)
                            <x-foto :src="$foto" :alt="$b['titulo'] . ' de Arrecife, foto ' . ($i + 1)" proporcion="3/4" foco="50% 30%"
                                    :class="'snap-start ' . [0 => '', 1 => 'md:mt-10', 2 => 'md:mt-4'][$i]"
                                    data-revelar="foto" :data-paralaje="[0.05, 0.13, 0.09][$i]" :style="'--d: ' . ($i * 160) . 'ms'" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endforeach

{{-- Paso de la bruma al abismo --}}
<div class="h-28 md:h-40 bg-gradient-to-b {{ $bloques->count() > 1 ? 'from-bruma-honda' : ($bloques->isNotEmpty() ? 'from-bruma' : 'from-superficie') }} to-abismo" aria-hidden="true"></div>

@include('partials.reserva')

@endsection
