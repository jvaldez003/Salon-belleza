<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('banners.index') }}" class="p-2 bg-white rounded-xl shadow-sm text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <h1 class="font-black text-2xl text-slate-900 leading-tight">
                {{ __('Editar Banner') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-2">
                        <label for="titulo" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Título del Banner</label>
                        <input type="text" name="titulo" id="titulo" value="{{ $banner->titulo }}"
                               class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800 text-xl" required>
                    </div>

                    <div class="space-y-2">
                        <label for="subtitulo" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Subtítulo o Descripción corta</label>
                        <textarea name="subtitulo" id="subtitulo" rows="3"
                                  class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all text-slate-700 leading-relaxed">{{ $banner->subtitulo }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="texto_boton" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Texto del Botón</label>
                            <input type="text" name="texto_boton" id="texto_boton" value="{{ $banner->texto_boton }}"
                                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
                        </div>

                        <div class="space-y-2">
                            <label for="link_boton" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Link del Botón (URL)</label>
                            <input type="text" name="link_boton" id="link_boton" value="{{ $banner->link_boton }}"
                                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Imagen Actual</label>
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $banner->imagen_url) }}" class="w-full h-48 object-cover rounded-2xl border border-slate-100">
                        </div>
                        <label for="imagen" class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Cambiar Imagen (Opcional)</label>
                        <div class="relative group">
                            <input type="file" name="imagen" id="imagen" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl py-12 flex flex-col items-center justify-center group-hover:bg-indigo-50 group-hover:border-indigo-200 transition-all">
                                <span class="text-sm font-bold text-slate-500">Seleccionar nueva imagen</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 ml-1">
                        <input type="checkbox" name="activo" id="activo" {{ $banner->activo ? 'checked' : '' }} class="w-6 h-6 text-indigo-600 border-slate-200 rounded-lg focus:ring-indigo-500">
                        <label for="activo" class="text-sm font-bold text-slate-700 uppercase tracking-wider">Banner Activo</label>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-indigo-100 transition-all transform active:scale-95 flex items-center justify-center">
                            Actualizar Banner
                        </button>
                        <a href="{{ route('banners.index') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 text-center font-black py-4 rounded-2xl transition-all">
                            Descartar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
