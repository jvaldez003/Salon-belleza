<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-warm-900">Configuración del Sitio</h1>
        <p class="text-warm-400 text-sm mt-1">Logo, nombre, quiénes somos, misión y visión.</p>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="alert-success">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('configuracion.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')

                {{-- IDENTIDAD --}}
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-warm-200 space-y-6">
                    <h2 class="font-black text-lg text-warm-900">Identidad del negocio</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="label">Nombre del negocio</label>
                            <input type="text" name="nombre_negocio" value="{{ old('nombre_negocio', $config->nombre_negocio) }}" required
                                   class="w-full bg-cream border-0 rounded-2xl py-4 px-5 font-black text-warm-900 text-xl focus:ring-2 focus:ring-rose-primary">
                            @error('nombre_negocio')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="label">Logo del negocio</label>
                            @if($config->logo)
                                <div class="flex items-center gap-3 mb-2">
                                    <img src="{{ asset('storage/'.$config->logo) }}" class="h-14 object-contain rounded-xl border border-warm-200 bg-white p-1">
                                    <span class="text-xs text-warm-400">Logo actual</span>
                                </div>
                            @endif
                            <div class="relative group">
                                <input type="file" name="logo" accept="image/*"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full bg-cream border-2 border-dashed border-warm-300 rounded-2xl py-6 flex flex-col items-center gap-1 group-hover:border-rose-primary transition-all">
                                    <svg class="w-6 h-6 text-warm-300 group-hover:text-rose-primary transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs font-bold text-warm-400">{{ $config->logo ? 'Cambiar logo' : 'Subir logo' }}</span>
                                </div>
                            </div>
                            @error('logo')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="label">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono', $config->telefono) }}"
                                   class="w-full bg-cream border-0 rounded-2xl py-3 px-4 text-sm font-bold text-warm-700 focus:ring-2 focus:ring-rose-primary"
                                   placeholder="+57 300 000 0000">
                        </div>
                        <div class="space-y-2">
                            <label class="label">Email contacto</label>
                            <input type="email" name="email_contacto" value="{{ old('email_contacto', $config->email_contacto) }}"
                                   class="w-full bg-cream border-0 rounded-2xl py-3 px-4 text-sm font-bold text-warm-700 focus:ring-2 focus:ring-rose-primary"
                                   placeholder="contacto@arrecife.com">
                        </div>
                        <div class="space-y-2">
                            <label class="label">Dirección</label>
                            <input type="text" name="direccion" value="{{ old('direccion', $config->direccion) }}"
                                   class="w-full bg-cream border-0 rounded-2xl py-3 px-4 text-sm font-bold text-warm-700 focus:ring-2 focus:ring-rose-primary"
                                   placeholder="Calle 00 # 00-00, Ciudad">
                        </div>
                    </div>
                </div>

                {{-- QUIÉNES SOMOS --}}
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-warm-200 space-y-5">
                    <h2 class="font-black text-lg text-warm-900">Quiénes Somos</h2>

                    <div class="space-y-2">
                        <label class="label">Título de la sección</label>
                        <input type="text" name="quienes_somos_titulo" value="{{ old('quienes_somos_titulo', $config->quienes_somos_titulo) }}"
                               class="w-full bg-cream border-0 rounded-2xl py-3 px-5 font-bold text-warm-800 focus:ring-2 focus:ring-rose-primary">
                    </div>

                    <div class="space-y-2">
                        <label class="label">Texto</label>
                        <textarea name="quienes_somos_texto" rows="5"
                                  class="w-full bg-cream border-0 rounded-2xl py-4 px-5 text-sm text-warm-700 leading-relaxed focus:ring-2 focus:ring-rose-primary"
                                  placeholder="Cuéntanos sobre el negocio...">{{ old('quienes_somos_texto', $config->quienes_somos_texto) }}</textarea>
                    </div>
                </div>

                {{-- MISIÓN --}}
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-warm-200 space-y-5">
                    <h2 class="font-black text-lg text-warm-900">Misión</h2>
                    <div class="space-y-2">
                        <label class="label">Texto de misión</label>
                        <textarea name="mision_texto" rows="5"
                                  class="w-full bg-cream border-0 rounded-2xl py-4 px-5 text-sm text-warm-700 leading-relaxed focus:ring-2 focus:ring-rose-primary"
                                  placeholder="¿Cuál es la misión del negocio?">{{ old('mision_texto', $config->mision_texto) }}</textarea>
                    </div>
                </div>

                {{-- VISIÓN --}}
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-warm-200 space-y-5">
                    <h2 class="font-black text-lg text-warm-900">Visión</h2>
                    <div class="space-y-2">
                        <label class="label">Texto de visión</label>
                        <textarea name="vision_texto" rows="5"
                                  class="w-full bg-cream border-0 rounded-2xl py-4 px-5 text-sm text-warm-700 leading-relaxed focus:ring-2 focus:ring-rose-primary"
                                  placeholder="¿Cuál es la visión del negocio?">{{ old('vision_texto', $config->vision_texto) }}</textarea>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-rose-primary hover:bg-rose-dark text-white font-black py-5 rounded-2xl shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2 text-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Guardar Configuración
                </button>
            </form>

            {{-- ── GALERÍAS DE IMÁGENES POR SECCIÓN ─────────────── --}}
            @foreach([
                ['key' => 'quienes_somos', 'label' => 'Quiénes Somos'],
                ['key' => 'mision',        'label' => 'Misión'],
                ['key' => 'vision',        'label' => 'Visión'],
            ] as $sec)
            @php $imgs = $imagenesPorSeccion[$sec['key']] ?? collect(); @endphp
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-warm-200 space-y-5">

                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-black text-lg text-warm-900">Imágenes – {{ $sec['label'] }}</h2>
                        <p class="text-xs text-warm-400 mt-0.5">Estas imágenes forman el slider de la sección en la página principal.</p>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full bg-rose-light/20 text-rose-deeper">
                        {{ $imgs->count() }} {{ $imgs->count() === 1 ? 'imagen' : 'imágenes' }}
                    </span>
                </div>

                {{-- Grid de imágenes existentes --}}
                @if($imgs->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($imgs as $img)
                    <div class="relative group rounded-2xl overflow-hidden border border-warm-200 bg-cream"
                         style="aspect-ratio:4/3;">
                        <img src="{{ asset('storage/'.$img->imagen_url) }}"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-200 flex items-center justify-center">
                            <form method="POST" action="{{ route('configuracion.imagenes.destroy', $img) }}"
                                  class="opacity-0 group-hover:opacity-100 transition"
                                  onsubmit="return confirm('¿Eliminar esta imagen?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition active:scale-95">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="rounded-2xl border-2 border-dashed border-warm-200 py-8 text-center">
                    <p class="text-xs font-bold text-warm-300 uppercase tracking-widest">Sin imágenes todavía</p>
                </div>
                @endif

                {{-- Upload --}}
                <form method="POST" action="{{ route('configuracion.imagenes.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="seccion" value="{{ $sec['key'] }}">
                    <div class="relative group">
                        <input type="file" name="imagen" accept="image/*"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               onchange="this.form.submit()">
                        <div class="w-full bg-cream border-2 border-dashed border-warm-300 rounded-2xl py-5 flex items-center justify-center gap-2 group-hover:border-rose-primary transition-all">
                            <svg class="w-5 h-5 text-warm-300 group-hover:text-rose-primary transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-xs font-bold text-warm-400 group-hover:text-rose-primary transition">Agregar imagen al slider</span>
                        </div>
                    </div>
                </form>

            </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
