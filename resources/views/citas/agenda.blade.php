{{--
    Agenda del salón (equipo). Vista "día": citas en orden con hora de inicio y fin, separadas en mañana y tarde.
    Vista "semana": una columna por día que el salón atiende (tabla horarios).
--}}
@php
    $ref       = $fechaBase ?? \Carbon\Carbon::parse($fecha);
    $esSemana  = $vista === 'semana';
    $abiertos  = \App\Models\Horario::where('activo', true)->pluck('dia')->map(fn ($d) => (int) $d)->all();
    $especialistas = \App\Models\Empleado::pluck('nombre', 'id')->map(fn ($n) => Str::of($n)->explode(' ')->first());
    $moneda    = fn ($v) => '$' . number_format($v, 0, ',', '.');
    $horaFin   = function ($c) { $f = $c->horaFin(); return $f->format('g:i') . ($f->hour < 12 ? ' a. m.' : ' p. m.'); };
    $conQuien  = fn ($c) => $c->servicios->pluck('pivot.empleado_id')->filter()->unique()->map(fn ($id) => $especialistas[$id] ?? null)->filter()->implode(', ');

    $paso = $esSemana ? 'addWeek' : 'addDay';
    $atras = $esSemana ? 'subWeek' : 'subDay';
    $titulo = $esSemana
        ? 'Semana del ' . $inicioSemana->translatedFormat('j \d\e F') . ' al ' . $finSemana->translatedFormat('j \d\e F')
        : Str::ucfirst($ref->translatedFormat('l j \d\e F'));
    $esHoyRango = $esSemana ? now()->between($inicioSemana, $finSemana) : $ref->isToday();
@endphp

<x-app-layout titulo="Agenda">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Agenda</h1>
                <p class="text-lg text-texto">{{ $titulo }}@if ($esHoyRango && ! $esSemana)<span class="text-texto-suave"> · hoy</span>@endif</p>
            </div>
            <x-boton :href="route('citas.create', ['fecha' => $ref->format('Y-m-d')])">Nueva cita</x-boton>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        {{-- Controles: día/semana, anterior/hoy/siguiente, ir a una fecha --}}
        <div class="flex flex-wrap items-center gap-3">
            <div class="inline-flex p-1 rounded-full bg-bruma" role="group" aria-label="Tipo de vista">
                @foreach (['dia' => 'Día', 'semana' => 'Semana'] as $v => $t)
                    <a href="{{ route('citas.agenda', ['vista' => $v, 'fecha' => $ref->format('Y-m-d')]) }}"
                       @if ($vista === $v) aria-current="page" @endif
                       @class(['px-4 py-1.5 rounded-full text-[0.9375rem] font-medium transition-colors', 'bg-white text-oceano shadow-sm' => $vista === $v, 'text-texto hover:text-oceano' => $vista !== $v])>{{ $t }}</a>
                @endforeach
            </div>

            <div class="inline-flex items-center gap-1">
                <a href="{{ route('citas.agenda', ['vista' => $vista, 'fecha' => $ref->copy()->$atras()->format('Y-m-d')]) }}"
                   class="w-10 h-10 grid place-items-center rounded-full border border-linea text-oceano hover:bg-bruma" aria-label="{{ $esSemana ? 'Semana anterior' : 'Día anterior' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <a href="{{ route('citas.agenda', ['vista' => $vista, 'fecha' => now()->format('Y-m-d')]) }}"
                   @class(['px-4 h-10 inline-flex items-center rounded-full border text-[0.9375rem] font-medium', 'border-oceano text-oceano' => ! $esHoyRango, 'border-linea text-texto-suave' => $esHoyRango])>Hoy</a>
                <a href="{{ route('citas.agenda', ['vista' => $vista, 'fecha' => $ref->copy()->$paso()->format('Y-m-d')]) }}"
                   class="w-10 h-10 grid place-items-center rounded-full border border-linea text-oceano hover:bg-bruma" aria-label="{{ $esSemana ? 'Semana siguiente' : 'Día siguiente' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <form method="GET" class="inline-flex items-center gap-2 ml-auto" aria-label="Ir a una fecha">
                <input type="hidden" name="vista" value="{{ $vista }}">
                <label for="ir-fecha" class="sr-only">Ir a la fecha</label>
                <input id="ir-fecha" type="date" name="fecha" value="{{ $ref->format('Y-m-d') }}" class="form-input py-2" onchange="this.form.submit()">
                <noscript><button type="submit" class="btn-secondary">Ir</button></noscript>
            </form>
        </div>

        @if ($esSemana)
            {{-- ═════════ SEMANA ═════════ --}}
            @php $diasVisibles = $diasSemana->filter(fn ($d) => in_array($d->dayOfWeekIso, $abiertos, true) || $citas->has($d->format('Y-m-d'))); @endphp
            <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0 pb-2">
                <div class="grid gap-3 min-w-[56rem]" style="grid-template-columns: repeat({{ max($diasVisibles->count(), 1) }}, minmax(0, 1fr))">
                    @foreach ($diasVisibles as $dia)
                        @php $lista = $citas->get($dia->format('Y-m-d'), collect()); $hoy = $dia->isToday(); @endphp
                        <section @class(['card overflow-hidden flex flex-col min-h-[18rem]', '!border-oceano ring-1 ring-oceano' => $hoy]) aria-label="{{ $dia->translatedFormat('l j \d\e F') }}">
                            <a href="{{ route('citas.agenda', ['vista' => 'dia', 'fecha' => $dia->format('Y-m-d')]) }}"
                               @class(['px-4 py-3 border-b border-linea flex items-baseline justify-between gap-2 hover:bg-bruma', 'bg-bruma' => $hoy])>
                                <span>
                                    <span class="block text-xs text-texto-suave">{{ $hoy ? 'hoy' : $dia->translatedFormat('D') }}</span>
                                    <span class="font-display text-2xl leading-none text-tinta tabular">{{ $dia->day }}</span>
                                </span>
                                <span class="text-xs text-texto-suave">{{ $lista->count() ?: '' }}{{ $lista->count() ? ($lista->count() === 1 ? ' cita' : ' citas') : '' }}</span>
                            </a>
                            <div class="p-2 grid grid-cols-1 gap-2 content-start flex-1 [&>*]:min-w-0">
                                @forelse ($lista as $c)
                                    <a href="{{ route('citas.show', $c) }}" class="block rounded-xl px-3 py-2 bg-bruma hover:bg-bruma-honda transition-colors border-l-[3px] {{ $c->estado === 'confirmada' ? 'border-oceano' : ($c->estado === 'completada' ? 'border-emerald-600' : 'border-agua') }}">
                                        <span class="block text-sm font-semibold text-oceano tabular">{{ $c->hora_texto }}</span>
                                        <span class="block text-sm text-tinta truncate">{{ $c->usuario?->name ?? 'Sin cliente' }}</span>
                                        <span class="block text-xs text-texto-suave truncate">{{ $c->servicios_texto }}</span>
                                    </a>
                                @empty
                                    <p class="m-auto text-sm text-texto-suave">{{ in_array($dia->dayOfWeekIso, $abiertos, true) ? 'Sin citas' : 'Cerrado' }}</p>
                                @endforelse
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>
            <p class="text-sm text-texto-suave">Toca un día para verlo en detalle. El borde de cada cita indica su estado: azul océano confirmada, azul claro pendiente, verde completada.</p>
        @else
            {{-- ═════════ DÍA ═════════ --}}
            @php
                $lista = $citasDia ?? collect();
                $abierto = in_array($ref->dayOfWeekIso, $abiertos, true);
                $bloques = ['Mañana' => $lista->filter(fn ($c) => substr($c->hora, 0, 5) < '12:00'), 'Tarde' => $lista->filter(fn ($c) => substr($c->hora, 0, 5) >= '12:00')];
            @endphp

            <div class="grid lg:grid-cols-[minmax(0,1fr)_280px] gap-6 items-start">
                <div class="grid gap-6 min-w-0">
                    @if ($lista->isEmpty())
                        <div class="card p-10 grid gap-4 justify-items-start">
                            <p class="font-display text-2xl text-tinta">{{ $abierto ? 'No hay citas este día.' : 'El salón no atiende este día.' }}</p>
                            @if ($abierto)
                                <x-boton :href="route('citas.create', ['fecha' => $ref->format('Y-m-d')])">Agendar una cita</x-boton>
                            @endif
                        </div>
                    @else
                        @foreach ($bloques as $nombre => $grupo)
                            @continue($grupo->isEmpty())
                            <section aria-labelledby="b-{{ Str::slug($nombre) }}" class="grid gap-3">
                                <h2 id="b-{{ Str::slug($nombre) }}" class="font-display italic text-xl text-oceano">{{ $nombre }}</h2>
                                <ol class="card divide-y divide-linea overflow-hidden">
                                    @foreach ($grupo as $c)
                                        <li>
                                            <a href="{{ route('citas.show', $c) }}" class="grid sm:grid-cols-[9.5rem_minmax(0,1fr)_auto] gap-x-6 gap-y-2 px-5 py-4 hover:bg-bruma transition-colors">
                                                <span class="tabular">
                                                    <span class="block font-display text-xl text-oceano leading-tight">{{ $c->hora_texto }}</span>
                                                    <span class="block text-xs text-texto-suave">hasta {{ $horaFin($c) }}</span>
                                                </span>
                                                <span class="min-w-0">
                                                    <span class="block font-semibold text-tinta">{{ $c->usuario?->name ?? 'Sin cliente' }}</span>
                                                    <span class="block text-[0.9375rem] text-texto">{{ $c->servicios_texto }}</span>
                                                    @if ($quien = $conQuien($c))<span class="block text-sm text-texto-suave">Con {{ $quien }}</span>@endif
                                                    @if ($c->notas)<span class="block text-sm text-texto-suave italic mt-1 line-clamp-2">“{{ $c->notas }}”</span>@endif
                                                </span>
                                                <span class="flex sm:flex-col items-center sm:items-end gap-3 sm:gap-2">
                                                    <x-estado-cita :estado="$c->estado" />
                                                    <span class="font-display text-lg text-tinta tabular">{{ $moneda($c->total) }}</span>
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ol>
                            </section>
                        @endforeach
                    @endif
                </div>

                <aside class="card p-6 grid gap-4 lg:sticky lg:top-6" aria-label="Resumen del día">
                    <h2 class="font-display text-xl">Resumen del día</h2>
                    <dl class="grid gap-3">
                        <div class="flex items-baseline justify-between gap-4"><dt class="text-texto-suave">Citas</dt><dd class="font-display text-3xl text-tinta tabular">{{ $lista->count() }}</dd></div>
                        <div class="flex items-baseline justify-between gap-4"><dt class="text-texto-suave">Ingresos estimados</dt><dd class="font-display text-2xl text-oceano tabular">{{ $moneda($lista->sum('total')) }}</dd></div>
                        @if ($lista->isNotEmpty())
                            <div class="flex items-baseline justify-between gap-4"><dt class="text-texto-suave">Primera</dt><dd class="text-tinta tabular">{{ $lista->first()->hora_texto }}</dd></div>
                            <div class="flex items-baseline justify-between gap-4"><dt class="text-texto-suave">Termina</dt><dd class="text-tinta tabular">{{ $horaFin($lista->sortBy(fn ($c) => $c->horaFin())->last()) }}</dd></div>
                            <div class="flex items-baseline justify-between gap-4"><dt class="text-texto-suave">Por confirmar</dt><dd class="text-tinta tabular">{{ $lista->where('estado', 'pendiente')->count() }}</dd></div>
                        @endif
                    </dl>
                </aside>
            </div>
        @endif
    </div>
</x-app-layout>
