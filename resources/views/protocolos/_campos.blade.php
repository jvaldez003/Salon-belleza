{{--
    Campos de nombre y descripción del protocolo, con la guía de formato que entiende la página pública.
    Variables: $protocolo (o null)
--}}
@php
    $plantilla = "Tratamiento hidratante (no alisa) que aporta suavidad y brillo al cabello.\nINCLUYE: aplicación de tratamiento, plancha infrarroja, gorro térmico, lavado, cepillado\nDURACIÓN: aprox. dos horas (depende del largo del cabello)\nRequiere cepillado del cabello.";
@endphp

<div class="grid gap-6" x-data="{ texto: @js(old('descripcion', $protocolo?->descripcion ?? '')) }">
    <div class="grid gap-1.5">
        <label for="nombre" class="label">Nombre del tratamiento</label>
        <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $protocolo?->nombre) }}" maxlength="120" required
               class="form-input" placeholder="Ej.: Spa capilar">
        <x-input-error :messages="$errors->get('nombre')" />
    </div>

    <div class="grid gap-1.5">
        <div class="flex flex-wrap items-baseline justify-between gap-2">
            <label for="descripcion" class="label">Descripción</label>
            <button type="button" x-show="!texto.trim()" @click="texto = @js($plantilla)" class="text-sm font-semibold text-oceano hover:underline underline-offset-4">Insertar plantilla</button>
        </div>
        <textarea id="descripcion" name="descripcion" rows="8" maxlength="2000" x-model="texto" class="form-input font-mono text-sm leading-relaxed"></textarea>
        <div class="rounded-xl bg-bruma p-4 text-sm text-texto grid gap-1.5">
            <p class="font-semibold text-tinta">Cómo se arma la ficha en el sitio</p>
            <p><strong>Primera línea:</strong> qué es el tratamiento (va como introducción).</p>
            <p><strong>Línea que empieza por "INCLUYE:"</strong> los pasos separados por comas. Se muestran como "Paso a paso", en ese orden.</p>
            <p><strong>Línea que empieza por "DURACIÓN"</strong> el tiempo. Lo que va entre paréntesis sale como aclaración.</p>
            <p><strong>Las demás líneas</strong> salen en "Antes de reservar".</p>
        </div>
        <x-input-error :messages="$errors->get('descripcion')" />
    </div>
</div>
