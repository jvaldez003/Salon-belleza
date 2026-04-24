<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-slate-900 leading-tight italic">
                    {{ __('Catálogo de Servicios') }}
                </h1>
                <p class="text-slate-500 text-sm mt-1">Explora nuestra gama de tratamientos de belleza premium.</p>
            </div>
            @admin
                <a href="{{ route('servicios.create') }}" class="w-full md:w-auto bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-8 rounded-2xl transition shadow-lg flex items-center justify-center group">
                    <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                    Nuevo Servicio
                </a>
            @endadmin
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl shadow-sm flex items-center animate-fade-in" role="alert">
                    <svg class="w-6 h-6 mr-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Servicio</th>
                                <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Precio (COP)</th>
                                @admin
                                <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                                @endadmin
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($servicios as $servicio)
                                <tr class="group hover:bg-slate-50/50 transition-colors">
                                    <td class="px-10 py-6">
                                        <div class="flex items-center space-x-6">
                                            <div class="w-16 h-16 bg-slate-100 rounded-2xl overflow-hidden shadow-inner flex-shrink-0 group-hover:scale-110 transition-transform duration-500">
                                                @if($servicio->imagenes->count() > 0)
                                                    <img src="{{ asset('storage/' . $servicio->imagenes->first()->url) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-slate-900 font-bold text-lg leading-tight">{{ $servicio->nombre }}</p>
                                                <p class="text-xs text-slate-400 font-medium line-clamp-1 mt-1 max-w-xs">{{ $servicio->descripcion ?? 'Tratamiento beauty de alta gama.' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <span class="text-2xl font-black text-indigo-600 tracking-tighter italic">
                                            ${{ number_format($servicio->precio, 0) }}
                                        </span>
                                    </td>
                                    @admin
                                    <td class="px-10 py-6 text-right">
                                        <div class="flex justify-end space-x-2" x-data="{ openDelete: false }">
                                            <a href="{{ route('servicios.edit', $servicio->id) }}" 
                                               class="p-3 bg-indigo-50 text-indigo-400 hover:bg-indigo-600 hover:text-white rounded-xl transition-all"
                                               title="Editar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </a>
                                            
                                            <button @click="openDelete = true" 
                                                    class="p-3 bg-red-50 text-red-400 hover:bg-red-600 hover:text-white rounded-xl transition-all"
                                                    title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>

                                            <!-- Modal de Deletions -->
                                            <div x-show="openDelete" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition>
                                                <div class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center" @click.away="openDelete = false">
                                                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                    </div>
                                                    <h3 class="text-2xl font-black text-slate-900 mb-2 italic">¿Eliminar servicio?</h3>
                                                    <p class="text-slate-500 mb-8 text-sm leading-relaxed">Borrarás <b>{{ $servicio->nombre }}</b> del catálogo permanentemente.</p>
                                                    <div class="flex flex-col space-y-3">
                                                        <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="w-full bg-red-600 text-white font-bold py-4 rounded-2xl shadow-lg transition-all active:scale-95">Sí, eliminar ahora</button>
                                                        </form>
                                                        <button @click="openDelete = false" class="w-full bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl transition-all active:scale-95">Cancelar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @endadmin
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($servicios->isEmpty())
                <div class="bg-white p-20 text-center rounded-[3rem] shadow-sm border border-slate-100">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 mb-2">Catálogo Vacío</h3>
                    <p class="text-slate-500 mb-8">No hay servicios registrados en este momento.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>