<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-slate-900 italic">Reportes y Estadísticas</h1>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-8 bg-white p-6 rounded-3xl border flex flex-wrap gap-4 items-end">
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400">Desde</label>
                    <input type="date" name="desde" value="{{ $desde }}" class="mt-1 rounded-xl border-slate-200 block">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400">Hasta</label>
                    <input type="date" name="hasta" value="{{ $hasta }}" class="mt-1 rounded-xl border-slate-200 block">
                </div>
                <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase">Actualizar</button>
                <a href="{{ route('reportes.csv', request()->query()) }}" class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase">Exportar CSV</a>
                <a href="{{ route('reportes.pdf', request()->query()) }}" class="bg-red-600 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase">Exportar PDF</a>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-8 rounded-3xl border text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase">Citas hoy</p>
                    <p class="text-4xl font-black text-indigo-600">{{ $citasHoy }}</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase">Ingresos período</p>
                    <p class="text-4xl font-black text-emerald-600">${{ number_format($ingresosPeriodo, 0) }}</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase">Clientes únicos</p>
                    <p class="text-4xl font-black text-slate-900">{{ $clientesUnicos }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-3xl border">
                    <h2 class="font-black text-lg mb-4">Ingresos por servicio</h2>
                    <canvas id="chartIngresos" height="200"></canvas>
                </div>
                <div class="bg-white p-8 rounded-3xl border">
                    <h2 class="font-black text-lg mb-4">Citas por estado</h2>
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
                    backgroundColor: 'rgba(79, 70, 229, 0.7)'
                }]
            }
        });
        new Chart(document.getElementById('chartEstados'), {
            type: 'doughnut',
            data: {
                labels: @json($citasPorEstado->keys()),
                datasets: [{
                    data: @json($citasPorEstado->values()),
                    backgroundColor: ['#6366f1','#10b981','#f59e0b','#ef4444']
                }]
            }
        });
    </script>
</x-app-layout>
