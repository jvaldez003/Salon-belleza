{{--
    Lista de citas. La clienta ve las suyas ("Mis citas"); el equipo ve todas, con filtros y el nombre del cliente.
    En pantallas grandes cada fila se alinea como tabla; en el celular se apila.
--}}
@php
    $esEquipo = auth()->user()->isAdmin() || auth()->user()->isEditor();
    $hayFiltros = request()->hasAny(['buscar', 'fecha', 'estado']) && request()->anyFilled(['buscar', 'fecha', 'estado']);
@endphp

<x-app-layout :titulo="$esEquipo ? 'Citas' : 'Mis citas'">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">{{ $esEquipo ? 'Citas' : 'Mis citas' }}</h1>
                <p class="text-texto-suave">{{ $esEquipo ? 'Todas las reservas del salón.' : 'Tus reservas, de la más reciente a la más antigua.' }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if ($esEquipo)
                    <x-boton variante="secundario" :href="route('citas.agenda')">Agenda</x-boton>
                @endif
                <x-boton :href="route('citas.create')">{{ $esEquipo ? 'Nueva cita' : 'Reservar cita' }}</x-boton>
            </div>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <x-aviso />

        @if ($esEquipo)
            <form method="GET" class="flex flex-wrap items-end gap-3" role="search" aria-label="Filtrar citas">
                <div class="grid gap-1.5 flex-1 min-w-[12rem]">
                    <label for="f-buscar" class="label">Cliente</label>
                    <input id="f-buscar" type="search" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre o correo" class="form-input">
                </div>
                <div class="grid gap-1.5">
                    <label for="f-fecha" class="label">Día</label>
                    <input id="f-fecha" type="date" name="fecha" value="{{ request('fecha') }}" class="form-input">
                </div>
                <div class="grid gap-1.5">
                    <label for="f-estado" class="label">Estado</label>
                    <select id="f-estado" name="estado" class="form-input pr-10">
                        <option value="">Todos</option>
                        @foreach (['pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'completada' => 'Completada', 'cancelada' => 'Cancelada'] as $v => $t)
                            <option value="{{ $v }}" @selected(request('estado') === $v)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Filtrar</button>
                @if ($hayFiltros)
                    <a href="{{ route('citas.index') }}" class="btn text-texto hover:text-oceano">Quitar filtros</a>
                @endif
            </form>
        @endif

        @if ($citas->isEmpty())
            <div class="card p-10 grid gap-4 justify-items-start">
                <p class="font-display text-2xl text-tinta">
                    {{ $hayFiltros ? 'No hay citas con esos filtros.' : ($esEquipo ? 'Todavía no hay citas registradas.' : 'Aún no tienes citas.') }}
                </p>
                @unless ($hayFiltros)
                    <x-boton :href="route('citas.create')">{{ $esEquipo ? 'Crear la primera cita' : 'Reservar tu primera cita' }}</x-boton>
                @endunless
            </div>
        @else
            <div class="card overflow-hidden">
                {{-- Encabezados (solo pantallas grandes) --}}
                <div @class([
                        'hidden lg:grid gap-6 px-6 py-3 bg-bruma text-sm font-semibold text-texto',
                        'lg:grid-cols-[13rem_minmax(0,1fr)_minmax(0,1.4fr)_7rem_8rem_auto]' => $esEquipo,
                        'lg:grid-cols-[15rem_minmax(0,1.6fr)_7rem_8rem_auto]' => ! $esEquipo,
                     ]) aria-hidden="true">
                    <span>Fecha y hora</span>
                    @if ($esEquipo)<span>Cliente</span>@endif
                    <span>Servicios</span>
                    <span class="text-right">Total</span>
                    <span>Estado</span>
                    <span class="w-40"></span>
                </div>

                <ul class="divide-y divide-linea">
                    @foreach ($citas as $cita)
                        <li @class([
                                'grid gap-x-6 gap-y-2 px-6 py-4 items-center',
                                'lg:grid-cols-[13rem_minmax(0,1fr)_minmax(0,1.4fr)_7rem_8rem_auto]' => $esEquipo,
                                'lg:grid-cols-[15rem_minmax(0,1.6fr)_7rem_8rem_auto]' => ! $esEquipo,
                                'opacity-60' => $cita->estado === 'cancelada',
                            ])>
                            <div class="flex items-baseline justify-between gap-3 lg:block">
                                <span class="block font-semibold text-tinta first-letter:uppercase">{{ $cita->fecha_larga }}</span>
                                <span class="block text-sm text-texto-suave tabular">{{ $cita->hora_texto }}</span>
                            </div>
                            @if ($esEquipo)
                                <div class="min-w-0">
                                    <span class="block text-tinta truncate">{{ $cita->usuario?->name ?? 'Sin cliente' }}</span>
                                    <span class="block text-xs text-texto-suave truncate">{{ $cita->usuario?->email }}</span>
                                </div>
                            @endif
                            <p class="text-[0.9375rem] text-texto min-w-0">{{ $cita->servicios_texto }}</p>
                            <p class="font-display text-lg text-oceano tabular lg:text-right">{{ '$' . number_format($cita->total, 0, ',', '.') }}</p>
                            <div><x-estado-cita :estado="$cita->estado" /></div>
                            <div class="flex flex-wrap gap-x-4 gap-y-2 lg:justify-end lg:w-40 text-sm font-semibold">
                                <a href="{{ route('citas.show', $cita) }}" class="text-oceano hover:underline underline-offset-4">Ver<span class="sr-only"> la cita del {{ $cita->fecha_larga }}</span></a>
                                @if ($cita->estado !== 'cancelada' && $cita->estado !== 'completada')
                                    <a href="{{ route('citas.edit', $cita) }}" class="text-oceano hover:underline underline-offset-4">Cambiar</a>
                                @endif
                                @if ($cita->puedeResenar() && $cita->user_id === auth()->id())
                                    <a href="{{ route('resenas.create', $cita) }}" class="text-oceano hover:underline underline-offset-4">Dejar reseña</a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if ($citas->hasPages())
                <div>{{ $citas->links() }}</div>
            @endif
        @endif
    </div>
</x-app-layout>
