<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('servicios.index') }}" class="p-2 bg-white rounded-xl shadow-sm text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <h1 class="font-black text-2xl text-slate-900 leading-tight">
                {{ __('Editar Servicio') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Image Management Section -->
            @if($servicio->imagenes->count() > 0)
                <div class="mb-10 bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                    <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center">
                        <span class="w-2 h-6 bg-red-500 rounded-full mr-3"></span>
                        Galería Actual
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                        @foreach($servicio->imagenes as $imagen)
                            <div class="group relative bg-slate-50 rounded-2xl overflow-hidden border border-slate-100 aspect-square">
                                <img src="{{ asset('storage/' . $imagen->url) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <form action="{{ route('servicios.eliminarImagen', $imagen->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-white text-red-600 font-bold py-2 px-4 rounded-xl shadow-xl hover:bg-red-600 hover:text-white transition-all transform hover:scale-105 active:scale-95"
                                                onclick="return confirm('¿Eliminar esta imagen?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Main Edit Form -->
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                <form action="{{ route('servicios.update', $servicio->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Nombre -->
                        <div class="space-y-2">
                            <label for="nombre" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Nombre del Servicio</label>
                            <input type="text" name="nombre" id="nombre" value="{{ $servicio->nombre }}" 
                                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800" required>
                            @error('nombre') <p class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Precio -->
                        <div class="space-y-2">
                            <label for="precio" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Precio ($)</label>
                            <input type="number" step="0.01" name="precio" id="precio" value="{{ $servicio->precio }}" 
                                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-indigo-600 text-xl" required>
                            @error('precio') <p class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="space-y-2">
                        <label for="descripcion" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Descripción Detallada</label>
                        <textarea name="descripcion" id="descripcion" rows="4" 
                                  class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all text-slate-700 leading-relaxed">{{ $servicio->descripcion }}</textarea>
                        @error('descripcion') <p class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Upload More -->
                    <div class="space-y-2">
                        <label for="imagenes" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Añadir más fotos</label>
                        <div class="relative group">
                            <input type="file" name="imagenes[]" id="imagenes" multiple 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl py-8 flex flex-col items-center justify-center group-hover:bg-indigo-50 group-hover:border-indigo-200 transition-all">
                                <svg class="w-10 h-10 text-slate-300 mb-2 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="text-sm font-bold text-slate-500 group-hover:text-indigo-600">Haz clic para subir archivos</span>
                            </div>
                        </div>
                        @error('imagenes.*') <p class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t border-slate-50 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-indigo-100 transition-all transform active:scale-95">
                            Guardar Cambios
                        </button>
                        <a href="{{ route('servicios.index') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 text-center font-black py-4 rounded-2xl transition-all">
                            Descartar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
