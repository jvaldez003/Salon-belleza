<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-warm-900 leading-tight">Empleados</h1>
                <p class="text-warm-400 text-sm mt-1">Gestiona el equipo y los servicios que puede realizar cada uno.</p>
            </div>
            <a href="{{ route('empleados.create') }}"
               class="w-full md:w-auto bg-rose-primary hover:bg-rose-dark text-white font-bold py-3 px-8 rounded-2xl transition shadow-sm flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                Nuevo Empleado
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 alert-success">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if($empleados->isEmpty())
                <div class="bg-white p-20 text-center rounded-[3rem] shadow-sm border border-warm-200">
                    <div class="w-20 h-20 bg-cream rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-warm-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-warm-800 mb-2">Sin empleados registrados</h3>
                    <p class="text-warm-500 mb-6">Agrega empleados para que los clientes puedan elegir con quién quieren su servicio.</p>
                    <a href="{{ route('empleados.create') }}" class="bg-rose-primary text-white font-bold py-3 px-8 rounded-2xl hover:bg-rose-dark transition">Agregar primer empleado</a>
                </div>
            @else
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-warm-200 overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-cream">
                                <th class="px-8 py-5 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em]">Empleado</th>
                                <th class="px-8 py-5 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em]">Especialidad</th>
                                <th class="px-8 py-5 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em]">Servicios asignados</th>
                                <th class="px-8 py-5 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em]">Estado</th>
                                <th class="px-8 py-5 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-warm-100">
                            @foreach($empleados as $empleado)
                            <tr class="hover:bg-cream transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl overflow-hidden bg-rose-light/30 shrink-0">
                                            @if($empleado->foto)
                                                <img src="{{ asset('storage/'.$empleado->foto) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-rose-dark font-black text-lg">
                                                    {{ mb_strtoupper(mb_substr($empleado->nombre, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="font-bold text-warm-800">{{ $empleado->nombre }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-warm-500">{{ $empleado->especialidad ?? '—' }}</td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($empleado->servicios as $srv)
                                            <span class="text-xs bg-rose-light/20 text-rose-deeper font-bold px-2 py-1 rounded-lg">{{ $srv->nombre }}</span>
                                        @empty
                                            <span class="text-xs text-warm-400">Sin servicios</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-black px-3 py-1 rounded-full {{ $empleado->activo ? 'bg-rose-light/20 text-rose-deeper' : 'bg-warm-100 text-warm-500' }}">
                                        {{ $empleado->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end gap-2" x-data="{ del: false }">
                                        <a href="{{ route('empleados.edit', $empleado) }}"
                                           class="p-2.5 bg-cream text-warm-500 hover:bg-rose-primary hover:text-white rounded-xl transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                        <button @click="del = true"
                                                class="p-2.5 bg-red-50 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                        <div x-show="del" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-warm-900/60 backdrop-blur-sm" x-transition>
                                            <div class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center" @click.away="del = false">
                                                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                </div>
                                                <h3 class="text-xl font-black text-warm-900 mb-2">¿Eliminar empleado?</h3>
                                                <p class="text-warm-500 mb-6 text-sm">Se eliminará <b>{{ $empleado->nombre }}</b> permanentemente.</p>
                                                <div class="flex flex-col gap-3">
                                                    <form action="{{ route('empleados.destroy', $empleado) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button class="w-full bg-red-500 text-white font-bold py-3 rounded-2xl">Sí, eliminar</button>
                                                    </form>
                                                    <button @click="del = false" class="w-full bg-cream text-warm-600 font-bold py-3 rounded-2xl">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
