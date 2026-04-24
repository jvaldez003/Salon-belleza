<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-slate-900 leading-tight">
                    {{ __('Gestión de Banners') }}
                </h1>
                <p class="text-slate-500 text-sm mt-1">Administra el anuncio principal de tu página de inicio.</p>
            </div>
            <a href="{{ route('banners.create') }}" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-2xl transition shadow-lg shadow-indigo-200 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                Nuevo Banner
            </a>
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

            <div class="grid grid-cols-1 gap-8">
                @foreach($banners as $banner)
                    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-slate-100 flex flex-col md:flex-row group transition-all duration-500 hover:shadow-2xl">
                        <!-- Preview -->
                        <div class="md:w-1/3 h-64 md:h-auto relative overflow-hidden">
                            <img src="{{ asset('storage/' . $banner->imagen_url) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @if($banner->activo)
                                <div class="absolute top-4 left-4 bg-emerald-500 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">Activo</div>
                            @else
                                <div class="absolute top-4 left-4 bg-slate-400 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">Inactivo</div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-10 flex-1 flex flex-col justify-center">
                            <h3 class="text-3xl font-black text-slate-900 mb-2">{{ $banner->titulo }}</h3>
                            <p class="text-slate-500 mb-6 leading-relaxed line-clamp-2">{{ $banner->subtitulo }}</p>
                            
                            <div class="flex flex-wrap gap-4 items-center">
                                @if($banner->texto_boton)
                                    <span class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl text-sm font-bold border border-indigo-100">
                                        Botón: {{ $banner->texto_boton }}
                                    </span>
                                @endif
                                
                                <div class="ml-auto flex space-x-2" x-data="{ open: false }">
                                    <a href="{{ route('banners.edit', $banner->id) }}" 
                                       class="p-4 bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-600 rounded-2xl transition-all"
                                       title="Editar">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </a>
                                    
                                    <button @click="open = true" 
                                            class="p-4 bg-red-50 hover:bg-red-600 hover:text-white text-red-500 rounded-2xl transition-all"
                                            title="Eliminar">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>

                                    <!-- Modal Deletion -->
                                    <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition>
                                        <div class="bg-white rounded-[2rem] p-10 max-w-sm w-full shadow-2xl text-center" @click.away="open = false">
                                            <h3 class="text-2xl font-black text-slate-900 mb-4">¿Borrar banner?</h3>
                                            <p class="text-slate-500 mb-8 text-sm">Esta acción no se puede deshacer.</p>
                                            <div class="flex flex-col space-y-3">
                                                <form action="{{ route('banners.destroy', $banner->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="w-full bg-red-600 text-white font-bold py-4 rounded-2xl">Eliminar ahora</button>
                                                </form>
                                                <button @click="open = false" class="w-full bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl">Cancelar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($banners->isEmpty())
                <div class="bg-white p-20 text-center rounded-[3rem] shadow-sm border border-slate-100">
                    <h3 class="text-2xl font-black text-slate-800 mb-2">No hay banners</h3>
                    <p class="text-slate-500 mb-8 text-sm">Crea un banner innovador para tu página de inicio.</p>
                    <a href="{{ route('banners.create') }}" class="inline-flex items-center bg-indigo-600 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-indigo-100">
                        Crear mi primer banner
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
