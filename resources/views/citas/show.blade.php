{{-- Detalle de una cita (clienta o equipo). --}}
@php
    $esEquipo   = auth()->user()->isAdmin() || auth()->user()->isEditor();
    $especialistas = \App\Models\Empleado::whereIn('id', $cita->servicios->pluck('pivot.empleado_id')->filter())->pluck('nombre', 'id');
    $editable   = ! in_array($cita->estado, ['cancelada', 'completada'], true);
    $wa = $configuracionSitio->whatsapp ? preg_replace('/[^0-9]/', '', $configuracionSitio->whatsapp) : null;
@endphp

<x-app-layout titulo="Detalle de la cita">
    <x-slot name="header">
        <div class="grid gap-3">
            <a href="{{ route('citas.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-texto hover:text-oceano w-fit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ $esEquipo ? 'Citas' : 'Mis citas' }}
            </a>
            <div class="flex flex-wrap items-center gap-4">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none first-letter:uppercase">{{ $cita->fecha_larga }}</h1>
                <x-estado-cita :estado="$cita->estado" />
            </div>
            <p class="text-lg text-texto">{{ $cita->hora_texto }}<span class="text-texto-suave"> · duración aprox. {{ (new \App\Models\Servicio(['duracion' => $cita->duracionMinutos()]))->duracion_texto }}</span></p>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid lg:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">
        <div class="grid gap-6 min-w-0">
            <x-aviso />

            <section class="card overflow-hidden" aria-labelledby="t-servicios">
                <h2 id="t-servicios" class="text-2xl px-6 pt-6 pb-4">Servicios</h2>
                <ul class="border-t border-linea divide-y divide-linea">
                    @foreach ($cita->servicios as $s)
                        <li class="grid grid-cols-[minmax(0,1fr)_auto] gap-x-4 gap-y-0.5 px-6 py-4">
                            <span class="font-semibold text-tinta">{{ $s->nombre_publico }}</span>
                            <x-precio :valor="$s->precio" :desde="$s->precio_desde" class="text-right" />
                            <span class="text-sm text-texto-suave">
                                {{ $s->duracion_texto }}@if ($s->pivot->empleado_id && $especialistas->has($s->pivot->empleado_id)), con {{ $especialistas[$s->pivot->empleado_id] }}@endif
                            </span>
                        </li>
                    @endforeach
                </ul>
                <div class="flex items-baseline justify-between gap-4 px-6 py-4 bg-bruma">
                    <span class="font-semibold text-tinta">Total</span>
                    <span class="font-display text-3xl text-oceano tabular">{{ '$' . number_format($cita->total, 0, ',', '.') }}</span>
                </div>
            </section>

            @if ($cita->notas)
                <section class="card p-6 grid gap-2" aria-labelledby="t-notas">
                    <h2 id="t-notas" class="font-display italic text-xl">Notas</h2>
                    <p class="leading-relaxed">{{ $cita->notas }}</p>
                </section>
            @endif
        </div>

        <aside class="grid gap-4">
            @if ($esEquipo)
                <div class="card p-6 grid gap-1">
                    <p class="text-sm text-texto-suave">Cliente</p>
                    <p class="font-semibold text-tinta">{{ $cita->usuario?->name ?? 'Sin cliente' }}</p>
                    @if ($cita->usuario?->email)<p class="text-sm text-texto break-all">{{ $cita->usuario->email }}</p>@endif
                    @if ($cita->usuario?->telefono)<p class="text-sm text-texto">{{ $cita->usuario->telefono }}</p>@endif
                </div>
            @endif

            <div class="card p-6 grid gap-3">
                @if ($editable)
                    <x-boton :href="route('citas.edit', $cita)" class="w-full">{{ $esEquipo ? 'Editar cita' : 'Cambiar día u hora' }}</x-boton>
                    <form method="POST" action="{{ route('citas.destroy', $cita) }}" x-data="{ seguro: false }">
                        @csrf @method('DELETE')
                        <button type="button" x-show="!seguro" @click="seguro = true" class="btn-secondary w-full">Cancelar cita</button>
                        <div x-show="seguro" x-cloak class="grid gap-2">
                            <p class="text-sm text-texto">¿Seguro que quieres cancelar esta cita?</p>
                            <button type="submit" class="btn-peligro w-full">Sí, cancelar cita</button>
                            <button type="button" @click="seguro = false" class="btn w-full text-texto hover:text-oceano">No, mantenerla</button>
                        </div>
                    </form>
                @elseif ($cita->puedeResenar() && $cita->user_id === auth()->id())
                    <x-boton :href="route('resenas.create', $cita)" class="w-full">Dejar reseña</x-boton>
                @else
                    <p class="text-sm text-texto-suave">Esta cita está {{ $cita->estado }} y ya no se puede cambiar.</p>
                @endif
                @if ($wa && ! $esEquipo)
                    <a href="https://wa.me/{{ $wa }}?text={{ urlencode('Hola, tengo una pregunta sobre mi cita del ' . $cita->fecha_larga . ' a las ' . $cita->hora_texto) }}"
                       target="_blank" rel="noopener" class="text-center text-sm font-semibold text-oceano hover:underline underline-offset-4">Escribir por WhatsApp</a>
                @endif
            </div>
        </aside>
    </div>
</x-app-layout>
