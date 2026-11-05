{{-- Banners del inicio (admin). El inicio muestra los activos, del más reciente al más antiguo. --}}
@php $activos = $banners->where('activo', true)->count(); @endphp

<x-app-layout titulo="Banners">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Banners</h1>
                <p class="text-texto-suave">La presentación del inicio del sitio. {{ $activos }} {{ $activos === 1 ? 'activo' : 'activos' }}; el más reciente sale primero.</p>
            </div>
            <x-boton :href="route('banners.create')">Nuevo banner</x-boton>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <x-aviso />

        @if ($banners->isEmpty())
            <div class="card p-10 grid gap-4 justify-items-start">
                <p class="font-display text-2xl text-tinta">Todavía no hay banners.</p>
                <p class="text-texto-suave">Sin banners, el inicio muestra la foto de "Quiénes somos" con un texto de bienvenida.</p>
                <x-boton :href="route('banners.create')">Crear el primero</x-boton>
            </div>
        @else
            <ul class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($banners->sortByDesc('created_at') as $b)
                    <li class="card overflow-hidden grid {{ $b->activo ? '' : 'opacity-70' }}">
                        <x-foto :src="$b->imagen_url" proporcion="4/5" foco="50% 30%" :alt="Str::legible($b->titulo)" class="rounded-t-[999px] mx-6 mt-6">
                            @unless ($b->activo)
                                <span class="absolute top-3 left-1/2 -translate-x-1/2 badge-warm">Oculto</span>
                            @endunless
                        </x-foto>
                        <div class="p-6 grid gap-3">
                            <p class="font-display text-xl text-tinta leading-tight">{{ Str::legible($b->titulo) }}</p>
                            @if ($b->subtitulo)<p class="text-sm text-texto-suave line-clamp-2">{{ Str::legible($b->subtitulo) }}</p>@endif
                            <p class="text-xs text-texto-suave">Botón: {{ $b->texto_boton ?: 'Reservar cita' }}</p>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 pt-1">
                                <a href="{{ route('banners.edit', $b->id) }}" class="text-sm font-semibold text-oceano hover:underline underline-offset-4">Editar<span class="sr-only"> {{ $b->titulo }}</span></a>
                                <x-confirmar-borrado :action="route('banners.destroy', $b->id)" :que="'el banner ' . Str::legible($b->titulo)" compacto />
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>
