<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="font-black text-xl text-slate-900">Selecciona la fecha en el calendario</h2>
            <p class="text-sm text-slate-400 mt-1">Domingos cerrados · Lun–Sáb {{ config('salon.hora_apertura') }}–{{ config('salon.hora_cierre') }}</p>
        </div>
    </div>
    <div id="calendario-reserva" class="text-sm"></div>
</div>

@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
        <style>
            #calendario-reserva { --fc-border-color: #e2e8f0; --fc-today-bg-color: #eef2ff; }
            #calendario-reserva .fc-toolbar-title { font-size: 1rem; font-weight: 900; }
            #calendario-reserva .fc-button { font-size: 10px; font-weight: 700; text-transform: uppercase; }
        </style>
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/locales/es.global.min.js"></script>
    @endpush
@endonce

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('calendario-reserva');
    if (!el || el.dataset.initialized) return;
    el.dataset.initialized = '1';

    const diasLaborales = @json(config('salon.dias_laborales', [1, 2, 3, 4, 5, 6]));
    let fechaSeleccionada = @json($fechaInicial ?? now()->format('Y-m-d'));

    const cal = new FullCalendar.Calendar(el, {
        locale: 'es',
        initialView: 'dayGridMonth',
        height: 380,
        headerToolbar: { left: 'prev,next', center: 'title', right: 'today' },
        hiddenDays: [0],
        selectable: true,
        dateClick(info) {
            const isoDay = info.date.getDay() === 0 ? 7 : info.date.getDay();
            if (!diasLaborales.includes(isoDay)) {
                alert('El salón no atiende los domingos.');
                return;
            }
            fechaSeleccionada = info.dateStr.substring(0, 10);
            window.dispatchEvent(new CustomEvent('fecha-cita-seleccionada', { detail: fechaSeleccionada }));
        },
        dayCellClassNames(arg) {
            const isoDay = arg.date.getDay() === 0 ? 7 : arg.date.getDay();
            if (!diasLaborales.includes(isoDay)) return ['opacity-40'];
            const ymd = arg.date.toISOString().substring(0, 10);
            if (ymd === fechaSeleccionada) return ['!bg-indigo-100', 'ring-2', 'ring-indigo-400', 'ring-inset'];
            return [];
        },
    });

    cal.render();
    cal.gotoDate(fechaSeleccionada);

    window.addEventListener('fecha-cita-seleccionada', (e) => {
        fechaSeleccionada = e.detail;
        cal.render();
    });
});
</script>
@endpush
