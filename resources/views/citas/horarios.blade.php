<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-slate-900 italic">Horarios del Salón</h1>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100">
                <p class="text-slate-500 mb-6">Configuración actual de disponibilidad (archivo <code class="bg-slate-100 px-2 py-1 rounded">config/salon.php</code>).</p>
                <ul class="space-y-3 font-bold text-slate-800">
                    <li>Apertura: {{ $config['hora_apertura'] }}</li>
                    <li>Cierre: {{ $config['hora_cierre'] }}</li>
                    <li>Intervalo entre citas: {{ $config['intervalo_minutos'] }} min</li>
                    <li>Días laborales: Lun–Sáb</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
