<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-slate-900 leading-tight">
                    {{ __('Gestión de Servicios') }}
                </h1>
                <p class="text-slate-500 text-sm mt-1">Administra el catálogo de belleza de tu salón.</p>
            </div>
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'editor')
                <a href="{{ route('servicios.create') }}" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-2xl transition shadow-lg shadow-indigo-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                    Nuevo Servicio
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl shadow-sm flex items-center" role="alert">
                    <svg class="w-6 h-6 mr-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($servicios as $servicio)
                    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-2xl transition-all duration-500 group flex flex-col h-full">
                        <!-- Preview Image/Carousel -->
                        <div class="relative h-56 bg-slate-100 overflow-hidden">
                            @if($servicio->imagenes->count() > 0)
                                <img src="{{ asset('storage/' . $servicio->imagenes->first()->url) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-black text-slate-900 uppercase tracking-widest shadow-sm">
                                    {{ $servicio->imagenes->count() }} Fotos
                                </div>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                    <svg class="w-12 h-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span class="text-xs font-bold uppercase tracking-tighter">Sin imágenes</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-8 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-2xl font-black text-slate-900 line-clamp-1">{{ $servicio->nombre }}</h3>
                            </div>
                            
                            <p class="text-slate-500 text-sm mb-6 line-clamp-2 leading-relaxed">
                                {{ $servicio->descripcion ?? 'Un servicio de alta calidad diseñado para resaltar tu belleza natural con los mejores productos.' }}
                            </p>

                            <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Inversión</span>
                                    <span class="text-3xl font-black text-indigo-600">${{ number_format($servicio->precio, 0) }}</span>
                                </div>

                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'editor')
                                    <div class="flex space-x-2" x-data="{ open: false }">
                                        <a href="{{ route('servicios.edit', $servicio->id) }}" 
                                           class="p-3 bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-600 rounded-2xl transition-all"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>
                                        
                                        <button @click="open = true" 
                                                class="p-3 bg-red-50 hover:bg-red-600 hover:text-white text-red-500 rounded-2xl transition-all"
                                                title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>

                                        <!-- Modal de Confirmación Moderno -->
                                        <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition>
                                            <div class="bg-white rounded-[2rem] p-10 max-w-sm w-full shadow-2xl text-center" @click.away="open = false">
                                                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                </div>
                                                <h3 class="text-2xl font-black text-slate-900 mb-4">¿Borrar servicio?</h3>
                                                <p class="text-slate-500 mb-8 text-sm leading-relaxed">Estás a punto de eliminar "{{ $servicio->nombre }}". Esta acción es permanente.</p>
                                                <div class="flex flex-col space-y-3">
                                                    <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="w-full bg-red-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-red-200">Eliminar ahora</button>
                                                    </form>
                                                    <button @click="open = false" class="w-full bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl">Mantener servicio</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($servicios->isEmpty())
                <div class="bg-white p-20 text-center rounded-[3rem] shadow-sm border border-slate-100">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 mb-2">No hay servicios</h3>
                    <p class="text-slate-500 mb-8">Comienza creando tu primer servicio de belleza.</p>
                    <a href="{{ route('servicios.create') }}" class="inline-flex items-center bg-indigo-600 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-indigo-100 transition-transform active:scale-95">
                        Crear mi primer servicio
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>