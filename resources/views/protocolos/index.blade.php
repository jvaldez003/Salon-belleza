{{-- Protocolos de tratamiento (admin). --}}
<x-app-layout titulo="Protocolos">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Protocolos</h1>
                <p class="text-texto-suave">Las fichas de tratamiento de la página "Protocolos" del sitio.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <x-boton variante="secundario" :href="route('protocolos.publico')" target="_blank" rel="noopener">Ver en el sitio</x-boton>
                <x-boton :href="route('protocolos.create')">Nuevo protocolo</x-boton>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <x-aviso />

        @forelse ($protocolos as $p)
            @php $f = $p->ficha; $foto = $p->medios->firstWhere('tipo', 'imagen'); @endphp
            @if ($loop->first)<ul class="card divide-y divide-linea overflow-hidden">@endif
                <li class="flex flex-wrap items-center gap-4 px-4 sm:px-5 py-4 {{ $p->activo ? '' : 'bg-bruma/60' }}">
                    <x-foto :src="$foto?->url" proporcion="4/5" :inicial="$p->nombre_publico" class="w-14 shrink-0 !rounded-xl" />
                    <div class="flex-1 min-w-[12rem]">
                        <p class="font-semibold text-tinta">
                            {{ $p->nombre_publico }}
                            @unless ($p->activo)<span class="badge-warm ml-2 align-middle">Oculto</span>@endunless
                        </p>
                        <p class="text-sm text-texto-suave">
                            {{ $f['duracion'] ?? 'Sin duración' }} · {{ count($f['pasos']) }} {{ count($f['pasos']) === 1 ? 'paso' : 'pasos' }} · {{ $p->medios_count }} {{ $p->medios_count === 1 ? 'foto o video' : 'fotos y videos' }}
                        </p>
                    </div>
                    <a href="{{ route('protocolos.edit', $p) }}" class="text-sm font-semibold text-oceano hover:underline underline-offset-4">Editar<span class="sr-only"> {{ $p->nombre_publico }}</span></a>
                </li>
            @if ($loop->last)</ul>@endif
        @empty
            <div class="card p-10 grid gap-4 justify-items-start">
                <p class="font-display text-2xl text-tinta">Todavía no hay protocolos.</p>
                <x-boton :href="route('protocolos.create')">Crear el primero</x-boton>
            </div>
        @endforelse
    </div>
</x-app-layout>
