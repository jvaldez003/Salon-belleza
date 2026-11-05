{{--
    Inicio del panel.
    Clienta: su próxima cita destacada y accesos a reservar, sus citas e historial.
    Equipo:  la agenda de hoy y accesos rápidos (el admin además ve atajos de gestión).
--}}
@php
    $usuario  = Auth::user();
    $esEquipo = $usuario->isAdmin() || $usuario->isEditor();
    $nombre   = Str::of($usuario->name)->explode(' ')->first();
    $saludo   = now()->hour < 12 ? 'Buenos días' : (now()->hour < 19 ? 'Buenas tardes' : 'Buenas noches');
@endphp

<x-app-layout titulo="Inicio">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <p class="text-texto-suave">{{ $saludo }},</p>
                <h1 class="text-[clamp(2.2rem,1.6rem+2.4vw,3.4rem)] leading-none">{{ $nombre }}</h1>
            </div>
            <x-boton :href="route('citas.create')">{{ $esEquipo ? 'Nueva cita' : 'Reservar cita' }}</x-boton>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-10">
        <x-aviso />
        @if (request('verified'))
            <div class="alert-success" role="status">Tu correo quedó confirmado. Te damos la bienvenida a Arrecife.</div>
        @endif

        @if ($esEquipo)
            {{-- ═════════ EQUIPO: agenda de hoy ═════════ --}}
            <section aria-labelledby="t-hoy" class="grid lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)] gap-8 items-start">
                <div class="card overflow-hidden">
                    <div class="flex flex-wrap items-baseline justify-between gap-3 px-6 pt-6 pb-4">
                        <h2 id="t-hoy" class="text-2xl">Hoy, {{ now()->translatedFormat('l j \d\e F') }}</h2>
                        <a href="{{ route('citas.agenda') }}" class="text-sm font-semibold text-oceano hover:underline underline-offset-4">Ver agenda completa</a>
                    </div>
                    @if ($agendaHoy->isEmpty())
                        <p class="px-6 pb-8 text-texto-suave">No hay citas para hoy. Cuando alguien reserve, aparecerá aquí.</p>
                    @else
                        <ol class="border-t border-linea">
                            @foreach ($agendaHoy as $c)
                                <li class="border-b border-linea last:border-0">
                                    <a href="{{ route('citas.show', $c) }}" class="grid grid-cols-[5.5rem_minmax(0,1fr)_auto] gap-4 items-center px-6 py-4 hover:bg-bruma transition-colors">
                                        <span class="font-display text-xl text-oceano tabular">{{ $c->hora_texto }}</span>
                                        <span class="min-w-0">
                                            <span class="block font-semibold text-tinta truncate">{{ $c->usuario?->name ?? 'Sin cliente' }}</span>
                                            <span class="block text-sm text-texto-suave truncate">{{ $c->servicios_texto }}</span>
                                        </span>
                                        <x-estado-cita :estado="$c->estado" />
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>

                <div class="grid gap-4">
                    <div class="card p-6 grid gap-1">
                        <p class="text-sm text-texto-suave">Citas activas hoy</p>
                        <p class="font-display text-5xl text-tinta leading-none tabular">{{ $citasHoy }}</p>
                    </div>
                    <nav aria-label="Accesos rápidos" class="card p-2 grid">
                        @php
                            $atajos = [['Calendario del mes', route('citas.calendario')], ['Todas las citas', route('citas.index')]];
                            if ($usuario->isAdmin()) {
                                $atajos[] = ['Servicios y precios', route('servicios.index')];
                                $atajos[] = ['Banners del inicio', route('banners.index')];
                                $atajos[] = ['Estadísticas', route('admin.index')];
                            }
                        @endphp
                        @foreach ($atajos as [$texto, $url])
                            <a href="{{ $url }}" class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-tinta hover:bg-bruma transition-colors">
                                {{ $texto }}
                                <svg class="w-4 h-4 text-texto-suave" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </section>
        @else
            {{-- ═════════ CLIENTA: su próxima cita ═════════ --}}
            @php $proxima = $proximasCitas->first(); $siguientes = $proximasCitas->slice(1); @endphp

            <section aria-labelledby="t-proxima">
                <h2 id="t-proxima" class="sr-only">Tu próxima cita</h2>
                @if ($proxima)
                    <div class="rounded-2xl bg-abismo text-sobre-abismo-suave overflow-hidden grid md:grid-cols-[minmax(0,1fr)_auto]">
                        <div class="p-6 md:p-10 grid gap-4">
                            <p class="font-display italic text-lg text-agua">Tu próxima cita</p>
                            <p class="font-display text-white text-[clamp(1.9rem,1.4rem+2vw,3rem)] leading-tight first-letter:uppercase">{{ $proxima->fecha_larga }}</p>
                            <p class="text-lg text-sobre-abismo">{{ $proxima->hora_texto }} · {{ $proxima->servicios_texto }}</p>
                            <div class="flex flex-wrap items-center gap-3 pt-2">
                                <x-estado-cita :estado="$proxima->estado" class="!bg-white/10 !text-white" />
                                <span class="text-sm">Total {{ '$' . number_format($proxima->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="flex md:flex-col gap-3 p-6 md:p-10 md:justify-center border-t md:border-t-0 md:border-l border-white/10">
                            <x-boton variante="claro" :href="route('citas.show', $proxima)">Ver detalle</x-boton>
                            <a href="{{ route('citas.edit', $proxima) }}" class="btn border-sobre-abismo-suave/40 text-sobre-abismo hover:bg-white/10">Cambiar</a>
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl bg-abismo text-sobre-abismo-suave p-6 md:p-10 grid gap-4">
                        <p class="font-display text-white text-[clamp(1.9rem,1.4rem+2vw,3rem)] leading-tight">No tienes citas próximas</p>
                        <p class="text-lg max-w-[44ch]">Elige tus servicios, el día y la hora. Te confirmamos por WhatsApp.</p>
                        <div><x-boton variante="claro" :href="route('citas.create')">Reservar cita</x-boton></div>
                    </div>
                @endif
            </section>

            @if ($siguientes->isNotEmpty())
                <section aria-labelledby="t-siguientes" class="grid gap-4">
                    <h2 id="t-siguientes" class="text-2xl">Después</h2>
                    <ul class="card divide-y divide-linea">
                        @foreach ($siguientes as $c)
                            <li>
                                <a href="{{ route('citas.show', $c) }}" class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 hover:bg-bruma transition-colors">
                                    <span>
                                        <span class="block font-semibold text-tinta first-letter:uppercase">{{ $c->fecha_larga }}, {{ $c->hora_texto }}</span>
                                        <span class="block text-sm text-texto-suave">{{ $c->servicios_texto }}</span>
                                    </span>
                                    <x-estado-cita :estado="$c->estado" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <nav aria-label="Accesos rápidos" class="grid sm:grid-cols-3 gap-4">
                @foreach ([
                    ['Mis citas', 'Consulta, cambia o cancela tus reservas.', route('citas.index')],
                    ['Historial y reseñas', 'Tus servicios anteriores. Cuéntanos cómo te fue.', route('resenas.historial')],
                    ['Servicios y precios', 'Mira todo lo que hacemos en el estudio.', url('/') . '#servicios'],
                ] as [$titulo, $texto, $url])
                    <a href="{{ $url }}" class="card-hover p-6 grid gap-1.5">
                        <span class="font-display text-xl text-tinta">{{ $titulo }}</span>
                        <span class="text-sm text-texto-suave">{{ $texto }}</span>
                    </a>
                @endforeach
            </nav>
        @endif
    </div>
</x-app-layout>
