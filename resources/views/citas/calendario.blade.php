<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-warm-900 italic">Calendario de Citas</h1>
                <p class="text-warm-500 text-sm mt-1">Visualiza tus citas y reserva haciendo clic en un día disponible.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                    <a href="{{ route('citas.agenda') }}" class="bg-cream text-warm-800 font-bold py-3 px-6 rounded-2xl text-xs uppercase tracking-widest hover:bg-warm-200 transition">Agenda</a>
                @endif
                <a href="{{ route('citas.create') }}" class="text-white font-bold py-3 px-8 rounded-2xl text-xs uppercase tracking-widest transition shadow-lg" style="background:linear-gradient(135deg,#0A2F6B,#008FE8)">Nueva Cita</a>
            </div>
        </div>
    </x-slot>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
        <style>
            #calendario-citas { --fc-border-color: #D9EAF2; --fc-today-bg-color: #EBF5FC; }
            #calendario-citas .fc-toolbar-title { font-weight: 900; font-style: italic; color: #0A2F6B; }
            #calendario-citas .fc-button-primary { background: #0A2F6B; border-color: #0A2F6B; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: .1em; }
            #calendario-citas .fc-button-primary:not(:disabled).fc-button-active,
            #calendario-citas .fc-button-primary:not(:disabled):active { background: #0E4A9E; border-color: #0E4A9E; }
            #calendario-citas .fc-event { border-radius: 8px; padding: 2px 4px; font-size: 11px; font-weight: 700; cursor: pointer; }
        </style>
    @endpush

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-warm-200 shadow-sm mb-6">
                <div class="flex flex-wrap gap-4 text-xs font-black uppercase tracking-widest text-warm-500 mb-6">
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-500"></span> Pendiente</span>
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full" style="background:#0A2F6B"></span> Confirmada</span>
                    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Completada</span>
                </div>
                <div id="calendario-citas"></div>
            </div>
            <p class="text-center text-sm text-warm-400 font-medium">
                Haz clic en un día libre para reservar · Haz clic en una cita para ver detalles
            </p>
        </div>
    </div>

    @php
        $horariosActivos = \App\Models\Horario::where('activo', true)->orderBy('dia')->get();
        $diasLaboralesJs = $horariosActivos->pluck('dia')->toArray();
        $businessHours = $horariosActivos->map(fn($h) => [
            'daysOfWeek' => [$h->dia === 7 ? 0 : $h->dia],
            'startTime'  => substr($h->hora_apertura, 0, 5),
            'endTime'    => substr($h->hora_cierre, 0, 5),
        ])->values()->toArray();
    @endphp

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/locales/es.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const calendarioEl = document.getElementById('calendario-citas');
                const diasLaborales = @json($diasLaboralesJs);

                const calendario = new FullCalendar.Calendar(calendarioEl, {
                    locale: 'es',
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    height: 'auto',
                    navLinks: true,
                    editable: false,
                    selectable: true,
                    businessHours: @json($businessHours),
                    hiddenDays: [0],
                    events: {
                        url: '{{ route('citas.eventos') }}',
                        failure: () => alert('No se pudieron cargar las citas del calendario.'),
                    },
                    dateClick: function (info) {
                        const dia = info.date.getDay();
                        const isoDay = dia === 0 ? 7 : dia;
                        if (!diasLaborales.includes(isoDay)) {
                            alert('El salón no atiende los domingos.');
                            return;
                        }
                        const fecha = info.dateStr.substring(0, 10);
                        window.location.href = `{{ route('citas.create') }}?fecha=${fecha}`;
                    },
                    eventClick: function (info) {
                        info.jsEvent.preventDefault();
                        if (info.event.url) {
                            window.location.href = info.event.url;
                        }
                    },
                });

                calendario.render();
            });
        </script>
    @endpush
</x-app-layout>
