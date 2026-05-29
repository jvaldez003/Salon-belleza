<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-warm-900">Reportes y Estadísticas</h1>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-8 bg-white p-6 rounded-3xl border border-warm-200 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="label">Desde</label>
                    <input type="date" name="desde" value="{{ $desde }}" class="mt-1 rounded-xl border-warm-200 bg-cream focus:ring-rose-primary block">
                </div>
                <div>
                    <label class="label">Hasta</label>
                    <input type="date" name="hasta" value="{{ $hasta }}" class="mt-1 rounded-xl border-warm-200 bg-cream focus:ring-rose-primary block">
                </div>
                <button type="submit" class="bg-warm-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase hover:bg-warm-800 transition">Actualizar</button>
                <a href="{{ route('reportes.csv', request()->query()) }}" class="bg-gold-primary text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase hover:bg-gold-dark transition">Exportar CSV</a>
                <a href="{{ route('reportes.pdf', request()->query()) }}" class="bg-rose-primary text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase hover:bg-rose-dark transition">Exportar PDF</a>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-8 rounded-3xl border border-warm-200 text-center">
                    <p class="text-[10px] font-black text-warm-400 uppercase">Citas hoy</p>
                    <p class="text-4xl font-black text-rose-dark">{{ $citasHoy }}</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-warm-200 text-center">
                    <p class="text-[10px] font-black text-warm-400 uppercase">Ingresos período</p>
                    <p class="text-4xl font-black text-gold-dark">${{ number_format($ingresosPeriodo, 0) }}</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-warm-200 text-center">
                    <p class="text-[10px] font-black text-warm-400 uppercase">Clientes únicos</p>
                    <p class="text-4xl font-black text-warm-900">{{ $clientesUnicos }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-3xl border border-warm-200">
                    <h2 class="font-black text-lg text-warm-900 mb-4">Ingresos por servicio</h2>
                    <canvas id="chartIngresos" height="200"></canvas>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-warm-200">
                    <h2 class="font-black text-lg text-warm-900 mb-4">Citas por estado</h2>
                    <canvas id="chartEstados" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('chartIngresos'), {
            type: 'bar',
            data: {
                labels: @json($ingresosPorServicio->pluck('nombre')),
                datasets: [{
                    label: 'Ingresos ($)',
                    data: @json($ingresosPorServicio->pluck('ingresos')),
                    backgroundColor: 'rgba(200, 164, 109, 0.7)',
                    borderColor: '#A8845A',
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { grid: { color: '#EDE5DF' } }, x: { grid: { display: false } } }
            }
        });
        new Chart(document.getElementById('chartEstados'), {
            type: 'doughnut',
            data: {
                labels: @json($citasPorEstado->keys()),
                datasets: [{
                    data: @json($citasPorEstado->values()),
                    backgroundColor: ['#D8A7B1','#C8A46D','#E7C6CC','#EDE5DF'],
                    borderWidth: 0,
                }]
            },
            options: { plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
        });
    </script>
</x-app-layout>
