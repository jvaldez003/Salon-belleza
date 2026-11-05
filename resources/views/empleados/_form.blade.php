{{--
    Campos del formulario de empleado (crear y editar).
    Variables: $empleado (o null), $servicios, $categorias, $salon, $horariosDefault
--}}
@php
    $e = $empleado ?? null;
    $asignados = collect(old('servicios', $e?->servicios->pluck('id')->all() ?? []))->map(fn ($id) => (int) $id)->all();
    $grupos = $categorias
        ->map(fn ($c) => ['nombre' => Str::legible($c->nombre), 'items' => $servicios->where('categoria_id', $c->id)->values()])
        ->filter(fn ($g) => $g['items']->isNotEmpty())->values();
    $sinCat = $servicios->whereNull('categoria_id')->values();
    if ($sinCat->isNotEmpty()) $grupos->push(['nombre' => 'Sin categoría', 'items' => $sinCat]);
@endphp

<section class="grid gap-6" aria-labelledby="t-datos">
    <h2 id="t-datos" class="font-display text-xl">Datos</h2>

    <div class="grid sm:grid-cols-2 gap-6">
        <div class="grid gap-1.5 content-start">
            <label for="nombre" class="label">Nombre</label>
            <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $e?->nombre) }}" maxlength="100" required
                   class="form-input" placeholder="Ej.: Leidy Ordoñez" autocomplete="off">
            <x-input-error :messages="$errors->get('nombre')" />
        </div>
        <div class="grid gap-1.5 content-start">
            <label for="especialidad" class="label">Especialidad <span class="font-normal text-texto-suave">(opcional)</span></label>
            <input id="especialidad" type="text" name="especialidad" value="{{ old('especialidad', $e?->especialidad) }}" maxlength="100"
                   class="form-input" placeholder="Ej.: Colorimetría y cortes">
            <x-input-error :messages="$errors->get('especialidad')" />
        </div>
    </div>

    <div class="grid sm:grid-cols-[6rem_minmax(0,1fr)] gap-5 items-start">
        <div class="grid gap-2 justify-items-start">
            <x-foto :src="$e?->foto" proporcion="1/1" :inicial="$e?->nombre ?? 'A'" :alt="$e ? 'Foto de ' . $e->nombre : ''" class="w-24 !rounded-full" />
            @if ($e?->foto)
                <label class="inline-flex items-center gap-2 text-sm text-texto cursor-pointer">
                    <input type="checkbox" name="quitar_foto" value="1" class="w-4 h-4 rounded border-linea text-red-700 focus:ring-laguna">
                    Quitar foto
                </label>
            @endif
        </div>
        <x-subir-fotos name="foto" :etiqueta="$e?->foto ? 'Cambiar foto' : 'Foto (opcional)'"
                       ayuda="Se ve en círculo junto al nombre cuando el cliente elige con quién atenderse. Usa una foto de la cara, centrada."
                       :marcos="[['Al reservar', '1/1']]" />
    </div>

    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" name="activo" value="1" @checked($errors->any() ? old('activo') : ($e?->activo ?? true))
               class="mt-0.5 w-5 h-5 rounded border-linea text-oceano focus:ring-laguna">
        <span>
            <span class="block font-semibold text-tinta">Disponible para reservas</span>
            <span class="block text-sm text-texto-suave">Desmárcalo si está de vacaciones o ya no trabaja en el salón. Sus citas pasadas se conservan.</span>
        </span>
    </label>
</section>

<section class="grid gap-4 pt-8 border-t border-linea" aria-labelledby="t-servicios"
         x-data="{ marcados: {{ count($asignados) }}, contar() { this.marcados = this.$el.querySelectorAll('input[name=&quot;servicios[]&quot;]:checked').length } }"
         @change="contar()">
    <div class="grid gap-1">
        <h2 id="t-servicios" class="font-display text-xl">Servicios que realiza</h2>
        <p class="text-sm text-texto-suave">El cliente podrá elegir a esta persona solo en estos servicios.
            <span class="font-semibold text-texto" x-text="marcados === 1 ? '1 marcado' : marcados + ' marcados'"></span></p>
    </div>

    @if ($servicios->isEmpty())
        <p class="text-texto-suave">Todavía no hay servicios. <a href="{{ route('servicios.create') }}" class="text-oceano font-semibold hover:underline">Crear un servicio</a></p>
    @else
        <div class="grid gap-3">
            @foreach ($grupos as $g)
                @php $ids = $g['items']->pluck('id')->all(); @endphp
                <details class="card group" @if (array_intersect($ids, $asignados) || $loop->first) open @endif>
                    <summary class="flex items-center justify-between gap-3 px-4 sm:px-5 py-3 cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                        <span class="font-display italic text-lg text-oceano">{{ $g['nombre'] }}
                            <span class="not-italic font-sans text-sm text-texto-suave">({{ $g['items']->count() }})</span></span>
                        <svg class="w-4 h-4 text-texto-suave transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <div class="px-4 sm:px-5 pb-4 grid gap-3">
                        <button type="button" class="justify-self-start text-sm font-semibold text-oceano hover:underline underline-offset-4"
                                @click="const c = [...$el.parentElement.querySelectorAll('input[type=checkbox]')]; const v = c.some(i => !i.checked); c.forEach(i => i.checked = v); contar()">
                            Marcar o desmarcar todos
                        </button>
                        <div class="grid sm:grid-cols-2 gap-x-6 gap-y-1">
                            @foreach ($g['items'] as $s)
                                <label class="flex items-center gap-3 py-1.5 cursor-pointer">
                                    <input type="checkbox" name="servicios[]" value="{{ $s->id }}" @checked(in_array($s->id, $asignados))
                                           class="w-5 h-5 rounded border-linea text-oceano focus:ring-laguna shrink-0">
                                    <span class="min-w-0 text-tinta">{{ $s->nombre_publico }}
                                        @unless ($s->activo)<span class="text-sm text-texto-suave">(oculto)</span>@endunless
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </details>
            @endforeach
        </div>
    @endif
    <x-input-error :messages="$errors->get('servicios')" />
</section>

<section class="grid gap-4 pt-8 border-t border-linea" aria-labelledby="t-horario">
    <x-horario-semana :dias="$horariosDefault" :salon="$salon" si="Trabaja" no="Descansa">
        <div class="grid gap-1">
            <h2 id="t-horario" class="font-display text-xl">Horario de trabajo</h2>
            <p class="text-sm text-texto-suave">Las reservas con esta persona solo se ofrecen en estas horas.</p>
        </div>
    </x-horario-semana>
</section>
