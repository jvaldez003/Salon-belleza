<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('servicios.index') }}" class="p-2 bg-white rounded-xl shadow-sm text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <h1 class="font-black text-2xl text-slate-900 leading-tight">
                {{ __('Nuevo Servicio') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                <form action="{{ route('servicios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Nombre -->
                        <div class="space-y-2">
                            <label for="nombre" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Nombre del Servicio</label>
                            <input type="text" name="nombre" id="nombre" placeholder="Ej: Corte de Cabello Dama"
                                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800" required>
                            @error('nombre') <p class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Precio -->
                        <div class="space-y-2">
                            <label for="precio" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Precio sugerido ($)</label>
                            <input type="number" step="0.01" name="precio" id="precio" placeholder="0.00"
                                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-indigo-600 text-xl" required>
                            @error('precio') <p class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="space-y-2">
                        <label for="descripcion" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Descripción del Servicio</label>
                        <textarea name="descripcion" id="descripcion" rows="4" placeholder="Describe los beneficios y detalles del servicio..."
                                  class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all text-slate-700 leading-relaxed"></textarea>
                        @error('descripcion') <p class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Images Upload -->
                    <div class="space-y-2">
                        <label for="imagenes" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Galería de Imágenes</label>
                        <div class="relative group">
                            <input type="file" name="imagenes[]" id="imagenes" multiple 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl py-12 flex flex-col items-center justify-center group-hover:bg-indigo-50 group-hover:border-indigo-200 transition-all">
                                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-indigo-500 mb-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-lg font-bold text-slate-700">Selecciona o arrastra imágenes</span>
                                <span class="text-sm text-slate-400 mt-1">Puedes subir varias fotos para el carrusel</span>
                            </div>
                        </div>
                        @error('imagenes.*') <p class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t border-slate-50 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-indigo-100 transition-all transform active:scale-95 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            Crear Servicio
                        </button>
                        <a href="{{ route('servicios.index') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 text-center font-black py-4 rounded-2xl transition-all">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
