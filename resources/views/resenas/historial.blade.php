{{-- Citas completadas y sus reseñas. La clienta ve las suyas; el admin ve todas y puede buscar por cliente. --}}
@php $esAdmin = auth()->user()->isAdmin(); @endphp

<x-app-layout titulo="Historial y reseñas">
    <x-slot name="header">
        <div class="grid gap-1">
            <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Historial y reseñas</h1>
            <p class="text-texto-suave">{{ $esAdmin ? 'Servicios completados y lo que opinaron los clientes.' : 'Tus servicios completados. Cuéntanos cómo te fue.' }}</p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <x-aviso />

        @if ($esAdmin)
            <form method="GET" class="flex flex-wrap items-end gap-3" role="search" aria-label="Buscar en el historial">
                <div class="grid gap-1.5 flex-1 min-w-[12rem]">
                    <label for="h-buscar" class="label">Cliente</label>
                    <input id="h-buscar" type="search" name="buscar" value="{{ request('buscar') }}" class="form-input" placeholder="Nombre del cliente">
                </div>
                <button type="submit" class="btn-primary">Buscar</button>
            </form>
        @endif

        @forelse ($citas as $cita)
            <article class="card p-6 grid sm:grid-cols-[minmax(0,1fr)_auto] gap-x-8 gap-y-4" aria-label="Cita del {{ $cita->fecha_larga }}">
                <div class="grid gap-1 min-w-0">
                    <p class="font-semibold text-tinta first-letter:uppercase">{{ $cita->fecha_larga }}, {{ $cita->hora_texto }}</p>
                    @if ($esAdmin && $cita->usuario)<p class="text-sm text-texto-suave">{{ $cita->usuario->name }}</p>@endif
                    <p class="text-texto">{{ $cita->servicios_texto }}</p>
                    <p class="font-display text-lg text-oceano tabular">{{ '$' . number_format($cita->total, 0, ',', '.') }}</p>
                </div>

                <div class="sm:text-right sm:max-w-xs grid gap-2 content-start">
                    @if ($cita->resena)
                        <p class="text-xl leading-none tracking-wider" aria-label="{{ $cita->resena->calificacion }} de 5 estrellas">
                            @for ($i = 1; $i <= 5; $i++)<span class="{{ $i <= $cita->resena->calificacion ? 'text-laguna' : 'text-linea' }}" aria-hidden="true">★</span>@endfor
                        </p>
                        @if ($cita->resena->comentario)
                            <p class="font-display italic text-texto leading-snug">“{{ $cita->resena->comentario }}”</p>
                        @endif
                        <p class="text-xs text-texto-suave">{{ $cita->resena->created_at->translatedFormat('j \d\e F \d\e Y') }}</p>
                    @elseif ($cita->user_id === auth()->id())
                        <x-boton :href="route('resenas.create', $cita)">Dejar reseña</x-boton>
                    @else
                        <p class="text-sm text-texto-suave">Sin reseña</p>
                    @endif
                </div>
            </article>
        @empty
            <div class="card p-10 grid gap-2">
                <p class="font-display text-2xl text-tinta">Todavía no hay servicios completados.</p>
                @unless ($esAdmin)
                    <p class="text-texto-suave">Cuando terminemos tu servicio, podrás calificarlo aquí.</p>
                @endunless
            </div>
        @endforelse

        @if ($citas->hasPages())<div>{{ $citas->links() }}</div>@endif
    </div>
</x-app-layout>
