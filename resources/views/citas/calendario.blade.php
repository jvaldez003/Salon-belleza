{{--
    Calendario mensual de citas (FullCalendar). La clienta ve las suyas; el equipo, todas.
    Tocar un día que el salón atiende abre la reserva con esa fecha; tocar una cita abre su detalle.
--}}
@php
    $esEquipo = auth()->user()->isAdmin() || auth()->user()->isEditor();
    $horariosActivos = \App\Models\Horario::where('activo', true)->orderBy('dia')->get();
    $diasLaborales = $horariosActivos->pluck('dia')->map(fn ($d) => (int) $d)->values()->all();
    $diasOcultos = collect(range(1, 7))->diff($diasLaborales)->map(fn ($d) => $d === 7 ? 0 : $d)->values()->all();
    $horarioNegocio = $horariosActivos->map(fn ($h) => [
        'daysOfWeek' => [$h->dia === 7 ? 0 : $h->dia],
        'startTime'  => substr($h->hora_apertura, 0, 5),
        'endTime'    => substr($h->hora_cierre, 0, 5),
    ])->values()->all();
@endphp

<x-app-layout titulo="Calendario">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Calendario</h1>
                <p class="text-texto-suave">Toca un día para reservar en esa fecha, o una cita para ver su detalle.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if ($esEquipo)<x-boton variante="secundario" :href="route('citas.agenda')">Agenda</x-boton>@endif
                <x-boton :href="route('citas.create')">{{ $esEquipo ? 'Nueva cita' : 'Reservar cita' }}</x-boton>
            </div>
        </div>
    </x-slot>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
        <style>
            /* FullCalendar con los tokens de Arrecife */
            #calendario-citas {
                --fc-border-color: var(--linea);
                --fc-today-bg-color: var(--bruma);
                --fc-page-bg-color: #fff;
                --fc-neutral-bg-color: var(--bruma);
                --fc-button-bg-color: #fff;
                --fc-button-border-color: var(--linea);
                --fc-button-text-color: var(--oceano);
                --fc-button-hover-bg-color: var(--bruma);
                --fc-button-hover-border-color: var(--oceano);
                --fc-button-active-bg-color: var(--oceano);
                --fc-button-active-border-color: var(--oceano);
                --fc-event-border-color: transparent;
                font-family: Manrope, 'Segoe UI', sans-serif;
                color: var(--texto);
            }
            #calendario-citas .fc-toolbar-title { font-family: 'Bodoni Moda', Georgia, serif; font-weight: 500; color: var(--abismo); font-size: 1.75rem; }
            #calendario-citas .fc-toolbar-title::first-letter { text-transform: uppercase; }
            #calendario-citas .fc-button { border-radius: 999px !important; padding: .45rem 1rem; font-weight: 600; font-size: .875rem; box-shadow: none !important; text-transform: none; }
            #calendario-citas .fc-button-active { color: #fff !important; }
            #calendario-citas .fc-button-group { gap: .25rem; }
            #calendario-citas .fc-col-header-cell-cushion { color: var(--texto-suave); font-weight: 600; font-size: .8125rem; text-transform: capitalize; padding: .6rem 0; }
            #calendario-citas .fc-daygrid-day-number { color: var(--tinta); font-family: 'Bodoni Moda', Georgia, serif; font-size: 1.05rem; padding: .4rem .6rem; }
            #calendario-citas .fc-day-today .fc-daygrid-day-number { color: var(--oceano); font-weight: 700; }
            #calendario-citas .fc-daygrid-day.fc-day-future:hover, #calendario-citas .fc-daygrid-day.fc-day-today:hover { background: var(--bruma); cursor: pointer; }
            #calendario-citas .fc-event { border-radius: 8px; padding: 2px 6px; font-size: .78rem; font-weight: 600; cursor: pointer; }
            #calendario-citas .fc-daygrid-event-dot { display: none; }
            #calendario-citas .fc-daygrid-block-event .fc-event-main-frame { display: flex; gap: .35rem; min-width: 0; }
            #calendario-citas .fc-event-time { flex-shrink: 0; font-weight: 700; }
            #calendario-citas .fc-event-title { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0; }
            #calendario-citas .fc-scrollgrid { border-radius: 14px; overflow: hidden; }
            @media (max-width: 640px) {
                #calendario-citas .fc-header-toolbar { flex-direction: column; gap: .75rem; align-items: stretch; }
                #calendario-citas .fc-toolbar-title { font-size: 1.4rem; text-align: center; }
                #calendario-citas .fc-toolbar-chunk { display: flex; justify-content: center; }
            }
        </style>
    @endpush

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-4">
        <div class="flex flex-wrap items-center gap-3 text-sm" aria-label="Colores por estado">
            <x-estado-cita estado="pendiente" />
            <x-estado-cita estado="confirmada" />
            <x-estado-cita estado="completada" />
        </div>

        <p id="calendario-aviso" class="alert-error hidden" role="alert"></p>

        <div class="card p-3 sm:p-6">
            <div id="calendario-citas"></div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/locales/es.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const el = document.getElementById('calendario-citas');
                const aviso = document.getElementById('calendario-aviso');
                const diasLaborales = @json($diasLaborales);
                const hoy = new Date(); hoy.setHours(0, 0, 0, 0);
                const avisar = (texto) => { aviso.textContent = texto; aviso.classList.remove('hidden'); clearTimeout(avisar.t); avisar.t = setTimeout(() => aviso.classList.add('hidden'), 5000); };
                const angosto = window.matchMedia('(max-width: 640px)').matches;

                const calendario = new FullCalendar.Calendar(el, {
                    locale: 'es',
                    initialView: angosto ? 'listMonth' : 'dayGridMonth',
                    headerToolbar: angosto
                        ? { left: 'prev,next', center: 'title', right: 'today' }
                        : { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listMonth' },
                    buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', list: 'Lista' },
                    noEventsContent: 'No hay citas en este mes.',
                    titleFormat: { month: 'long', year: 'numeric' },
                    eventDisplay: 'block',
                    dayMaxEvents: 3,
                    moreLinkContent: (arg) => `${arg.num} más`,
                    height: 'auto',
                    navLinks: true,
                    hiddenDays: @json($diasOcultos),
                    businessHours: @json($horarioNegocio),
                    slotMinTime: '07:00:00',
                    slotMaxTime: '20:00:00',
                    eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
                    events: {
                        url: @json(route('citas.eventos')),
                        failure: () => avisar('No pudimos cargar las citas. Revisa tu conexión y recarga la página.'),
                    },
                    dateClick(info) {
                        const dia = info.date.getDay() === 0 ? 7 : info.date.getDay();
                        if (info.date < hoy) { avisar('Esa fecha ya pasó. Elige un día de hoy en adelante.'); return; }
                        if (!diasLaborales.includes(dia)) { avisar('El salón no atiende ese día.'); return; }
                        window.location.href = @json(route('citas.create')) + '?fecha=' + info.dateStr.substring(0, 10);
                    },
                    eventClick(info) {
                        info.jsEvent.preventDefault();
                        if (info.event.url) window.location.href = info.event.url;
                    },
                });
                calendario.render();
            });
        </script>
    @endpush
</x-app-layout>
