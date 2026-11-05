{{--
    Campos del formulario de servicio (crear y editar).
    Variables: $servicio (o null), $categorias
--}}
@php
    $s = $servicio ?? null;
    $nombre = old('nombre', $s?->nombre);
@endphp

<div class="grid gap-6">
    <div class="grid sm:grid-cols-[minmax(0,1fr)_12rem] gap-6">
        <div class="grid gap-1.5">
            <label for="nombre" class="label">Nombre del servicio</label>
            <input id="nombre" type="text" name="nombre" value="{{ $nombre }}" maxlength="60" required
                   class="form-input" placeholder="Ej.: Diseño de cejas">
            <p class="text-xs text-texto-suave">Si el precio puede subir, escribe "desde" al final (ej.: "Peinados desde"). En el sitio se mostrará "desde $50.000".</p>
            <x-input-error :messages="$errors->get('nombre')" />
        </div>
        <div class="grid gap-1.5 content-start">
            <label for="categoria_id" class="label">Categoría</label>
            <select id="categoria_id" name="categoria_id" class="form-input">
                <option value="">Sin categoría</option>
                @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}" @selected(old('categoria_id', $s?->categoria_id) == $cat->id)>{{ Str::legible($cat->nombre) }}</option>
                @endforeach
            </select>
            @if ($categorias->isEmpty())
                <a href="{{ route('categorias.index') }}" class="text-xs text-oceano hover:underline">Crea una categoría primero</a>
            @endif
            <x-input-error :messages="$errors->get('categoria_id')" />
        </div>
    </div>

    <div class="grid sm:grid-cols-2 gap-6">
        <div class="grid gap-1.5">
            <label for="precio" class="label">Precio en pesos</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-texto-suave pointer-events-none" aria-hidden="true">$</span>
                <input id="precio" type="number" name="precio" value="{{ old('precio', $s ? (float) $s->precio : null) }}" min="0" step="100" required
                       class="form-input pl-8 font-display text-xl text-oceano tabular" placeholder="50000">
            </div>
            <p class="text-xs text-texto-suave">Sin puntos ni signos. Ej.: 50000</p>
            <x-input-error :messages="$errors->get('precio')" />
        </div>

        <div class="grid gap-1.5" x-data="{ min: {{ (int) old('duracion', $s?->duracion ?? 60) }} }">
            <label for="duracion" class="label">Duración aproximada</label>
            <select id="duracion" name="duracion" x-model.number="min" class="form-input">
                @php
                    $actual = (int) old('duracion', $s?->duracion ?? 60);
                    $opciones = collect([15, 20, 30, 45, 60, 75, 90, 105, 120, 150, 180, 210, 240, 300])->push($actual)->unique()->sort();
                @endphp
                @foreach ($opciones as $m)
                    @php $h = intdiv($m, 60); $r = $m % 60; @endphp
                    <option value="{{ $m }}" @selected((int) old('duracion', $s?->duracion ?? 60) === $m)>
                        {{ $m < 60 ? "$m min" : ($r ? "$h h $r min" : ($h === 1 ? '1 hora' : "$h horas")) }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-texto-suave">La agenda la usa para calcular a qué hora termina cada cita.</p>
            <x-input-error :messages="$errors->get('duracion')" />
        </div>
    </div>

    <div class="grid gap-1.5">
        <label for="descripcion" class="label">Descripción <span class="font-normal text-texto-suave">(opcional)</span></label>
        <textarea id="descripcion" name="descripcion" rows="3" class="form-input"
                  placeholder="Qué incluye y para quién es.">{{ old('descripcion', $s?->descripcion) }}</textarea>
        <x-input-error :messages="$errors->get('descripcion')" />
    </div>

    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" name="activo" value="1" @checked($errors->any() ? old('activo') : ($s?->activo ?? true))
               class="mt-0.5 w-5 h-5 rounded border-linea text-oceano focus:ring-laguna">
        <span>
            <span class="block font-semibold text-tinta">Mostrar en el sitio y en las reservas</span>
            <span class="block text-sm text-texto-suave">Desmárcalo para ocultar el servicio sin borrarlo.</span>
        </span>
    </label>

    <x-subir-fotos name="imagenes[]" multiple
                   :etiqueta="$s ? 'Agregar fotos' : 'Fotos del servicio'"
                   ayuda="Opcional. Aparecen en miniatura junto al servicio en la carta de precios."
                   :marcos="[['Carta de precios', '1/1']]" />
</div>
