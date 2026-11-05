@extends('layouts.public')

@section('title', $configuracionSitio->nombre_negocio ?? 'Arrecife')
@section('subtitle', 'Beauty Studio en Candelaria')

@php
    // Categorías que tienen servicios activos (las demás no se muestran para no llevar a secciones vacías)
    $sinCategoria = $servicios->whereNull('categoria_id');
    $grupos = $categorias
        ->map(fn ($c) => ['id' => (string) $c->id, 'categoria' => $c, 'nombre' => Str::legible($c->nombre), 'servicios' => $servicios->where('categoria_id', $c->id)])
        ->filter(fn ($g) => $g['servicios']->isNotEmpty())
        ->values();
    if ($sinCategoria->isNotEmpty()) {
        $grupos->push(['id' => 'otros', 'categoria' => null, 'nombre' => 'Otros servicios', 'servicios' => $sinCategoria]);
    }

    // Foto de una categoría: galería primero, luego el campo "imagen"
    $fotoCategoria = function ($c, $preferida = 0) {
        if (! $c) return null;
        $imgs = $c->imagenes ?? collect();
        return optional($imgs->get($preferida) ?? $imgs->first())->url ?: ($c->imagen ?: null);
    };

    $whatsappNumero = $configuracionSitio->whatsapp_numero;
    $nombreNegocio  = $configuracionSitio->nombre_negocio ?? 'Arrecife';
@endphp

@section('content')

{{-- ═════════ HERO: superficie + foto con luz de agua ═════════ --}}
@php
    $diapositivas = $banners->map(fn ($b) => [
        'titulo'    => $b->titulo,
        'texto'     => $b->subtitulo,
        'foto'      => $b->imagen_url,
        'boton'     => $b->texto_boton,
        'enlace'    => $b->link_boton,
    ])->values();
    if ($diapositivas->isEmpty()) {
        $diapositivas->push([
            'titulo' => 'Tu lugar para desconectarte',
            'texto'  => 'Faciales, capilares, corporales, cejas y pestañas en un espacio tranquilo en Candelaria.',
            'foto'   => $configuracionSitio->quienes_somos_imagen,
            'boton'  => null, 'enlace' => null,
        ]);
    }
    $total = $diapositivas->count();
@endphp

<section class="bg-superficie" aria-label="Presentación"
         x-data="{ activa: 0, total: {{ $total }}, ir(n) { this.activa = (n + this.total) % this.total } }">
    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-16 lg:py-20 grid md:grid-cols-[1.05fr_1fr] gap-8 md:gap-14 lg:gap-20 items-center">

        <div class="grid gap-6 min-w-0">
            <h1 class="opsz-max font-medium tracking-[.02em] leading-[.9] text-[clamp(3.25rem,1.5rem+8vw,8rem)]">
                {{ $nombreNegocio }}
                <span class="block mt-3 font-normal italic tracking-normal text-oceano text-[.32em]">Beauty Studio</span>
            </h1>

            @foreach ($diapositivas as $i => $d)
                <div x-show="activa === {{ $i }}" @if ($i > 0) x-cloak @endif class="grid gap-4"
                     x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0">
                    <p class="font-display text-[clamp(1.5rem,1.1rem+1.4vw,2.1rem)] leading-tight text-tinta max-w-[22ch]">{{ Str::legible($d['titulo']) }}</p>
                    @if ($d['texto'])
                        <p class="text-lg leading-relaxed text-texto max-w-[46ch] line-clamp-4">{{ Str::legible($d['texto']) }}</p>
                    @endif
                    <div class="flex flex-wrap gap-3 pt-2">
                        @if ($d['boton'] && $d['enlace'])
                            <x-boton :href="$d['enlace']">{{ $d['boton'] }}</x-boton>
                        @else
                            <x-boton :href="$urlReserva">Reservar cita</x-boton>
                        @endif
                        <x-boton variante="secundario" href="#servicios">Ver servicios y precios</x-boton>
                    </div>
                </div>
            @endforeach

            @if ($total > 1)
                <div class="flex items-center gap-3 pt-2" aria-label="Cambiar presentación">
                    <button type="button" @click="ir(activa - 1)" class="w-10 h-10 rounded-full border border-linea text-oceano hover:bg-bruma transition-colors flex items-center justify-center" aria-label="Anterior">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <span class="text-sm text-texto-suave tabular"><span x-text="activa + 1">1</span> de {{ $total }}</span>
                    <button type="button" @click="ir(activa + 1)" class="w-10 h-10 rounded-full border border-linea text-oceano hover:bg-bruma transition-colors flex items-center justify-center" aria-label="Siguiente">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            @endif
        </div>

        {{-- Foto en arco con la luz de agua (único efecto llamativo del sitio) --}}
        <div class="order-first md:order-none relative overflow-hidden bg-bruma-honda aspect-[4/4.4] md:aspect-4/5 max-w-full rounded-t-[160px] md:rounded-t-[220px] rounded-b-foto isolate">
            @foreach ($diapositivas as $i => $d)
                <div class="absolute inset-0" x-show="activa === {{ $i }}" @if ($i > 0) x-cloak @endif
                     x-transition:enter="transition-opacity ease-out duration-700" x-transition:enter-start="opacity-0"
                     x-transition:leave="transition-opacity ease-in duration-500" x-transition:leave-end="opacity-0">
                    <x-foto :src="$d['foto']" :alt="Str::legible($d['titulo'])" foco="50% 30%" inicial="A"
                            :prioridad="$i === 0" class="h-full aspect-auto !rounded-none" />
                </div>
            @endforeach
            <x-luz-agua />
            <div class="absolute inset-0 pointer-events-none bg-gradient-to-b from-transparent from-55% to-abismo/45" aria-hidden="true"></div>
        </div>
    </div>
</section>

{{-- ═════════ SERVICIOS: selector de categoría + carta de precios ═════════ --}}
@if ($grupos->isNotEmpty())
<section id="servicios" class="scroll-mt-20 bg-gradient-to-b from-superficie to-bruma to-[18rem] py-14 md:py-24"
         aria-labelledby="titulo-servicios"
         x-data="{ cat: '{{ $grupos->first()['id'] }}' }">
    <div class="max-w-arrecife mx-auto px-4 sm:px-6">

        <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
            <h2 id="titulo-servicios" class="text-[clamp(1.75rem,1.2rem+2.2vw,2.75rem)] leading-tight">Servicios y precios</h2>
            <p class="max-w-[46ch] text-[0.9375rem] text-texto-suave">Elige una categoría para ver sus servicios. Los precios son en pesos colombianos.</p>
        </div>

        {{-- Selector de categorías (fila deslizable en el celular) --}}
        <div class="grid grid-flow-col auto-cols-[minmax(150px,190px)] gap-4 overflow-x-auto snap-x snap-mandatory pb-3 -mx-4 px-4 sm:mx-0 sm:px-0"
             role="group" aria-label="Categorías">
            @foreach ($grupos as $g)
                <button type="button" @click="cat = '{{ $g['id'] }}'"
                        :aria-pressed="(cat === '{{ $g['id'] }}').toString()"
                        class="group snap-start grid gap-2.5 text-left rounded-foto">
                    <x-foto :src="$fotoCategoria($g['categoria'])" :inicial="$g['nombre']" proporcion="4/5"
                            class="transition-shadow duration-200 ring-offset-2 ring-offset-superficie"
                            ::class="cat === '{{ $g['id'] }}' ? 'ring-2 ring-oceano' : 'group-hover:ring-1 group-hover:ring-linea'" />
                    <span class="font-semibold text-[0.9375rem] leading-snug"
                          :class="cat === '{{ $g['id'] }}' ? 'text-oceano' : 'text-tinta'">{{ $g['nombre'] }}</span>
                    <span class="-mt-2 text-xs text-texto-suave">{{ $g['servicios']->count() }} {{ $g['servicios']->count() === 1 ? 'servicio' : 'servicios' }}</span>
                </button>
            @endforeach
        </div>

        {{-- Carta de precios de la categoría elegida --}}
        @foreach ($grupos as $g)
            <div x-show="cat === '{{ $g['id'] }}'" @if (! $loop->first) x-cloak @endif
                 class="mt-10 md:mt-14 grid md:grid-cols-[5fr_7fr] gap-8 md:gap-14 items-start">

                <x-foto :src="$fotoCategoria($g['categoria'], 1)" :alt="$g['nombre']" :inicial="$g['nombre']"
                        class="aspect-[16/10] md:aspect-4/5 md:sticky md:top-24">
                    <div class="foto-velo">
                        <p class="font-display text-3xl leading-none">{{ $g['nombre'] }}</p>
                        <p class="mt-1.5 text-sm text-sobre-abismo-suave">{{ $g['servicios']->count() }} {{ $g['servicios']->count() === 1 ? 'servicio' : 'servicios' }}</p>
                    </div>
                </x-foto>

                <div class="min-w-0">
                    <h3 class="sr-only">{{ $g['nombre'] }}</h3>
                    <ul class="border-t border-linea">
                        @foreach ($g['servicios']->sortBy('precio') as $s)
                            @php $nombre = $s->nombre_publico; $desde = $s->precio_desde; $fotoServicio = optional($s->imagenes->first())->url; @endphp
                            <li class="py-4 border-b border-linea flex gap-4 items-start">
                                @if ($fotoServicio)
                                    <x-foto :src="$fotoServicio" proporcion="1/1" class="w-14 shrink-0 !rounded-xl" />
                                @endif
                                <div class="flex-1 min-w-0 grid grid-cols-[minmax(0,1fr)_auto] gap-x-4 gap-y-1">
                                    <span class="font-semibold text-tinta leading-snug">{{ $nombre }}</span>
                                    <x-precio :valor="$s->precio" :desde="$desde" class="text-right" />
                                    @if ($s->descripcion)
                                        <p class="col-span-2 text-sm text-texto-suave leading-relaxed line-clamp-2">{{ Str::legible($s->descripcion) }}</p>
                                    @endif
                                    <span class="col-span-2 text-xs text-texto-suave">{{ $s->duracion_texto }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-7 flex flex-wrap items-center gap-3">
                        <x-boton :href="$urlReserva">Reservar cita</x-boton>
                        @if ($whatsappNumero)
                            <x-boton variante="secundario" target="_blank" rel="noopener"
                                     :href="'https://wa.me/' . $whatsappNumero . '?text=' . urlencode('Hola, quiero preguntar por los servicios de ' . Str::lower($g['nombre']))">
                                Preguntar por WhatsApp
                            </x-boton>
                        @endif
                    </div>
                    <p class="mt-4 text-sm text-texto-suave">Los precios pueden variar según el caso. Te confirmamos el valor final antes del servicio.</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ═════════ QUIÉNES SOMOS (empieza el descenso al abismo) ═════════ --}}
@if ($configuracionSitio->quienes_somos_texto)
<section class="bg-gradient-to-b from-bruma via-bruma-honda via-[calc(100%-9rem)] to-abismo pt-14 md:pt-24 pb-36 md:pb-48" aria-labelledby="titulo-nosotros">
    <div class="max-w-arrecife mx-auto px-4 sm:px-6 grid md:grid-cols-[4fr_6fr] gap-8 md:gap-20 items-center">
        <x-foto :src="$configuracionSitio->quienes_somos_imagen" alt="El equipo de Arrecife en el estudio" foco="50% 35%"
                proporcion="3/4" class="aspect-[4/4.2] md:aspect-3/4 shadow-[0_30px_60px_-30px_rgb(10_47_107/.5)]" />
        <div class="grid gap-5 min-w-0">
            <h2 id="titulo-nosotros" class="text-[clamp(1.75rem,1.2rem+2.2vw,2.75rem)] leading-tight">
                {{ Str::legible($configuracionSitio->quienes_somos_titulo ?: 'Quiénes somos') }}
            </h2>
            <p class="max-w-[60ch] text-lg leading-relaxed">{{ Str::limit(Str::legible($configuracionSitio->quienes_somos_texto), 330) }}</p>
            @if ($tieneNosotros ?? false)
                <div><x-boton variante="secundario" :href="route('nosotros')">Conocer el estudio</x-boton></div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ═════════ RESERVA (abismo) ═════════ --}}
@include('partials.reserva')

@endsection
