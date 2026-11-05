<x-app-layout titulo="Editar empleado">
    <x-slot name="header">
        <div class="grid gap-3">
            <a href="{{ route('empleados.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-texto hover:text-oceano w-fit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Equipo
            </a>
            <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">{{ $empleado->nombre }}</h1>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <x-aviso />
        <form method="POST" action="{{ route('empleados.update', $empleado) }}" enctype="multipart/form-data" class="card p-6 md:p-8 grid gap-8">
            @csrf
            @method('PUT')
            @include('empleados._form')
            <div class="flex flex-wrap items-center gap-3 pt-6 border-t border-linea">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="{{ route('empleados.index') }}" class="btn text-texto hover:text-oceano">Cancelar</a>
            </div>
        </form>

        <div class="grid gap-1 justify-items-end text-right">
            <x-confirmar-borrado :action="route('empleados.destroy', $empleado)" :que="'a ' . $empleado->nombre" texto="Eliminar" />
            <p class="text-sm text-texto-suave max-w-sm">Si solo dejará de atender un tiempo, mejor desmarca "Disponible para reservas".</p>
        </div>
    </div>
</x-app-layout>
