{{-- Equipo del salón: quién atiende, qué servicios hace y qué días trabaja. --}}
@php
    $iniciales = [1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D'];
    $hora = fn ($t) => ltrim(substr((string) $t, 0, 5), '0');
    $inactivos = $empleados->where('activo', false)->count();
@endphp

<x-app-layout titulo="Equipo">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Equipo</h1>
                <p class="text-texto-suave">
                    {{ $empleados->count() }} {{ $empleados->count() === 1 ? 'persona' : 'personas' }}@if ($inactivos), {{ $inactivos }} sin reservas por ahora @endif.
                    Los clientes eligen con quién atenderse al reservar.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <x-boton variante="secundario" :href="route('horarios.index')">Horario del salón</x-boton>
                <x-boton :href="route('empleados.create')">Nuevo empleado</x-boton>
            </div>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <x-aviso />

        @if ($empleados->isEmpty())
            <div class="card p-10 grid gap-4 justify-items-start">
                <p class="font-display text-2xl text-tinta">Todavía no hay nadie en el equipo.</p>
                <p class="text-texto-suave max-w-prose">Agrega a las personas que atienden para que los clientes puedan elegir con quién reservar.</p>
                <x-boton :href="route('empleados.create')">Agregar al primer empleado</x-boton>
            </div>
        @else
            <ul class="grid md:grid-cols-2 gap-4">
                @foreach ($empleados as $emp)
                    @php $dias = $emp->horarios->keyBy('dia'); @endphp
                    <li @class(['card p-5 grid gap-4 content-start', 'bg-bruma/60' => ! $emp->activo])>
                        <div class="grid grid-cols-[3.5rem_minmax(0,1fr)_auto] gap-4 items-center">
                            <x-foto :src="$emp->foto" proporcion="1/1" :inicial="$emp->nombre" alt="" class="w-14 !rounded-full" />
                            <div class="min-w-0">
                                <p class="font-display text-xl text-tinta truncate">{{ $emp->nombre }}</p>
                                <p class="text-sm text-texto-suave truncate">
                                    @unless ($emp->activo)<span class="font-semibold text-texto">Sin reservas por ahora · </span>@endunless
                                    {{ $emp->especialidad ?: ($emp->servicios->count() . ' ' . ($emp->servicios->count() === 1 ? 'servicio' : 'servicios')) }}
                                </p>
                            </div>
                            <a href="{{ route('empleados.edit', $emp) }}" class="text-sm font-semibold text-oceano hover:underline underline-offset-4">
                                Editar<span class="sr-only"> a {{ $emp->nombre }}</span>
                            </a>
                        </div>

                        {{-- Semana: qué días trabaja --}}
                        <div class="grid gap-1.5">
                            <p class="text-xs font-semibold text-texto">Días de trabajo</p>
                            <ol class="grid grid-cols-7 gap-1" aria-label="Días de trabajo de {{ $emp->nombre }}">
                                @foreach ($iniciales as $n => $letra)
                                    @php $d = $dias->get($n); $trabaja = $d?->activo; $abierto = $salon->get($n)?->activo; @endphp
                                    <li @class([
                                            'grid place-items-center rounded-lg py-1.5 text-xs tabular',
                                            'bg-oceano text-white' => $trabaja,
                                            'bg-bruma text-texto-suave' => ! $trabaja,
                                            'ring-1 ring-inset ring-red-300' => $trabaja && ! $abierto,
                                        ])
                                        title="{{ \App\Models\Horario::nombreDia($n) }}: {{ $trabaja ? $hora($d->hora_apertura) . ' a ' . $hora($d->hora_cierre) : 'descansa' }}{{ $trabaja && ! $abierto ? ' (el salón está cerrado)' : '' }}">
                                        <span class="font-semibold" aria-hidden="true">{{ $letra }}</span>
                                        <span class="sr-only">{{ \App\Models\Horario::nombreDia($n) }}: {{ $trabaja ? 'de ' . $hora($d->hora_apertura) . ' a ' . $hora($d->hora_cierre) : 'descansa' }}</span>
                                        @if ($trabaja)<span class="hidden sm:block text-[0.6875rem] opacity-80" aria-hidden="true">{{ $hora($d->hora_apertura) }}</span>@endif
                                    </li>
                                @endforeach
                            </ol>
                        </div>

                        {{-- Servicios --}}
                        @if ($emp->servicios->isEmpty())
                            <p class="text-sm text-texto">Sin servicios asignados: nadie podrá reservar con esta persona.
                                <a href="{{ route('empleados.edit', $emp) }}#t-servicios" class="font-semibold text-oceano hover:underline">Asignar servicios</a></p>
                        @else
                            <details class="group">
                                <summary class="text-sm text-texto cursor-pointer list-none [&::-webkit-details-marker]:hidden inline-flex items-center gap-1.5 hover:text-oceano">
                                    {{ $emp->servicios->count() }} {{ $emp->servicios->count() === 1 ? 'servicio' : 'servicios' }}:
                                    <span class="text-texto-suave truncate max-w-[16rem]">{{ $emp->servicios->take(3)->map->nombre_publico->join(', ') }}{{ $emp->servicios->count() > 3 ? '…' : '' }}</span>
                                    <svg class="w-3.5 h-3.5 shrink-0 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </summary>
                                <ul class="mt-2 flex flex-wrap gap-1.5">
                                    @foreach ($emp->servicios->sortBy('nombre') as $s)
                                        <li class="badge-blue font-medium">{{ $s->nombre_publico }}</li>
                                    @endforeach
                                </ul>
                            </details>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>
