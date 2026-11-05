<x-app-layout titulo="Editar banner">
    <x-slot name="header">
        <div class="grid gap-3">
            <a href="{{ route('banners.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-texto hover:text-oceano w-fit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Banners
            </a>
            <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">{{ Str::legible($banner->titulo) }}</h1>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <form method="POST" action="{{ route('banners.update', $banner->id) }}" enctype="multipart/form-data" class="card p-6 md:p-8 grid gap-8">
            @csrf
            @method('PUT')
            @include('banners._form')
            <div class="flex flex-wrap items-center gap-3 pt-6 border-t border-linea">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="{{ route('banners.index') }}" class="btn text-texto hover:text-oceano">Cancelar</a>
            </div>
        </form>
        <div class="flex justify-end">
            <x-confirmar-borrado :action="route('banners.destroy', $banner->id)" :que="'el banner ' . Str::legible($banner->titulo)" />
        </div>
    </div>
</x-app-layout>
