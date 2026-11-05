{{-- Editar protocolo: datos + vista previa de la ficha pública + fotos y videos. --}}
@php $f = $protocolo->ficha; @endphp

<x-app-layout titulo="Editar protocolo">
    <x-slot name="header">
        <div class="grid gap-3">
            <a href="{{ route('protocolos.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-texto hover:text-oceano w-fit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Protocolos
            </a>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">{{ $protocolo->nombre_publico }}</h1>
                @unless ($protocolo->activo)<span class="badge-warm">Oculto</span>@endunless
            </div>
            @if ($protocolo->activo)
                <a href="{{ route('protocolos.publico') }}#protocolo-{{ $protocolo->id }}" target="_blank" rel="noopener" class="text-sm font-semibold text-oceano hover:underline underline-offset-4 w-fit">Ver en el sitio</a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-8">
        <x-aviso />

        <div class="grid lg:grid-cols-[minmax(0,7fr)_minmax(0,5fr)] gap-8 items-start">
            {{-- Datos --}}
            <form method="POST" action="{{ route('protocolos.update', $protocolo) }}" class="card p-6 md:p-8 grid gap-8">
                @csrf @method('PUT')
                @include('protocolos._campos')
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="hidden" name="activo" value="0">
                    <input type="checkbox" name="activo" value="1" @checked(old('activo', $protocolo->activo)) class="mt-0.5 w-5 h-5 rounded border-linea text-oceano focus:ring-laguna">
                    <span>
                        <span class="block font-semibold text-tinta">Mostrar en el sitio</span>
                        <span class="block text-sm text-texto-suave">Desmárcalo para ocultarlo sin borrarlo.</span>
                    </span>
                </label>
                <div class="flex flex-wrap items-center gap-3 pt-6 border-t border-linea">
                    <button type="submit" class="btn-primary">Guardar cambios</button>
                </div>
            </form>

            {{-- Vista previa de la ficha (con lo guardado) --}}
            <aside class="grid gap-3 lg:sticky lg:top-6" aria-labelledby="t-previa">
                <h2 id="t-previa" class="font-display italic text-xl text-oceano">Así se ve en el sitio</h2>
                <div class="card p-6 grid gap-5">
                    <p class="font-display text-2xl text-tinta leading-tight">{{ $protocolo->nombre_publico }}</p>
                    @if ($f['intro'])<p class="text-[0.9375rem] leading-relaxed">{{ $f['intro'] }}</p>@endif
                    @if ($f['duracion'])
                        <p class="text-sm"><span class="text-texto-suave">Duración:</span> <span class="text-tinta font-semibold">{{ $f['duracion'] }}</span>@if ($f['duracionNota'])<span class="text-texto-suave"> ({{ $f['duracionNota'] }})</span>@endif</p>
                    @endif
                    @if ($f['pasos'])
                        <div class="grid gap-2">
                            <p class="text-sm font-semibold text-tinta">Paso a paso</p>
                            <ol class="grid gap-1.5 text-sm">
                                @foreach ($f['pasos'] as $n => $paso)
                                    <li class="flex gap-2"><span class="w-5 h-5 shrink-0 rounded-full bg-oceano text-white grid place-items-center text-[0.7rem] font-semibold">{{ $n + 1 }}</span>{{ $paso }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                    @if ($f['notas'])
                        <div class="grid gap-1.5">
                            <p class="text-sm font-semibold text-tinta">Antes de reservar</p>
                            <ul class="grid gap-1 text-sm list-disc pl-5">@foreach ($f['notas'] as $n)<li>{{ $n }}</li>@endforeach</ul>
                        </div>
                    @endif

                    @php
                        $faltan = array_filter([
                            ! $f['pasos'] ? 'una línea "INCLUYE:" con los pasos' : null,
                            ! $f['duracion'] ? 'una línea "DURACIÓN"' : null,
                            $protocolo->medios->isEmpty() ? 'al menos una foto' : null,
                        ]);
                    @endphp
                    @if ($faltan)
                        <p class="text-sm rounded-xl bg-bruma p-3"><strong class="text-tinta">Para completar la ficha falta:</strong> {{ implode(', ', $faltan) }}.</p>
                    @endif
                </div>
                <p class="text-xs text-texto-suave">La vista previa se actualiza al guardar.</p>
            </aside>
        </div>

        {{-- Fotos y videos --}}
        <section class="card p-6 md:p-8 grid gap-8" aria-labelledby="t-medios">
            <div class="grid gap-1">
                <h2 id="t-medios" class="text-2xl">Fotos y videos</h2>
                <p class="text-sm text-texto-suave">La primera foto es la principal de la ficha. Las demás se ven como miniaturas para cambiar.</p>
            </div>

            @if ($protocolo->medios->isNotEmpty())
                <ul class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach ($protocolo->medios as $m)
                        <li class="grid gap-1.5">
                            @if ($m->tipo === 'imagen')
                                <x-foto :src="$m->url" proporcion="4/5" :alt="'Foto ' . $loop->iteration" />
                            @else
                                <div class="foto-marco aspect-4/5 bg-abismo grid place-items-center text-white">
                                    <span class="relative grid justify-items-center gap-1 text-sm">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86A1 1 0 008 5.14z"/></svg>
                                        {{ $m->esUrlExterna() ? 'Video de YouTube' : 'Video' }}
                                    </span>
                                </div>
                            @endif
                            <p class="text-xs text-texto-suave">{{ $loop->first ? 'Principal' : 'Miniatura ' . $loop->iteration }}</p>
                            <x-confirmar-borrado :action="route('protocolos.medios.destroy', $m)" :que="$m->tipo === 'imagen' ? 'esta foto' : 'este video'" texto="Quitar" compacto />
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="grid md:grid-cols-2 gap-8 pt-6 border-t border-linea">
                <form method="POST" action="{{ route('protocolos.medios.store', $protocolo) }}" enctype="multipart/form-data" class="grid gap-4 content-start">
                    @csrf
                    <input type="hidden" name="tipo" value="imagen">
                    <x-subir-fotos name="archivo" id="foto-protocolo" etiqueta="Agregar una foto"
                                   ayuda="Las fotos verticales se ven mejor en la ficha."
                                   :marcos="[['Ficha', '4/5'], ['Miniatura', '1/1']]" />
                    <div><button type="submit" class="btn-primary">Subir foto</button></div>
                </form>

                <div class="grid gap-4 content-start" x-data="{ modo: 'url' }">
                    <div class="grid gap-2">
                        <p class="label !mb-0">Agregar un video</p>
                        <div class="inline-flex p-1 rounded-full bg-bruma w-fit" role="group" aria-label="Tipo de video">
                            <button type="button" @click="modo = 'url'" :aria-pressed="(modo === 'url').toString()" class="px-4 py-1.5 rounded-full text-sm font-medium" :class="modo === 'url' ? 'bg-white text-oceano shadow-sm' : 'text-texto'">Enlace de YouTube</button>
                            <button type="button" @click="modo = 'archivo'" :aria-pressed="(modo === 'archivo').toString()" class="px-4 py-1.5 rounded-full text-sm font-medium" :class="modo === 'archivo' ? 'bg-white text-oceano shadow-sm' : 'text-texto'">Archivo de video</button>
                        </div>
                    </div>
                    <form x-show="modo === 'url'" method="POST" action="{{ route('protocolos.medios.store', $protocolo) }}" class="grid gap-3">
                        @csrf
                        <input type="hidden" name="tipo" value="video">
                        <label for="video_url" class="sr-only">Enlace de YouTube</label>
                        <input id="video_url" type="url" name="video_url" required class="form-input" placeholder="https://www.youtube.com/watch?v=…">
                        <div><button type="submit" class="btn-secondary">Agregar video</button></div>
                    </form>
                    <form x-show="modo === 'archivo'" x-cloak method="POST" action="{{ route('protocolos.medios.store', $protocolo) }}" enctype="multipart/form-data" class="grid gap-3">
                        @csrf
                        <input type="hidden" name="tipo" value="video">
                        <label for="video_archivo" class="sr-only">Archivo de video</label>
                        <input id="video_archivo" type="file" name="archivo" accept="video/mp4,video/quicktime,video/webm" required
                               class="block w-full text-sm text-texto file:mr-3 file:rounded-full file:border-0 file:bg-oceano file:px-4 file:py-2 file:text-white file:font-semibold">
                        <p class="text-xs text-texto-suave">MP4, MOV o WebM de hasta 50 MB.</p>
                        <div><button type="submit" class="btn-secondary">Subir video</button></div>
                    </form>
                    <x-input-error :messages="array_merge($errors->get('video_url'), $errors->get('tipo'))" />
                </div>
            </div>
        </section>

        <div class="flex justify-end">
            <x-confirmar-borrado :action="route('protocolos.destroy', $protocolo)" :que="'el protocolo ' . $protocolo->nombre_publico . ' con sus fotos y videos'" />
        </div>
    </div>
</x-app-layout>
