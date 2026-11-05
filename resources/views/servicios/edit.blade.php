<x-app-layout titulo="Editar servicio">
    <x-slot name="header">
        <div class="grid gap-3">
            <a href="{{ route('servicios.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-texto hover:text-oceano w-fit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Servicios
            </a>
            <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">{{ $servicio->nombre_publico }}</h1>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <x-aviso />

        {{-- Fotos actuales (fuera del formulario: cada una tiene su propio botón para quitarla) --}}
        @if ($servicio->imagenes->isNotEmpty())
            <section class="card p-6 grid gap-4" aria-labelledby="t-fotos">
                <h2 id="t-fotos" class="font-display text-xl">Fotos actuales</h2>
                <ul class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($servicio->imagenes as $img)
                        <li class="grid gap-2">
                            <x-foto :src="$img->url" proporcion="1/1" :alt="'Foto ' . $loop->iteration . ' de ' . $servicio->nombre_publico" />
                            <x-confirmar-borrado :action="route('servicios.eliminarImagen', $img->id)" que="esta foto" texto="Quitar" compacto />
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        <form method="POST" action="{{ route('servicios.update', $servicio->id) }}" enctype="multipart/form-data" class="card p-6 md:p-8 grid gap-8">
            @csrf
            @method('PUT')
            @include('servicios._form')
            <div class="flex flex-wrap items-center gap-3 pt-6 border-t border-linea">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="{{ route('servicios.index') }}" class="btn text-texto hover:text-oceano">Cancelar</a>
            </div>
        </form>

        <div class="flex justify-end">
            <x-confirmar-borrado :action="route('servicios.destroy', $servicio->id)" :que="'el servicio ' . $servicio->nombre_publico" texto="Eliminar" />
        </div>
    </div>
</x-app-layout>
