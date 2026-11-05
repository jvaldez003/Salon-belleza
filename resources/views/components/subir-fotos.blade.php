{{--
    Campo para subir fotos con vista previa del recorte.
    Al elegir una foto se muestra cómo quedará en cada lugar del sitio donde aparece (los "marcos"),
    antes de guardar. Sirve para fotos únicas o varias.

    Uso:
      <x-subir-fotos name="imagenes[]" multiple
                     :marcos="[['Carta de precios', '1/1'], ['Tarjeta', '4/5']]"
                     ayuda="Fotos verticales funcionan mejor." />

    Props:
      name      nombre del input (usa "[]" al final si es multiple)
      multiple  permite varias fotos
      marcos    [[texto, proporción], …] con proporciones "4/5", "3/4", "1/1", "16/9", "16/10"
      ayuda     texto de ayuda bajo el título
      etiqueta  título del campo
      id        id del input (por defecto se genera)
--}}
@props([
    'name',
    'multiple' => false,
    'marcos' => [['Vista previa', '4/5']],
    'ayuda' => null,
    'etiqueta' => null,
    'id' => null,
])

@php
    $id = $id ?? 'fotos-' . \Illuminate\Support\Str::random(6);
    $errorKey = str_replace('[]', '', $name);
    $proporcionCss = fn ($p) => str_replace('/', ' / ', $p);
@endphp

<div class="grid gap-3"
     x-data="{
        fotos: [],
        pesadas: 0,
        elegir(e) {
            this.fotos.forEach(f => URL.revokeObjectURL(f.url));
            const lista = Array.from(e.target.files || []);
            this.pesadas = lista.filter(f => f.size > 5 * 1024 * 1024).length;
            this.fotos = lista.map(f => ({ nombre: f.name, url: URL.createObjectURL(f), kb: Math.round(f.size / 1024) }));
        },
        limpiar() {
            this.fotos.forEach(f => URL.revokeObjectURL(f.url));
            this.fotos = []; this.pesadas = 0;
            this.$refs.input.value = '';
        }
     }">
    @if ($etiqueta)
        <div class="grid gap-0.5">
            <label for="{{ $id }}" class="label !mb-0">{{ $etiqueta }}</label>
            @if ($ayuda)<p class="text-sm text-texto-suave">{{ $ayuda }}</p>@endif
        </div>
    @endif

    <label for="{{ $id }}"
           class="relative flex flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-linea bg-bruma/50 px-6 py-8 text-center cursor-pointer hover:border-oceano hover:bg-bruma transition-colors focus-within:ring-2 focus-within:ring-laguna">
        <svg class="w-8 h-8 text-oceano" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.6-4.6a2 2 0 012.8 0L16 16m-2-2l1.6-1.6a2 2 0 012.8 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span class="font-semibold text-tinta" x-text="fotos.length ? (fotos.length === 1 ? 'Cambiar la foto elegida' : 'Cambiar las ' + fotos.length + ' fotos elegidas') : '{{ $multiple ? 'Elige una o varias fotos' : 'Elige una foto' }}'"></span>
        <span class="text-sm text-texto-suave">JPG, PNG o WebP de hasta 5 MB</span>
        <input x-ref="input" id="{{ $id }}" type="file" name="{{ $name }}" accept="image/jpeg,image/png,image/gif,image/webp"
               @if ($multiple) multiple @endif @change="elegir($event)" class="sr-only" {{ $attributes }}>
    </label>

    <p x-show="pesadas" x-cloak class="text-sm text-red-700" role="alert">
        <span x-text="pesadas === 1 ? 'Una foto pesa más de 5 MB' : pesadas + ' fotos pesan más de 5 MB'"></span> y no se podrá guardar. Elige una versión más liviana.
    </p>

    {{-- Vista previa: cada foto en cada marco del sitio --}}
    <div x-show="fotos.length" x-cloak class="grid gap-4 rounded-2xl border border-linea bg-white p-4">
        <div class="flex items-center justify-between gap-3">
            <p class="text-sm font-semibold text-tinta">Así se verá en el sitio</p>
            <button type="button" @click="limpiar()" class="text-sm text-texto-suave hover:text-red-700">Quitar</button>
        </div>
        <template x-for="foto in fotos" :key="foto.url">
            <div class="grid gap-2">
                <p class="text-xs text-texto-suave truncate" x-text="foto.nombre + ' · ' + (foto.kb > 1024 ? (foto.kb / 1024).toFixed(1) + ' MB' : foto.kb + ' KB')"></p>
                <div class="flex flex-wrap items-end gap-3">
                    @foreach ($marcos as [$texto, $proporcion])
                        <figure class="grid gap-1 m-0" style="width: {{ in_array($proporcion, ['16/9', '16/10']) ? '11rem' : '6.5rem' }}">
                            <div class="relative overflow-hidden rounded-xl bg-bruma-honda" style="aspect-ratio: {{ $proporcionCss($proporcion) }}">
                                <img :src="foto.url" alt="" class="absolute inset-0 w-full h-full object-cover">
                            </div>
                            <figcaption class="text-xs text-texto-suave">{{ $texto }}</figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </template>
    </div>

    <x-input-error :messages="collect($errors->get($errorKey))->merge($errors->get($errorKey . '.*'))->flatten()->unique()->all()" />
</div>
