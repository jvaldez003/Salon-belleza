{{-- Catálogo de servicios (admin edita; otros roles solo consultan). Agrupado por categoría, con buscador. --}}
@php
    $grupos = $categorias
        ->map(fn ($c) => ['id' => (string) $c->id, 'nombre' => Str::legible($c->nombre), 'items' => $servicios->where('categoria_id', $c->id)])
        ->filter(fn ($g) => $g['items']->isNotEmpty())->values();
    $sinCat = $servicios->whereNull('categoria_id');
    if ($sinCat->isNotEmpty()) $grupos->push(['id' => 'otros', 'nombre' => 'Sin categoría', 'items' => $sinCat]);
    $esAdmin = auth()->user()->isAdmin();
    $ocultos = $servicios->where('activo', false)->count();
@endphp

<x-app-layout titulo="Servicios">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Servicios</h1>
                <p class="text-texto-suave">
                    {{ $servicios->count() }} servicios en {{ $grupos->count() }} {{ $grupos->count() === 1 ? 'categoría' : 'categorías' }}@if ($ocultos), {{ $ocultos }} {{ $ocultos === 1 ? 'oculto' : 'ocultos' }}@endif.
                </p>
            </div>
            @if ($esAdmin)
                <div class="flex flex-wrap gap-3">
                    <x-boton variante="secundario" :href="route('categorias.index')">Categorías</x-boton>
                    <x-boton :href="route('servicios.create')">Nuevo servicio</x-boton>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6"
         x-data="{ q: '', normal: (t) => (t || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '') }">
        <x-aviso />

        @if ($servicios->isEmpty())
            <div class="card p-10 grid gap-4 justify-items-start">
                <p class="font-display text-2xl text-tinta">Todavía no hay servicios.</p>
                @if ($esAdmin)<x-boton :href="route('servicios.create')">Crear el primer servicio</x-boton>@endif
            </div>
        @else
            <div class="max-w-md">
                <label for="buscar" class="sr-only">Buscar servicio</label>
                <input id="buscar" type="search" x-model="q" class="form-input" placeholder="Buscar un servicio">
            </div>

            @foreach ($grupos as $g)
                <section class="grid gap-3" aria-labelledby="g-{{ $g['id'] }}"
                         x-show="!q || {{ Js::from($g['items']->map(fn ($s) => Str::lower(Str::ascii($s->nombre)))->values()) }}.some(n => n.includes(normal(q)))">
                    <h2 id="g-{{ $g['id'] }}" class="font-display italic text-xl text-oceano">{{ $g['nombre'] }} <span class="not-italic font-sans text-sm text-texto-suave">({{ $g['items']->count() }})</span></h2>
                    <ul class="card divide-y divide-linea overflow-hidden">
                        @foreach ($g['items'] as $s)
                            <li x-show="!q || {{ Js::from(Str::lower(Str::ascii($s->nombre))) }}.includes(normal(q))"
                                @class(['grid grid-cols-[3rem_minmax(0,1fr)_auto] sm:grid-cols-[3rem_minmax(0,1fr)_7rem_6rem_auto] gap-x-4 gap-y-1 items-center px-4 sm:px-5 py-3', 'bg-bruma/60' => ! $s->activo])>
                                <x-foto :src="optional($s->imagenes->first())->url" proporcion="1/1" :inicial="$s->nombre_publico" class="w-12 !rounded-xl" />
                                <div class="min-w-0">
                                    <p class="font-semibold text-tinta truncate">{{ $s->nombre_publico }}</p>
                                    <p class="text-sm text-texto-suave truncate">
                                        @unless ($s->activo)<span class="font-semibold text-texto">Oculto · </span>@endunless
                                        <span class="sm:hidden">{{ $s->duracion_texto }} · </span>{{ $s->descripcion ? Str::legible($s->descripcion) : '' }}
                                    </p>
                                </div>
                                <x-precio :valor="$s->precio" :desde="$s->precio_desde" class="text-right sm:order-none" />
                                <span class="hidden sm:block text-sm text-texto tabular">{{ $s->duracion_texto }}</span>
                                @if ($esAdmin)
                                    <a href="{{ route('servicios.edit', $s->id) }}" class="col-start-2 sm:col-start-auto text-sm font-semibold text-oceano hover:underline underline-offset-4 justify-self-start sm:justify-self-end">
                                        Editar<span class="sr-only"> {{ $s->nombre_publico }}</span>
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        @endif
    </div>
</x-app-layout>
