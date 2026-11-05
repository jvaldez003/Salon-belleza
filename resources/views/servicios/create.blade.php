<x-app-layout titulo="Nuevo servicio">
    <x-slot name="header">
        <div class="grid gap-3">
            <a href="{{ route('servicios.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-texto hover:text-oceano w-fit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Servicios
            </a>
            <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Nuevo servicio</h1>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 md:py-10">
        <form method="POST" action="{{ route('servicios.store') }}" enctype="multipart/form-data" class="card p-6 md:p-8 grid gap-8">
            @csrf
            @include('servicios._form', ['servicio' => null])
            <div class="flex flex-wrap gap-3 pt-2 border-t border-linea">
                <button type="submit" class="btn-primary mt-6">Crear servicio</button>
                <a href="{{ route('servicios.index') }}" class="btn mt-6 text-texto hover:text-oceano">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
