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
                            <label class="label mt-3 block">Eslogan</label>
                            <input type="text" name="eslogan" value="{{ old('eslogan', $config->eslogan) }}"
                                   class="w-full bg-cream border-0 rounded-2xl py-3 px-5 text-sm font-bold text-warm-800 focus:ring-2 focus:ring-rose-primary"
                                   placeholder="Elegancia y bienestar a tu medida">
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

                {{-- REDES SOCIALES --}}
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-warm-200 space-y-5">
                    <div>
                        <h2 class="font-black text-lg text-warm-900">Redes Sociales</h2>
                        <p class="text-xs text-warm-400 mt-1">Aparecen en el footer y como opciones de contacto rápido.</p>
                    </div>

                    <div class="space-y-4">
                        {{-- WhatsApp --}}
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #0A2F6B 0%, #008FE8 100%);">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </div>
                            <div class="flex-1">
                                <label class="label">WhatsApp <span class="text-warm-300 font-normal">(número internacional, ej: 573001234567)</span></label>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp', $config->whatsapp) }}"
                                       class="w-full bg-cream border-0 rounded-2xl py-3 px-4 text-sm font-bold text-warm-700 focus:ring-2 focus:ring-rose-primary"
                                       placeholder="573001234567">
                            </div>
                        </div>

                        {{-- Facebook --}}
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0" style="background:#1877F2;">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </div>
                            <div class="flex-1">
                                <label class="label">Facebook <span class="text-warm-300 font-normal">(URL completa)</span></label>
                                <input type="url" name="facebook" value="{{ old('facebook', $config->facebook) }}"
                                       class="w-full bg-cream border-0 rounded-2xl py-3 px-4 text-sm font-bold text-warm-700 focus:ring-2 focus:ring-rose-primary"
                                       placeholder="https://facebook.com/tu-pagina">
                            </div>
                        </div>

                        {{-- Instagram --}}
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0" style="background: linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </div>
                            <div class="flex-1">
                                <label class="label">Instagram <span class="text-warm-300 font-normal">(URL completa)</span></label>
                                <input type="url" name="instagram" value="{{ old('instagram', $config->instagram) }}"
                                       class="w-full bg-cream border-0 rounded-2xl py-3 px-4 text-sm font-bold text-warm-700 focus:ring-2 focus:ring-rose-primary"
                                       placeholder="https://instagram.com/tu-perfil">
                            </div>
                        </div>

                        {{-- TikTok --}}
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 bg-black">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.75a4.85 4.85 0 01-1.01-.06z"/></svg>
                            </div>
                            <div class="flex-1">
                                <label class="label">TikTok <span class="text-warm-300 font-normal">(URL completa)</span></label>
                                <input type="url" name="tiktok" value="{{ old('tiktok', $config->tiktok) }}"
                                       class="w-full bg-cream border-0 rounded-2xl py-3 px-4 text-sm font-bold text-warm-700 focus:ring-2 focus:ring-rose-primary"
                                       placeholder="https://tiktok.com/@tu-perfil">
                            </div>
                        </div>

                        {{-- YouTube --}}
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0" style="background:#FF0000;">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </div>
                            <div class="flex-1">
                                <label class="label">YouTube <span class="text-warm-300 font-normal">(URL completa)</span></label>
                                <input type="url" name="youtube" value="{{ old('youtube', $config->youtube) }}"
                                       class="w-full bg-cream border-0 rounded-2xl py-3 px-4 text-sm font-bold text-warm-700 focus:ring-2 focus:ring-rose-primary"
                                       placeholder="https://youtube.com/@tu-canal">
                            </div>
                        </div>
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
