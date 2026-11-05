{{--
    Formulario de banner con vista previa en vivo del inicio del sitio (texto + foto en el arco).
    Variables: $banner (o null)
--}}
@php
    $b = $banner ?? null;
    $fotoActual = $b?->imagen_url ? asset('storage/' . $b->imagen_url) : null;
@endphp

<div class="grid lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)] gap-8 items-start"
     x-data="{
        titulo: @js(old('titulo', $b?->titulo ?? '')),
        subtitulo: @js(old('subtitulo', $b?->subtitulo ?? '')),
        boton: @js(old('texto_boton', $b?->texto_boton ?? '')),
        foto: @js($fotoActual),
        nueva: false,
        elegir(e) { const f = e.target.files[0]; if (!f) return; if (this.nueva) URL.revokeObjectURL(this.foto); this.foto = URL.createObjectURL(f); this.nueva = true; }
     }">

    {{-- Campos --}}
    <div class="grid gap-6">
        <div class="grid gap-1.5">
            <label for="titulo" class="label">Título</label>
            <input id="titulo" type="text" name="titulo" x-model="titulo" maxlength="255" required class="form-input" placeholder="Ej.: Belleza, estilo y confianza">
            <p class="text-xs text-texto-suave">Corto funciona mejor: 3 a 6 palabras.</p>
            <x-input-error :messages="$errors->get('titulo')" />
        </div>

        <div class="grid gap-1.5">
            <label for="subtitulo" class="label">Texto <span class="font-normal text-texto-suave">(opcional)</span></label>
            <textarea id="subtitulo" name="subtitulo" x-model="subtitulo" rows="3" maxlength="400" class="form-input" placeholder="Una o dos frases sobre el estudio."></textarea>
            <p class="text-xs text-texto-suave"><span x-text="subtitulo.length"></span> de 400 caracteres. En el inicio se muestran hasta 4 líneas.</p>
            <x-input-error :messages="$errors->get('subtitulo')" />
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            <div class="grid gap-1.5">
                <label for="texto_boton" class="label">Texto del botón <span class="font-normal text-texto-suave">(opcional)</span></label>
                <input id="texto_boton" type="text" name="texto_boton" x-model="boton" maxlength="40" class="form-input" placeholder="Reservar cita">
            </div>
            <div class="grid gap-1.5">
                <label for="link_boton" class="label">Enlace del botón</label>
                <input id="link_boton" type="text" name="link_boton" value="{{ old('link_boton', $b?->link_boton) }}" class="form-input" placeholder="#servicios">
            </div>
        </div>
        <p class="text-xs text-texto-suave -mt-3">Si dejas el botón vacío, se muestra "Reservar cita" y lleva al formulario de reserva.</p>

        <div class="grid gap-1.5">
            <label for="imagen" class="label">{{ $b ? 'Cambiar la foto' : 'Foto' }}</label>
            <input id="imagen" type="file" name="imagen" accept="image/jpeg,image/png,image/webp" @change="elegir($event)" @if (! $b) required @endif
                   class="block w-full text-sm text-texto file:mr-3 file:rounded-full file:border-0 file:bg-oceano file:px-4 file:py-2 file:text-white file:font-semibold file:cursor-pointer">
            <p class="text-xs text-texto-suave">Vertical o cuadrada, de al menos 800 × 800 px y hasta 10 MB. Se recorta en forma de arco; deja las caras en la parte de arriba o al centro.</p>
            <x-input-error :messages="$errors->get('imagen')" />
        </div>

        <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" name="activo" value="1" @checked($errors->any() ? old('activo') : ($b?->activo ?? true))
                   class="mt-0.5 w-5 h-5 rounded border-linea text-oceano focus:ring-laguna">
            <span>
                <span class="block font-semibold text-tinta">Mostrar en el inicio</span>
                <span class="block text-sm text-texto-suave">Si hay varios activos, se cambian con flechas en el inicio.</span>
            </span>
        </label>
    </div>

    {{-- Vista previa del inicio --}}
    <aside class="grid gap-3 lg:sticky lg:top-6" aria-label="Vista previa">
        <p class="font-display italic text-xl text-oceano">Así se ve en el inicio</p>
        <div class="card p-5 sm:p-6 bg-superficie grid grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)] gap-4 items-center" aria-hidden="true">
            <div class="grid gap-2 min-w-0">
                <p class="font-display text-[2rem] leading-[.9] text-tinta">{{ $configuracionSitio->nombre_negocio ?? 'Arrecife' }}</p>
                <p class="font-display italic text-xs text-oceano -mt-1">Beauty Studio</p>
                <p class="font-display text-base leading-tight text-tinta break-words" x-text="titulo || 'Título del banner'"></p>
                <p class="text-[0.7rem] leading-snug text-texto line-clamp-4 break-words" x-text="subtitulo"></p>
                <span class="mt-1 inline-flex w-fit rounded-full bg-oceano text-white text-[0.65rem] font-semibold px-3 py-1.5" x-text="boton || 'Reservar cita'"></span>
            </div>
            <div class="relative overflow-hidden bg-bruma-honda aspect-4/5 rounded-t-[999px] rounded-b-xl">
                <template x-if="foto"><img :src="foto" alt="" class="absolute inset-0 w-full h-full object-cover" style="object-position: 50% 30%"></template>
                <div x-show="!foto" class="absolute inset-0 grid place-items-center text-xs text-texto-suave p-4 text-center">La foto aparece aquí</div>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent from-55% to-abismo/45"></div>
            </div>
        </div>
        <p class="text-xs text-texto-suave">Vista reducida. En el celular la foto va arriba y el texto debajo.</p>
    </aside>
</div>
