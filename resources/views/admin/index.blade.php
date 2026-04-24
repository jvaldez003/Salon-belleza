<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-slate-900 leading-tight">
            {{ __('Estadísticas del Sistema') }}
        </h1>
        <p class="text-slate-500 text-sm mt-1">Métricas clave y distribución de usuarios por rol.</p>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Key Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-12">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center text-center">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Usuarios</span>
                    <p class="text-4xl font-black text-slate-900">{{ $totalUsuarios }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center text-center">
                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Total Servicios</span>
                    <p class="text-4xl font-black text-indigo-600">{{ $totalServicios }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center text-center">
                    <span class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-2">Administradores</span>
                    <p class="text-4xl font-black text-red-500">{{ $totalAdmins }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center text-center">
                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2">Editores</span>
                    <p class="text-4xl font-black text-emerald-500">{{ $totalEditores }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center text-center">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Usuarios Reg.</span>
                    <p class="text-4xl font-black text-slate-900">{{ $totalUsuariosReg }}</p>
                </div>
            </div>

            <!-- Detailed Distribution -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden max-w-4xl mx-auto">
                <div class="px-10 py-8 bg-slate-900 flex items-center justify-between">
                    <h2 class="text-xl font-black text-white italic">Distribución por Rol</h2>
                    <svg class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <div class="overflow-x-auto p-2">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Metrica / Rol</th>
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Cantidad</th>
                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($distribucion as $rol => $cantidad)
                                <tr class="group hover:bg-slate-50/50 transition-colors">
                                    <td class="px-10 py-6 font-bold text-slate-900">{{ $rol }}</td>
                                    <td class="px-10 py-6 font-black text-slate-900">{{ $cantidad }}</td>
                                    <td class="px-10 py-6 text-right">
                                        <div class="flex items-center justify-end space-x-3">
                                            <div class="w-32 h-2 bg-slate-100 rounded-full overflow-hidden hidden sm:block">
                                                <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $totalUsuarios > 0 ? ($cantidad / $totalUsuarios) * 100 : 0 }}%"></div>
                                            </div>
                                            <span class="text-sm font-black text-indigo-600">
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

            <!-- Footer Info -->
            <div class="mt-12 text-center">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest italic">
                    Generado automáticamente • {{ now()->format('d/m/Y H:i:s') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>