@extends('layouts.public')

@section('title', $configuracionSitio->nombre_negocio ?? 'Arrecife')
@section('subtitle', 'Protocolos')

@php
    // La descripción se ordena en intro, pasos, duración y notas con Protocolo::ficha (ver el modelo).

    // Color de cada paso: baja de "agua clara" a "abismo" a medida que avanza el procedimiento
    $colorPaso = function (int $i, int $total) {
        $t = $total > 1 ? $i / ($total - 1) : 0;
        $desde = [127, 216, 255]; $hasta = [10, 47, 107];
        $rgb = array_map(fn ($a, $b) => (int) round($a + ($b - $a) * $t), $desde, $hasta);
        $lum = (0.2126 * $rgb[0] + 0.7152 * $rgb[1] + 0.0722 * $rgb[2]) / 255;
        return ['fondo' => sprintf('#%02X%02X%02X', ...$rgb), 'texto' => $lum > 0.5 ? '#0A2F6B' : '#FFFFFF'];
    };

    $waNumero = $configuracionSitio->whatsapp_numero;

    $items = $protocolos->values()->map(fn ($p) => [
        'id'     => 'protocolo-' . $p->id,
        'modelo' => $p,
        'nombre' => $p->nombre_publico,
        'ficha'  => $p->ficha,
        'medios' => $p->medios->values(),
    ]);
    $varios = $items->count() > 1;
@endphp

@section('content')

{{-- ═════════ ENCABEZADO (superficie) ═════════ --}}
<section class="bg-superficie" aria-labelledby="titulo-protocolos">
    <div class="max-w-arrecife mx-auto px-4 sm:px-6 pt-10 md:pt-20 pb-6 md:pb-10 grid md:grid-cols-[1fr_auto] gap-x-16 gap-y-5 items-end">
        <h1 id="titulo-protocolos" class="opsz-max text-[clamp(2.75rem,1.4rem+5.5vw,6rem)] leading-[.95]">Protocolos</h1>
        <p class="text-lg leading-relaxed max-w-[44ch]">
            Cada tratamiento paso a paso: qué incluye, cuánto dura y qué tener en cuenta antes de reservar.
        </p>
    </div>
</section>

@if ($items->isEmpty())
    <section class="bg-superficie">
        <div class="max-w-arrecife mx-auto px-4 sm:px-6 pb-16">
            <p class="text-texto-suave">Pronto publicaremos aquí el detalle de nuestros tratamientos. Mientras tanto, puedes ver los <a href="{{ url('/') }}#servicios" class="text-oceano underline underline-offset-4">servicios y precios</a>.</p>
        </div>
    </section>
@else

<section class="bg-gradient-to-b from-superficie to-bruma"
         x-data="{
            sel: '{{ $items->first()['id'] }}',
            ids: @js($items->pluck('id')),
            init() {
                const h = location.hash.slice(1);
                if (this.ids.includes(h)) this.sel = h;
            },
            elegir(id) {
                this.sel = id;
                history.replaceState(null, '', '#' + id);
                this.$nextTick(() => {
                    const top = this.$refs.ficha.getBoundingClientRect().top;
                    if (top < 0 || top > window.innerHeight * .6) {
                        this.$refs.ficha.scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
                    }
                });
            }
         }">
    <div class="max-w-arrecife mx-auto md:px-6 pb-16 md:pb-24 {{ $varios ? 'md:grid md:grid-cols-[minmax(220px,3fr)_9fr] md:gap-14 lg:gap-20' : '' }} items-start">

        {{-- ── Selector de tratamientos ── --}}
        @if ($varios)
            <nav aria-label="Tratamientos"
                 class="sticky top-[72px] md:top-24 z-30 bg-superficie/95 md:bg-transparent backdrop-blur md:backdrop-blur-none border-y border-linea md:border-0">
                <ul class="flex md:flex-col gap-2 md:gap-0 overflow-x-auto px-4 md:px-0 py-3 md:py-0 snap-x">
                    @foreach ($items as $it)
                        <li class="shrink-0 snap-start md:border-b md:border-linea md:first:border-t">
                            <button type="button" @click="elegir('{{ $it['id'] }}')"
                                    :aria-pressed="(sel === '{{ $it['id'] }}').toString()"
                                    class="group w-full text-left whitespace-nowrap md:whitespace-normal rounded-full md:rounded-none px-4 md:px-0 py-2 md:py-5 border md:border-0"
                                    :class="sel === '{{ $it['id'] }}' ? 'bg-oceano border-oceano text-white md:bg-transparent md:text-oceano' : 'border-linea text-tinta hover:text-oceano'">
                                <span class="flex items-center gap-3">
                                    <span class="hidden md:block h-px bg-current transition-all duration-300"
                                          :class="sel === '{{ $it['id'] }}' ? 'w-8' : 'w-0 group-hover:w-3'" aria-hidden="true"></span>
                                    <span class="font-semibold md:font-normal md:font-display text-[0.9375rem] md:text-[1.6rem] md:leading-tight">{{ $it['nombre'] }}</span>
                                </span>
                                @if ($it['ficha']['duracion'])
                                    <span class="hidden md:block mt-1 text-sm text-texto-suave" :class="sel === '{{ $it['id'] }}' && 'md:pl-11'">{{ $it['ficha']['duracion'] }}</span>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endif

        {{-- ── Ficha del tratamiento elegido ── --}}
        <div x-ref="ficha" class="scroll-mt-36 md:scroll-mt-24 px-4 md:px-0 pt-8 md:pt-0 min-w-0" aria-live="polite">
            @foreach ($items as $it)
                @php
                    $f = $it['ficha'];
                    $medios = $it['medios'];
                    $totalPasos = count($f['pasos']);
                @endphp
                <article id="{{ $it['id'] }}" x-show="sel === '{{ $it['id'] }}'" @if (! $loop->first) x-cloak @endif
                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                         class="grid gap-12 md:gap-16" aria-labelledby="nombre-{{ $it['id'] }}">

                    {{-- Presentación: foto + datos --}}
                    <div class="grid sm:grid-cols-[5fr_6fr] gap-6 sm:gap-10 items-start">
                        <div class="min-w-0" x-data="{ activo: 0 }">
                            @if ($medios->isEmpty())
                                <x-foto :inicial="$it['nombre']" :alt="$it['nombre']" proporcion="4/5" />
                            @else
                                @foreach ($medios as $i => $m)
                                    <div x-show="activo === {{ $i }}" @if ($i > 0) x-cloak @endif>
                                        @if ($m->tipo === 'imagen')
                                            <x-foto :src="$m->url" :alt="$it['nombre'] . ', foto ' . ($i + 1)" proporcion="4/5" foco="50% 30%" :prioridad="$loop->parent->first && $i === 0" />
                                        @elseif ($m->esUrlExterna())
                                            <div class="foto-marco aspect-4/5 bg-abismo">
                                                <iframe src="{{ $m->url }}" title="Video de {{ $it['nombre'] }}" class="absolute inset-0 w-full h-full" loading="lazy"
                                                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                        @else
                                            <div class="foto-marco aspect-4/5 bg-abismo">
                                                <video class="absolute inset-0 w-full h-full object-contain" controls preload="metadata">
                                                    <source src="{{ asset('storage/' . $m->url) }}" type="video/mp4">
                                                </video>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                @if ($medios->count() > 1)
                                    <div class="mt-3 flex flex-wrap gap-2" role="group" aria-label="Fotos y videos de {{ $it['nombre'] }}">
                                        @foreach ($medios as $i => $m)
                                            <button type="button" @click="activo = {{ $i }}" :aria-pressed="(activo === {{ $i }}).toString()"
                                                    class="w-14 rounded-xl transition-opacity"
                                                    :class="activo === {{ $i }} ? 'ring-2 ring-oceano ring-offset-2 ring-offset-superficie' : 'opacity-60 hover:opacity-100'"
                                                    aria-label="{{ $m->tipo === 'imagen' ? 'Ver foto' : 'Ver video' }} {{ $i + 1 }}">
                                                @if ($m->tipo === 'imagen')
                                                    <x-foto :src="$m->url" proporcion="1/1" class="!rounded-xl" />
                                                @else
                                                    <span class="foto-marco aspect-square !rounded-xl grid place-items-center text-oceano">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86A1 1 0 008 5.14z"/></svg>
                                                    </span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="grid gap-6 min-w-0 content-start">
                            <h2 id="nombre-{{ $it['id'] }}" class="text-[clamp(2.1rem,1.4rem+3vw,3.6rem)] leading-[1.02]">{{ $it['nombre'] }}</h2>
                            @if ($f['intro'])
                                <p class="text-lg leading-relaxed">{{ $f['intro'] }}</p>
                            @endif

                            @if ($f['duracion'] || $totalPasos)
                                <dl class="grid grid-cols-2 border-y border-linea divide-x divide-linea">
                                    @if ($f['duracion'])
                                        <div class="py-4 pr-4">
                                            <dt class="text-sm text-texto-suave">Duración</dt>
                                            <dd class="mt-1 font-display text-xl text-tinta leading-snug">{{ $f['duracion'] }}</dd>
                                            @if ($f['duracionNota'])<dd class="mt-1 text-xs text-texto-suave">{{ $f['duracionNota'] }}</dd>@endif
                                        </div>
                                    @endif
                                    @if ($totalPasos)
                                        <div @class(['py-4', 'pl-5' => $f['duracion']])>
                                            <dt class="text-sm text-texto-suave">Procedimiento</dt>
                                            <dd class="mt-1 font-display text-xl text-tinta leading-snug">{{ $totalPasos }} {{ $totalPasos === 1 ? 'paso' : 'pasos' }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            @endif

                            <div class="flex flex-wrap gap-3">
                                <x-boton :href="$urlReserva">Reservar este tratamiento</x-boton>
                                @if ($waNumero)
                                    <x-boton variante="secundario" target="_blank" rel="noopener"
                                             :href="'https://wa.me/' . $waNumero . '?text=' . urlencode('Hola, quiero preguntar por el tratamiento de ' . Str::lower($it['nombre']))">
                                        Preguntar por WhatsApp
                                    </x-boton>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Paso a paso (descenso de color) + notas --}}
                    @if ($totalPasos || count($f['notas']))
                        <div class="grid lg:grid-cols-[7fr_4fr] gap-10 lg:gap-14 items-start">
                            @if ($totalPasos)
                                <div class="min-w-0">
                                    <h3 class="text-[1.75rem] leading-tight mb-6">Paso a paso</h3>
                                    <ol class="relative">
                                        @foreach ($f['pasos'] as $n => $paso)
                                            @php $c = $colorPaso($n, $totalPasos); $sig = $colorPaso(min($n + 1, $totalPasos - 1), $totalPasos); @endphp
                                            <li class="relative grid grid-cols-[2.5rem_1fr] gap-4 pb-6 last:pb-0">
                                                @unless ($loop->last)
                                                    <span class="absolute left-[1.25rem] top-10 bottom-0 w-px -translate-x-1/2"
                                                          style="background: linear-gradient({{ $c['fondo'] }}, {{ $sig['fondo'] }})" aria-hidden="true"></span>
                                                @endunless
                                                <span class="relative w-10 h-10 rounded-full grid place-items-center font-display text-lg tabular"
                                                      style="background: {{ $c['fondo'] }}; color: {{ $c['texto'] }}" aria-hidden="true">{{ $n + 1 }}</span>
                                                <span class="pt-2 leading-snug text-tinta"><span class="sr-only">Paso {{ $n + 1 }}: </span>{{ $paso }}</span>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif

                            @if (count($f['notas']))
                                <aside class="rounded-2xl border border-linea bg-superficie p-6 grid gap-4" aria-label="Antes de reservar {{ $it['nombre'] }}">
                                    <h3 class="font-display italic text-xl">Antes de reservar</h3>
                                    <ul class="grid gap-3 text-[0.9375rem] leading-relaxed">
                                        @foreach ($f['notas'] as $nota)
                                            <li class="grid grid-cols-[12px_1fr] gap-3">
                                                <span class="mt-[.75em] h-px bg-oceano" aria-hidden="true"></span>
                                                <span>{{ $nota }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </aside>
                            @endif
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Paso al abismo --}}
<div class="h-28 md:h-40 bg-gradient-to-b {{ $items->isEmpty() ? 'from-superficie' : 'from-bruma' }} to-abismo" aria-hidden="true"></div>

@include('partials.reserva')

@endsection
