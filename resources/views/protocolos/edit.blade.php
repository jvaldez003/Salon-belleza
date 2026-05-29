<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('protocolos.index') }}" class="p-2 bg-white rounded-xl border border-warm-200 text-warm-400 hover:text-warm-900 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="font-black text-3xl text-warm-900">{{ $protocolo->nombre }}</h1>
                <p class="text-warm-400 text-sm">Editar protocolo · gestionar medios</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="alert-success">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            @endif

            {{-- ── Datos básicos ── --}}
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-warm-200 space-y-5">
                <h2 class="font-black text-lg text-warm-900">Información del protocolo</h2>

                <form method="POST" action="{{ route('protocolos.update', $protocolo) }}" class="space-y-5">
                    @csrf @method('PUT')

                    <div class="space-y-2">
                        <label class="label">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $protocolo->nombre) }}" required maxlength="120"
                               class="w-full bg-cream border-0 rounded-2xl py-3 px-5 font-bold text-warm-800 focus:ring-2 focus:ring-rose-primary">
                        @error('nombre')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2">
                        <label class="label">Descripción</label>
                        <textarea name="descripcion" rows="4" maxlength="2000"
                                  class="w-full bg-cream border-0 rounded-2xl py-4 px-5 text-sm text-warm-700 leading-relaxed focus:ring-2 focus:ring-rose-primary">{{ old('descripcion', $protocolo->descripcion) }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" name="activo" id="activo" value="1"
                               {{ $protocolo->activo ? 'checked' : '' }}
                               class="w-5 h-5 rounded-lg accent-rose-primary">
                        <label for="activo" class="text-sm font-bold text-warm-700">Visible en la página principal</label>
                    </div>

                    <button type="submit"
                            class="w-full bg-rose-primary hover:bg-rose-dark text-white font-black py-4 rounded-2xl transition active:scale-95">
                        Guardar cambios
                    </button>
                </form>
            </div>

            {{-- ── Galería de medios ── --}}
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-warm-200 space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-black text-lg text-warm-900">Imágenes y Videos</h2>
                    <span class="text-xs font-black uppercase tracking-widest px-3 py-1 bg-rose-light/20 text-rose-deeper rounded-full">
                        {{ $protocolo->medios->count() }} medio(s)
                    </span>
                </div>

                {{-- Grid de medios existentes --}}
                @if($protocolo->medios->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($protocolo->medios as $medio)
                    <div class="relative group rounded-2xl overflow-hidden border border-warm-200 bg-cream">
                        @if($medio->tipo === 'imagen')
                            <img src="{{ asset('storage/'.$medio->url) }}"
                                 class="w-full h-auto block min-h-[100px] object-contain bg-cream">
                        @else
                            @if($medio->esUrlExterna())
                            <div class="aspect-video bg-warm-900 flex items-center justify-center">
                                <svg class="w-10 h-10 text-white/60" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 7a2.59 2.59 0 00-1.83-.76H6.24A2.59 2.59 0 003.65 7a2.59 2.59 0 00-.76 1.83v6.34a2.59 2.59 0 00.76 1.83 2.59 2.59 0 001.83.76h11.52a2.59 2.59 0 001.83-.76 2.59 2.59 0 00.76-1.83V8.83A2.59 2.59 0 0019.59 7zm-7.09 6.74L9.5 15.08V8.92l3 1.34 3 1.33-3 2.15z"/></svg>
                                <span class="text-white/60 text-xs font-bold ml-2">YouTube</span>
                            </div>
                            @else
                            <video class="w-full h-auto block" preload="metadata">
                                <source src="{{ asset('storage/'.$medio->url) }}" type="video/mp4">
                            </video>
                            @endif
                        @endif

                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all flex items-center justify-center">
                            <form method="POST" action="{{ route('protocolos.medios.destroy', $medio) }}"
                                  class="opacity-0 group-hover:opacity-100 transition"
                                  onsubmit="return confirm('¿Eliminar este medio?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition active:scale-95">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>

                        <span class="absolute top-2 left-2 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full
                            {{ $medio->tipo === 'imagen' ? 'bg-[#EBF5FC] text-[#0E4A9E]' : 'bg-warm-900/80 text-white' }}">
                            {{ $medio->tipo }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Agregar imagen --}}
                <div class="border-t border-warm-100 pt-6">
                    <p class="label mb-3">Agregar imagen</p>
                    <form method="POST" action="{{ route('protocolos.medios.store', $protocolo) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tipo" value="imagen">
                        <div class="relative group">
                            <input type="file" name="archivo" accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                   onchange="this.form.submit()">
                            <div class="w-full bg-cream border-2 border-dashed border-warm-300 rounded-2xl py-5 flex items-center justify-center gap-2 group-hover:border-rose-primary transition">
                                <svg class="w-5 h-5 text-warm-300 group-hover:text-rose-primary transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-bold text-warm-400 group-hover:text-rose-primary transition">Subir imagen (jpg, png, webp)</span>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Agregar video --}}
                <div class="border-t border-warm-100 pt-6 space-y-4" x-data="{ modo: 'url' }">
                    <div class="flex items-center gap-4">
                        <p class="label">Agregar video</p>
                        <div class="flex bg-cream rounded-xl p-0.5 gap-0.5">
                            <button type="button" @click="modo = 'url'"
                                    :class="modo === 'url' ? 'bg-white shadow-sm text-warm-900' : 'text-warm-400'"
                                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition">URL YouTube</button>
                            <button type="button" @click="modo = 'archivo'"
                                    :class="modo === 'archivo' ? 'bg-white shadow-sm text-warm-900' : 'text-warm-400'"
                                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition">Archivo MP4</button>
                        </div>
                    </div>

                    {{-- YouTube URL --}}
                    <form x-show="modo === 'url'" method="POST" action="{{ route('protocolos.medios.store', $protocolo) }}" class="flex gap-3">
                        @csrf
                        <input type="hidden" name="tipo" value="video">
                        <input type="url" name="video_url" placeholder="https://www.youtube.com/watch?v=..."
                               class="flex-1 bg-cream border-0 rounded-2xl py-3 px-5 text-sm font-bold text-warm-700 focus:ring-2 focus:ring-rose-primary"
                               required>
                        <button type="submit"
                                class="bg-rose-primary hover:bg-rose-dark text-white font-black px-6 py-3 rounded-2xl transition active:scale-95 shrink-0 text-sm">
                            Agregar
                        </button>
                    </form>

                    {{-- Archivo de video --}}
                    <form x-show="modo === 'archivo'" method="POST" action="{{ route('protocolos.medios.store', $protocolo) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tipo" value="video">
                        <div class="relative group">
                            <input type="file" name="archivo" accept="video/mp4,video/mov,video/webm"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                   onchange="this.form.submit()">
                            <div class="w-full bg-cream border-2 border-dashed border-warm-300 rounded-2xl py-5 flex items-center justify-center gap-2 group-hover:border-rose-primary transition">
                                <svg class="w-5 h-5 text-warm-300 group-hover:text-rose-primary transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-bold text-warm-400 group-hover:text-rose-primary transition">Subir video (mp4, mov, webm · máx. 50 MB)</span>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
