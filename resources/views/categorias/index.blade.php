{{--
    Categorías de servicios (admin).
    Fotos de cada categoría en el inicio del sitio:
      foto 1 → tarjeta del selector de categorías (4:5)
      foto 2 → foto grande de la carta de precios (si no hay, se usa la foto 1)
--}}
<x-app-layout titulo="Categorías">
    <x-slot name="header">
        <div class="grid gap-3">
            <a href="{{ route('servicios.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-texto hover:text-oceano w-fit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Servicios
            </a>
            <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Categorías</h1>
            <p class="text-texto-suave max-w-[60ch]">Agrupan los servicios en el sitio y en la reserva. Solo aparecen en el sitio las categorías que tienen servicios.</p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <x-aviso />
        @if ($errors->any())
            <div class="alert-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('categorias.store') }}" class="card p-6 flex flex-wrap items-end gap-3">
            @csrf
            <div class="grid gap-1.5 flex-1 min-w-[14rem]">
                <label for="nueva-categoria" class="label">Nueva categoría</label>
                <input id="nueva-categoria" type="text" name="nombre" value="{{ old('nombre') }}" maxlength="80" required class="form-input" placeholder="Ej.: Uñas">
            </div>
            <button type="submit" class="btn-primary">Agregar</button>
        </form>

        @forelse ($categorias as $cat)
            @php
                $nombre = Str::legible($cat->nombre);
                $cuantos = $cat->servicios()->count();
                $fotos = $cat->imagenes;
            @endphp
            <article class="card overflow-hidden" x-data="{ abierta: false, editando: false }" aria-labelledby="cat-{{ $cat->id }}">
                <div class="flex flex-wrap items-center gap-4 p-4 sm:p-5">
                    <x-foto :src="optional($fotos->first())->url ?: $cat->imagen" proporcion="4/5" :inicial="$nombre" class="w-14 shrink-0 !rounded-xl" />

                    <div class="flex-1 min-w-[10rem]">
                        <h2 id="cat-{{ $cat->id }}" x-show="!editando" class="font-sans font-semibold text-lg text-tinta">{{ $nombre }}</h2>
                        <form x-show="editando" x-cloak method="POST" action="{{ route('categorias.update', $cat) }}" class="flex flex-wrap items-center gap-2">
                            @csrf @method('PUT')
                            <label for="nombre-{{ $cat->id }}" class="sr-only">Nombre de la categoría</label>
                            <input id="nombre-{{ $cat->id }}" type="text" name="nombre" value="{{ $cat->nombre }}" maxlength="80" required class="form-input py-2 flex-1 min-w-[10rem]">
                            <button type="submit" class="btn-primary !min-h-[40px]">Guardar</button>
                            <button type="button" @click="editando = false" class="text-sm text-texto hover:text-oceano">Cancelar</button>
                        </form>
                        <p class="text-sm text-texto-suave">
                            {{ $cuantos }} {{ $cuantos === 1 ? 'servicio' : 'servicios' }} · {{ $fotos->count() }} {{ $fotos->count() === 1 ? 'foto' : 'fotos' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm font-semibold">
                        <button type="button" x-show="!editando" @click="editando = true" class="text-oceano hover:underline underline-offset-4">Cambiar nombre</button>
                        <button type="button" @click="abierta = !abierta" :aria-expanded="abierta.toString()" aria-controls="fotos-{{ $cat->id }}"
                                class="inline-flex items-center gap-1 text-oceano hover:underline underline-offset-4">
                            Fotos
                            <svg class="w-4 h-4 transition-transform" :class="abierta && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <x-confirmar-borrado :action="route('categorias.destroy', $cat)"
                                             :que="'la categoría ' . $nombre . ($cuantos ? ' (sus ' . $cuantos . ' servicios quedarán sin categoría)' : '')" compacto />
                    </div>
                </div>

                <div id="fotos-{{ $cat->id }}" x-show="abierta" x-cloak class="border-t border-linea bg-superficie p-4 sm:p-6 grid gap-6">
                    @if ($fotos->isNotEmpty())
                        <ul class="flex flex-wrap gap-4">
                            @foreach ($fotos as $img)
                                <li class="grid gap-1.5 w-28">
                                    <x-foto :src="$img->url" proporcion="4/5" :alt="'Foto ' . $loop->iteration . ' de ' . $nombre" />
                                    <p class="text-xs text-texto-suave">
                                        {{ $loop->first ? 'Tarjeta del inicio' : ($loop->iteration === 2 ? 'Foto de la carta' : 'Foto extra') }}
                                    </p>
                                    <x-confirmar-borrado :action="route('categorias.imagen.destroy', $img)" que="esta foto" texto="Quitar" compacto />
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ route('categorias.imagen.store', $cat) }}" enctype="multipart/form-data" class="grid gap-4 max-w-xl">
                        @csrf
                        <x-subir-fotos name="imagen" :id="'foto-cat-' . $cat->id"
                                       :etiqueta="$fotos->isEmpty() ? 'Agregar la foto de la tarjeta' : ($fotos->count() === 1 ? 'Agregar la foto de la carta' : 'Agregar otra foto')"
                                       :ayuda="$fotos->count() === 1 ? 'Se muestra grande al lado de los precios. Si no subes ninguna, se repite la foto de la tarjeta.' : 'Las fotos verticales funcionan mejor.'"
                                       :marcos="$fotos->count() === 1 ? [['Carta (escritorio)', '4/5'], ['Carta (celular)', '16/10']] : [['Tarjeta del inicio', '4/5']]" />
                        <div><button type="submit" class="btn-primary">Subir foto</button></div>
                    </form>
                </div>
            </article>
        @empty
            <div class="card p-10">
                <p class="font-display text-2xl text-tinta">Todavía no hay categorías.</p>
                <p class="text-texto-suave mt-1">Crea la primera con el formulario de arriba.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
