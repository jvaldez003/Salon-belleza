{{-- Reportes por período: cifras, servicios más pedidos, equipo y estado de las citas. Barras en HTML (sin librerías). --}}
@php
    $moneda = fn ($v) => '$' . number_format($v, 0, ',', '.');
    $f = fn ($d) => \Carbon\Carbon::parse($d);
    $mismoAnio = $f($desde)->year === $f($hasta)->year;
    $titulo = $f($desde)->translatedFormat($mismoAnio ? 'j \d\e F' : 'j \d\e F \d\e Y') . ' al ' . $f($hasta)->translatedFormat('j \d\e F \d\e Y');
    $query = ['desde' => $desde, 'hasta' => $hasta];
    $maxServicio = max(1, (float) $porServicio->max('ingresos'));
    $maxEmpleado = max(1, (int) $porEmpleado->max('cantidad'));
    $totalEstados = max(1, $porEstado->sum('total'));
    $coloresEstado = ['pendiente' => 'bg-agua', 'confirmada' => 'bg-oceano', 'completada' => 'bg-emerald-600', 'cancelada' => 'bg-red-400'];
    $topServicios = $porServicio->take(10);
    $restoServicios = $porServicio->slice(10);
@endphp

<x-app-layout titulo="Reportes">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Reportes</h1>
                <p class="text-texto-suave">Del {{ $titulo }}.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <x-boton variante="secundario" :href="route('reportes.csv', $query)">Descargar Excel (CSV)</x-boton>
                <x-boton variante="secundario" :href="route('reportes.pdf', $query)">Descargar PDF</x-boton>
            </div>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-8">
        {{-- Período --}}
        <form method="GET" class="card p-5 grid gap-4" aria-label="Elegir período">
            <div class="flex flex-wrap gap-2">
                @foreach ($atajos as $nombre => [$ini, $fin])
                    @php $activo = $ini->toDateString() === $desde && $fin->toDateString() === $hasta; @endphp
                    <a href="{{ route('reportes.index', ['desde' => $ini->toDateString(), 'hasta' => $fin->toDateString()]) }}"
                       @class(['inline-flex items-center min-h-[40px] px-4 rounded-full border text-sm font-semibold',
                               'bg-oceano border-oceano text-white' => $activo,
                               'bg-white border-linea text-texto hover:border-oceano hover:text-oceano' => ! $activo])
                       @if ($activo) aria-current="true" @endif>{{ $nombre }}</a>
                @endforeach
            </div>
            <div class="flex flex-wrap items-end gap-3">
                <div class="grid gap-1.5">
                    <label for="desde" class="label !mb-0">Desde</label>
                    <input id="desde" type="date" name="desde" value="{{ $desde }}" class="form-input !py-2.5">
                </div>
                <div class="grid gap-1.5">
                    <label for="hasta" class="label !mb-0">Hasta</label>
                    <input id="hasta" type="date" name="hasta" value="{{ $hasta }}" class="form-input !py-2.5">
                </div>
                <button type="submit" class="btn-primary">Ver período</button>
            </div>
        </form>

        {{-- Cifras clave --}}
        <section aria-label="Cifras del período" class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="card p-6 grid gap-2 content-start bg-abismo !border-abismo">
                <span class="text-sm text-sobre-abismo-suave">Realizado</span>
                <span class="font-display text-[clamp(1.7rem,1.2rem+1.6vw,2.4rem)] text-white leading-none tabular">{{ $moneda($resumen['realizado']) }}</span>
                <span class="text-xs text-sobre-abismo-suave">Citas completadas</span>
            </div>
            <div class="card p-6 grid gap-2 content-start">
                <span class="text-sm text-texto-suave">Agendado</span>
                <span class="font-display text-[clamp(1.7rem,1.2rem+1.6vw,2.4rem)] text-tinta leading-none tabular">{{ $moneda($resumen['agendado']) }}</span>
                <span class="text-xs text-texto-suave">Pendientes y confirmadas</span>
            </div>
            <div class="card p-6 grid gap-2 content-start">
                <span class="text-sm text-texto-suave">Citas</span>
                <span class="text-5xl font-semibold tracking-tight text-tinta leading-none tabular">{{ $resumen['citas'] }}</span>
                <span class="text-xs text-texto-suave">{{ $resumen['canceladas'] ? $resumen['canceladas'] . ' canceladas aparte' : 'Ninguna cancelada' }}</span>
            </div>
            <div class="card p-6 grid gap-2 content-start">
                <span class="text-sm text-texto-suave">Clientes</span>
                <span class="text-5xl font-semibold tracking-tight text-tinta leading-none tabular">{{ $resumen['clientes'] }}</span>
                <span class="text-xs text-texto-suave">Personas distintas que reservaron</span>
            </div>
        </section>

        @if ($resumen['citas'] === 0 && $resumen['canceladas'] === 0)
            <div class="card p-8 grid gap-2">
                <p class="font-display text-2xl text-tinta">No hay citas en este período.</p>
                <p class="text-texto-suave">Prueba con otro rango de fechas o con "Este año".</p>
            </div>
        @else
            <div class="grid lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)] gap-6 items-start">
                {{-- Servicios --}}
                <section class="card p-6 md:p-8 grid gap-5" aria-labelledby="t-servicios">
                    <div class="grid gap-1">
                        <h2 id="t-servicios" class="text-2xl">Servicios más vendidos</h2>
                        <p class="text-sm text-texto-suave">Calculado con el precio actual de cada servicio, sin citas canceladas.</p>
                    </div>
                    @if ($porServicio->isEmpty())
                        <p class="text-texto-suave">Sin servicios en este período.</p>
                    @else
                        <ol class="grid gap-3.5">
                            @foreach ($topServicios as $s)
                                @php $nombre = \App\Models\Servicio::make(['nombre' => $s->nombre])->nombre_publico; @endphp
                                <li class="grid gap-1.5" title="{{ $nombre }}: {{ $s->cantidad }} {{ $s->cantidad == 1 ? 'vez' : 'veces' }}, {{ $moneda($s->ingresos) }}">
                                    <div class="flex items-baseline justify-between gap-3">
                                        <span class="min-w-0 truncate text-tinta">{{ $nombre }}</span>
                                        <span class="shrink-0 text-sm text-texto tabular">
                                            <span class="text-texto-suave">{{ $s->cantidad }} ×</span>
                                            <span class="font-semibold text-tinta">{{ $moneda($s->ingresos) }}</span>
                                        </span>
                                    </div>
                                    <div class="h-2.5 rounded-full bg-bruma" aria-hidden="true">
                                        <div class="h-full rounded-full bg-oceano" style="width: {{ max(2, $s->ingresos / $maxServicio * 100) }}%"></div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                        @if ($restoServicios->isNotEmpty())
                            <p class="text-sm text-texto-suave">
                                Otros {{ $restoServicios->count() }} servicios: {{ $restoServicios->sum('cantidad') }} veces, {{ $moneda($restoServicios->sum('ingresos')) }}.
                            </p>
                        @endif
                    @endif
                </section>

                <div class="grid gap-6">
                    {{-- Estados --}}
                    <section class="card p-6 grid gap-4" aria-labelledby="t-estados">
                        <h2 id="t-estados" class="text-2xl">Estado de las citas</h2>
                        <div class="flex h-3 rounded-full overflow-hidden bg-bruma gap-[2px]" role="img"
                             aria-label="{{ $porEstado->map(fn ($e) => $e['nombre'] . ': ' . $e['total'])->implode(', ') }}">
                            @foreach ($porEstado as $clave => $e)
                                @if ($e['total'] > 0)
                                    <span class="{{ $coloresEstado[$clave] }} h-full" style="width: {{ $e['total'] / $totalEstados * 100 }}%" title="{{ $e['nombre'] }}: {{ $e['total'] }}"></span>
                                @endif
                            @endforeach
                        </div>
                        <dl class="grid gap-2.5">
                            @foreach ($porEstado as $clave => $e)
                                <div class="grid grid-cols-[0.75rem_minmax(0,1fr)_auto_3rem] items-center gap-3">
                                    <span class="w-3 h-3 rounded-full {{ $coloresEstado[$clave] }}" aria-hidden="true"></span>
                                    <dt class="text-tinta">{{ $e['nombre'] }}</dt>
                                    <dd class="text-lg font-semibold text-tinta tabular">{{ $e['total'] }}</dd>
                                    <dd class="text-sm text-texto-suave text-right tabular">{{ round($e['total'] / $totalEstados * 100) }}%</dd>
                                </div>
                            @endforeach
                        </dl>
                    </section>

                    {{-- Equipo --}}
                    <section class="card p-6 grid gap-4" aria-labelledby="t-equipo">
                        <div class="grid gap-1">
                            <h2 id="t-equipo" class="text-2xl">Servicios por especialista</h2>
                            <p class="text-sm text-texto-suave">Según a quién eligió el cliente al reservar.</p>
                        </div>
                        @if ($porEmpleado->isEmpty())
                            <p class="text-texto-suave">Sin datos en este período.</p>
                        @else
                            <ol class="grid gap-3">
                                @foreach ($porEmpleado as $e)
                                    <li class="grid gap-1.5">
                                        <div class="flex items-baseline justify-between gap-3">
                                            <span @class(['min-w-0 truncate', 'text-tinta' => $e->nombre !== 'Sin preferencia', 'text-texto-suave italic' => $e->nombre === 'Sin preferencia'])>{{ $e->nombre }}</span>
                                            <span class="text-sm font-semibold text-tinta tabular">{{ $e->cantidad }}</span>
                                        </div>
                                        <div class="h-2 rounded-full bg-bruma" aria-hidden="true">
                                            <div @class(['h-full rounded-full', 'bg-oceano' => $e->nombre !== 'Sin preferencia', 'bg-linea' => $e->nombre === 'Sin preferencia'])
                                                 style="width: {{ max(2, $e->cantidad / $maxEmpleado * 100) }}%"></div>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </section>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
