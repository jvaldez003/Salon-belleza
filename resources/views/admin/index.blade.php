<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-warm-900 leading-tight">
            {{ __('Estadísticas del Sistema') }}
        </h1>
        <p class="text-warm-500 text-sm mt-1">Métricas clave y distribución de usuarios por rol.</p>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-[2rem] border border-warm-200 shadow-sm text-center">
                    <span class="text-[10px] font-black text-rose-dark uppercase tracking-widest mb-2 block">Citas Hoy</span>
                    <p class="text-4xl font-black text-rose-dark">{{ $citasHoy }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-warm-200 shadow-sm text-center">
                    <span class="text-[10px] font-black text-gold-dark uppercase tracking-widest mb-2 block">Ingresos del Mes</span>
                    <p class="text-3xl font-black text-gold-dark">${{ number_format($ingresosMes, 0) }}</p>
                </div>
                <div class="md:col-span-2 flex gap-4">
                    <a href="{{ route('citas.agenda') }}" class="flex-1 bg-warm-900 text-white rounded-2xl flex items-center justify-center font-black text-xs uppercase tracking-widest hover:bg-warm-800 transition">Agenda del día</a>
                    <a href="{{ route('reportes.index') }}" class="flex-1 bg-rose-primary text-white rounded-2xl flex items-center justify-center font-black text-xs uppercase tracking-widest hover:bg-rose-dark transition">Reportes</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-12">
                <div class="bg-white p-6 rounded-[2rem] border border-warm-200 shadow-sm flex flex-col items-center text-center">
                    <span class="text-[10px] font-black text-warm-400 uppercase tracking-widest mb-2">Total Usuarios</span>
                    <p class="text-4xl font-black text-warm-900">{{ $totalUsuarios }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-warm-200 shadow-sm flex flex-col items-center text-center">
                    <span class="text-[10px] font-black text-rose-dark uppercase tracking-widest mb-2">Total Servicios</span>
                    <p class="text-4xl font-black text-rose-dark">{{ $totalServicios }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-warm-200 shadow-sm flex flex-col items-center text-center">
                    <span class="text-[10px] font-black text-warm-500 uppercase tracking-widest mb-2">Administradores</span>
                    <p class="text-4xl font-black text-warm-700">{{ $totalAdmins }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-warm-200 shadow-sm flex flex-col items-center text-center">
                    <span class="text-[10px] font-black text-gold-dark uppercase tracking-widest mb-2">Editores</span>
                    <p class="text-4xl font-black text-gold-dark">{{ $totalEditores }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-warm-200 shadow-sm flex flex-col items-center text-center">
                    <span class="text-[10px] font-black text-warm-400 uppercase tracking-widest mb-2">Usuarios Reg.</span>
                    <p class="text-4xl font-black text-warm-900">{{ $totalUsuariosReg }}</p>
                </div>
            </div>

            <!-- Distribución -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-warm-200 overflow-hidden max-w-4xl mx-auto">
                <div class="px-10 py-8 flex items-center justify-between"
                     style="background: var(--gradient-main);">
                    <h2 class="text-xl font-black text-white">Distribución por Rol</h2>
                    <svg class="w-6 h-6 text-rose-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <div class="overflow-x-auto p-2">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-cream">
                                <th class="px-10 py-5 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em]">Métrica / Rol</th>
                                <th class="px-10 py-5 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em]">Cantidad</th>
                                <th class="px-10 py-5 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em] text-right">Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-warm-100">
                            @foreach ($distribucion as $rol => $cantidad)
                                <tr class="group hover:bg-cream transition-colors">
                                    <td class="px-10 py-6 font-bold text-warm-900">{{ $rol }}</td>
                                    <td class="px-10 py-6 font-black text-warm-900">{{ $cantidad }}</td>
                                    <td class="px-10 py-6 text-right">
                                        <div class="flex items-center justify-end space-x-3">
                                            <div class="w-32 h-2 bg-warm-100 rounded-full overflow-hidden hidden sm:block">
                                                <div class="h-full bg-rose-primary rounded-full" style="width: {{ $totalUsuarios > 0 ? ($cantidad / $totalUsuarios) * 100 : 0 }}%"></div>
                                            </div>
                                            <span class="text-sm font-black text-rose-dark">
                                                {{ $totalUsuarios > 0 ? number_format(($cantidad / $totalUsuarios) * 100, 1) : 0 }}%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-12 text-center">
                <p class="text-warm-400 text-xs font-bold uppercase tracking-widest">
                    Generado automáticamente · {{ now()->format('d/m/Y H:i:s') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
