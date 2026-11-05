{{-- Opiniones de los clientes después de sus citas (admin). --}}
@php
    $promedio = (float) ($promedioGeneral ?? 0);
    $coma = fn ($n) => number_format((float) $n, 1, ',', '.');
@endphp

<x-app-layout titulo="Reseñas">
    <x-slot name="header">
        <div class="grid gap-1">
            <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Reseñas</h1>
            <p class="text-texto-suave max-w-prose">Lo que opinan los clientes después de una cita completada. Solo las ve el equipo.</p>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-8">
        @if ($resenas->total() === 0)
            <div class="card p-8 grid gap-2">
                <p class="font-display text-2xl text-tinta">Todavía no hay reseñas.</p>
                <p class="text-texto-suave max-w-prose">Cuando marques una cita como completada, el cliente podrá calificarla desde "Mis citas".</p>
            </div>
        @else
            <div class="grid lg:grid-cols-[20rem_minmax(0,1fr)] gap-6 items-start">
                <aside class="grid gap-6 lg:sticky lg:top-6">
                    <section class="card p-6 grid gap-2 bg-abismo !border-abismo" aria-label="Promedio general">
                        <span class="text-sm text-sobre-abismo-suave">Promedio general</span>
                        <p class="flex items-baseline gap-2">
                            <span class="font-display text-6xl text-white leading-none tabular">{{ $coma($promedio) }}</span>
                            <span class="text-sobre-abismo-suave">de 5</span>
                        </p>
                        <p class="text-xl tracking-wider leading-none" aria-hidden="true">
                            @for ($i = 1; $i <= 5; $i++)<span class="{{ $i <= round($promedio) ? 'text-agua' : 'text-white/20' }}">★</span>@endfor
                        </p>
                        <span class="text-sm text-sobre-abismo-suave">{{ $resenas->total() }} {{ $resenas->total() === 1 ? 'reseña' : 'reseñas' }}</span>
                    </section>

                    @if ($promedioPorServicio->isNotEmpty())
                        <section class="card p-6 grid gap-3" aria-labelledby="t-por-servicio">
                            <h2 id="t-por-servicio" class="font-display text-xl">Por servicio</h2>
                            <ul class="grid divide-y divide-linea">
                                @foreach ($promedioPorServicio as $item)
                                    <li class="flex items-baseline justify-between gap-3 py-2">
                                        <span class="min-w-0 text-tinta">{{ \App\Models\Servicio::make(['nombre' => $item->nombre])->nombre_publico }}</span>
                                        <span class="shrink-0 text-sm tabular">
                                            <span class="font-semibold text-tinta">{{ $coma($item->promedio) }}</span><span class="text-laguna" aria-hidden="true"> ★</span>
                                            <span class="text-texto-suave">({{ $item->total_resenas }})</span>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif
                </aside>

                <section class="grid gap-4" aria-labelledby="t-todas">
                    <h2 id="t-todas" class="font-display text-xl">Más recientes</h2>
                    <ul class="grid gap-4">
                        @foreach ($resenas as $resena)
                            <li class="card p-5 md:p-6 grid gap-3">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="font-semibold text-tinta">
                                            @if ($resena->usuario)
                                                <a href="{{ route('usuario.show', $resena->usuario->id) }}" class="hover:text-oceano">{{ $resena->usuario->name }}</a>
                                            @else
                                                Cliente sin cuenta
                                            @endif
                                        </p>
                                        <p class="text-sm text-texto-suave">
                                            @if ($resena->cita)
                                                <a href="{{ route('citas.show', $resena->cita) }}" class="hover:text-oceano first-letter:uppercase inline-block">{{ $resena->cita->fecha_larga }}</a>
                                                · {{ $resena->cita->servicios_texto }}
                                            @endif
                                        </p>
                                    </div>
                                    <p class="text-lg leading-none tracking-wider shrink-0" aria-label="{{ $resena->calificacion }} de 5 estrellas">
                                        @for ($i = 1; $i <= 5; $i++)<span class="{{ $i <= $resena->calificacion ? 'text-laguna' : 'text-linea' }}" aria-hidden="true">★</span>@endfor
                                    </p>
                                </div>
                                @if ($resena->comentario)
                                    <blockquote class="font-display italic text-lg text-texto leading-snug border-l-2 border-agua pl-4">{{ $resena->comentario }}</blockquote>
                                @endif
                                <p class="text-xs text-texto-suave">Escrita el {{ $resena->created_at->translatedFormat('j \d\e F \d\e Y') }}</p>
                            </li>
                        @endforeach
                    </ul>
                    @if ($resenas->hasPages())
                        <div class="pt-2">{{ $resenas->links() }}</div>
                    @endif
                </section>
            </div>
        @endif
    </div>
</x-app-layout>
