{{-- Estadísticas generales (admin). Las cifras son el contenido principal de esta página. --}}
@php
    $moneda = fn ($v) => '$' . number_format($v, 0, ',', '.');
    $mes = Str::ucfirst(now()->translatedFormat('F'));
    $roles = [
        'Administradores'    => [$totalAdmins, 'bg-abismo'],
        'Recepción'          => [$totalEditores, 'bg-oceano'],
        'Clientes'           => [$totalUsuariosReg, 'bg-agua'],
    ];
@endphp

<x-app-layout titulo="Estadísticas">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Estadísticas</h1>
                <p class="text-texto-suave">Datos al {{ now()->translatedFormat('j \d\e F') }}, {{ now()->format('g:i') }} {{ now()->hour < 12 ? 'a. m.' : 'p. m.' }}</p>
            </div>
            <x-boton variante="secundario" :href="route('reportes.index')">Ver reportes</x-boton>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-8">
        {{-- Cifras clave --}}
        <section aria-label="Cifras clave" class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('citas.agenda') }}" class="card-hover p-6 grid gap-2">
                <span class="text-sm text-texto-suave">Citas activas hoy</span>
                <span class="text-5xl font-semibold tracking-tight text-tinta leading-none tabular">{{ $citasHoy }}</span>
                <span class="text-sm font-semibold text-oceano">Ver agenda</span>
            </a>
            <div class="card p-6 grid gap-2 bg-abismo !border-abismo">
                <span class="text-sm text-sobre-abismo-suave">Ingresos de {{ $mes }}</span>
                <span class="font-display text-[clamp(1.8rem,1.2rem+1.8vw,2.6rem)] text-white leading-none tabular">{{ $moneda($ingresosMes) }}</span>
                <span class="text-xs text-sobre-abismo-suave">Sin contar citas canceladas</span>
            </div>
            <a href="{{ route('servicios.index') }}" class="card-hover p-6 grid gap-2">
                <span class="text-sm text-texto-suave">Servicios en el catálogo</span>
                <span class="text-5xl font-semibold tracking-tight text-tinta leading-none tabular">{{ $totalServicios }}</span>
                <span class="text-sm font-semibold text-oceano">Ver servicios</span>
            </a>
            <a href="{{ route('usuario.index') }}" class="card-hover p-6 grid gap-2">
                <span class="text-sm text-texto-suave">Personas registradas</span>
                <span class="text-5xl font-semibold tracking-tight text-tinta leading-none tabular">{{ $totalUsuarios }}</span>
                <span class="text-sm font-semibold text-oceano">Ver usuarios</span>
            </a>
        </section>

        {{-- Distribución por rol --}}
        <section class="card p-6 md:p-8 grid gap-6 max-w-3xl" aria-labelledby="t-roles">
            <div class="flex flex-wrap items-baseline justify-between gap-2">
                <h2 id="t-roles" class="text-2xl">Usuarios por rol</h2>
                <p class="text-sm text-texto-suave">{{ $totalUsuarios }} en total</p>
            </div>

            {{-- Barra apilada --}}
            <div class="flex h-3 rounded-full overflow-hidden bg-bruma" role="img"
                 aria-label="{{ collect($roles)->map(fn ($r, $n) => $n . ': ' . $r[0])->implode(', ') }}">
                @foreach ($roles as $nombre => [$cantidad, $color])
                    @if ($cantidad > 0)
                        <span class="{{ $color }} h-full" style="width: {{ $totalUsuarios ? $cantidad / $totalUsuarios * 100 : 0 }}%"></span>
                    @endif
                @endforeach
            </div>

            <dl class="grid gap-3">
                @foreach ($roles as $nombre => [$cantidad, $color])
                    @php $pct = $totalUsuarios ? round($cantidad / $totalUsuarios * 100) : 0; @endphp
                    <div class="grid grid-cols-[1rem_minmax(0,1fr)_auto_3.5rem] items-center gap-3">
                        <span class="w-3 h-3 rounded-full {{ $color }}" aria-hidden="true"></span>
                        <dt class="text-tinta">{{ $nombre }}</dt>
                        <dd class="text-lg font-semibold text-tinta tabular">{{ $cantidad }}</dd>
                        <dd class="text-sm text-texto-suave text-right tabular">{{ $pct }}%</dd>
                    </div>
                @endforeach
            </dl>
        </section>
    </div>
</x-app-layout>
