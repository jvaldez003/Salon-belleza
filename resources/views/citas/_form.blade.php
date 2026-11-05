{{--
    Formulario de reserva de cita (crear y editar), sistema Arrecife.
    Pasos: 1 servicios → 2 día y hora → 3 notas, con resumen fijo (lateral en escritorio, barra inferior en celular).

    Envía exactamente los mismos campos que antes, así el controlador no cambia:
      servicios[]          ids de servicio
      empleados[<id>]      especialista elegido por servicio ('' = sin preferencia)
      fecha (Y-m-d), hora (H:i), notas, estado (solo equipo)

    Variables: $cita (o null), $action, $method, $servicios, $categorias, opcional $fechaInicial.
--}}
@php
    $esEquipo = auth()->user()->isAdmin() || auth()->user()->isEditor();

    // ── Selección previa (old() tras un error de validación, o la cita en edición) ──
    $seleccionIds = collect(old('servicios', $cita ? $cita->servicios->pluck('id') : []))
        ->map(fn ($id) => (string) $id)->values()->all();

    $especialistasSel = [];
    if ($cita) {
        foreach ($cita->servicios as $s) {
            if ($s->pivot->empleado_id) $especialistasSel[(string) $s->id] = (string) $s->pivot->empleado_id;
        }
    }
    foreach (old('empleados', []) as $sid => $eid) {
        if ($eid) $especialistasSel[(string) $sid] = (string) $eid;
    }

    // ── Días: próximos 60, marcando los que el salón atiende (tabla horarios) ──
    $diasAbiertos = \App\Models\Horario::where('activo', true)->pluck('dia')->map(fn ($d) => (int) $d)->all()
        ?: config('salon.dias_laborales', [1, 2, 3, 4, 5, 6]);
    $nombresDia = [1 => 'lun', 2 => 'mar', 3 => 'mié', 4 => 'jue', 5 => 'vie', 6 => 'sáb', 7 => 'dom'];
    $nombresMes = [1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

    $dias = collect(range(0, 59))->map(function ($i) use ($diasAbiertos, $nombresDia, $nombresMes) {
        $d = now()->startOfDay()->addDays($i);
        return [
            'fecha'   => $d->format('Y-m-d'),
            'dia'     => $nombresDia[$d->dayOfWeekIso],
            'numero'  => $d->day,
            'mes'     => $nombresMes[$d->month],
            'abierto' => in_array($d->dayOfWeekIso, $diasAbiertos, true),
            'hoy'     => $i === 0,
        ];
    });

    $fechaInicial = old('fecha', $fechaInicial ?? ($cita?->fecha?->format('Y-m-d')));
    // Si la cita en edición está fuera de la tira de días, se agrega al inicio
    if ($fechaInicial && ! $dias->contains('fecha', $fechaInicial)) {
        $d = \Carbon\Carbon::parse($fechaInicial);
        $dias->prepend(['fecha' => $d->format('Y-m-d'), 'dia' => $nombresDia[$d->dayOfWeekIso], 'numero' => $d->day, 'mes' => $nombresMes[$d->month], 'abierto' => true, 'hoy' => false]);
    }
    if (! $fechaInicial || ! optional($dias->firstWhere('fecha', $fechaInicial))['abierto']) {
        $fechaInicial = optional($dias->firstWhere('abierto', true))['fecha'];
    }
    $horaInicial = old('hora', $cita && $cita->hora ? substr($cita->hora, 0, 5) : '');

    // ── Catálogo para el resumen (id → datos) ──
    $catalogo = $servicios->mapWithKeys(fn ($s) => [(string) $s->id => [
        'nombre'   => $s->nombre_publico,
        'precio'   => (float) $s->precio,
        'desde'    => $s->precio_desde,
        'duracion' => (int) $s->duracion,
        'cat'      => $s->categoria_id ? (string) $s->categoria_id : 'otros',
        'buscar'   => \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii($s->nombre_publico . ' ' . $s->descripcion)),
    ]]);

    $especialistas = $servicios->flatMap(fn ($s) => $s->empleados->where('activo', true))->unique('id')
        ->mapWithKeys(fn ($e) => [(string) $e->id => \Illuminate\Support\Str::of($e->nombre)->explode(' ')->first()]);

    // ── Grupos por categoría (solo los que tienen servicios) ──
    $categorias = $categorias ?? collect();
    $grupos = $categorias
        ->map(fn ($c) => ['id' => (string) $c->id, 'nombre' => Str::legible($c->nombre), 'servicios' => $servicios->where('categoria_id', $c->id)->sortBy('nombre')])
        ->filter(fn ($g) => $g['servicios']->isNotEmpty())->values();
    $sinCat = $servicios->whereNull('categoria_id');
    if ($sinCat->isNotEmpty()) $grupos->push(['id' => 'otros', 'nombre' => 'Otros', 'servicios' => $sinCat]);

    // La lista abre filtrada: en la categoría que ya tiene servicios elegidos o en la primera
    $catInicial = optional($grupos->first(fn ($g) => $g['servicios']->pluck('id')->map(fn ($i) => (string) $i)->intersect($seleccionIds)->isNotEmpty())
        ?? $grupos->first())['id'] ?? 'todas';
    if ($grupos->count() < 2) $catInicial = 'todas';

    $cancelarUrl = $cita ? route('citas.show', $cita) : ($esEquipo ? route('citas.index') : url('/'));
@endphp

<form method="POST" action="{{ $action }}" novalidate
      x-data="reservaArrecife({
          catalogo: @js($catalogo),
          especialistas: @js($especialistas),
          seleccion: @js($seleccionIds),
          especialistasSel: @js((object) $especialistasSel),
          fecha: @js($fechaInicial),
          hora: @js($horaInicial),
          citaId: @js($cita?->id),
          cat: @js($catInicial),
          urlHorarios: @js(route('citas.horarios.json')),
      })"
      @submit="enviar($event)"
      class="grid lg:grid-cols-[minmax(0,1fr)_340px] gap-10 lg:gap-14 items-start pb-32 lg:pb-0">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif
    <input type="hidden" name="fecha" :value="fecha">
    <input type="hidden" name="hora" :value="hora">

    <div class="grid gap-14 min-w-0">

        @if ($errors->any())
            <div class="alert-error" role="alert">
                <div>
                    <p class="font-semibold">Revisa estos datos para completar la reserva:</p>
                    <ul class="mt-1 list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ═════════ PASO 1: SERVICIOS ═════════ --}}
        <section id="paso-servicios" class="scroll-mt-28 grid gap-6" aria-labelledby="t-paso-1">
            <header class="flex items-center gap-4">
                <span class="w-10 h-10 rounded-full grid place-items-center font-display text-lg bg-agua text-abismo shrink-0" aria-hidden="true">1</span>
                <h2 id="t-paso-1" class="text-[1.9rem] leading-none">Elige tus servicios</h2>
            </header>

            <div class="grid gap-3">
                <label for="buscar-servicio" class="sr-only">Buscar servicio</label>
                <input id="buscar-servicio" type="search" x-model="busqueda" placeholder="Buscar un servicio, por ejemplo: cejas" class="form-input" autocomplete="off">

                @if ($grupos->count() > 1)
                    <div class="flex gap-2 overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0 pb-1" role="group" aria-label="Filtrar por categoría">
                        <button type="button" @click="cat = 'todas'" :aria-pressed="(cat === 'todas').toString()"
                                class="shrink-0 px-4 py-2 rounded-full border text-[0.9375rem] font-medium"
                                :class="cat === 'todas' ? 'bg-oceano border-oceano text-white' : 'border-linea text-tinta hover:border-oceano'">Todos</button>
                        @foreach ($grupos as $g)
                            <button type="button" @click="cat = '{{ $g['id'] }}'" :aria-pressed="(cat === '{{ $g['id'] }}').toString()"
                                    class="shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full border text-[0.9375rem] font-medium"
                                    :class="cat === '{{ $g['id'] }}' ? 'bg-oceano border-oceano text-white' : 'border-linea text-tinta hover:border-oceano'">
                                {{ $g['nombre'] }}
                                <span x-show="cuentaEn('{{ $g['id'] }}') > 0" x-cloak x-text="cuentaEn('{{ $g['id'] }}')"
                                      class="min-w-[1.25rem] h-5 px-1 rounded-full text-xs grid place-items-center"
                                      :class="cat === '{{ $g['id'] }}' ? 'bg-white text-oceano' : 'bg-oceano text-white'"></span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                @foreach ($grupos as $g)
                    <div x-show="grupoVisible('{{ $g['id'] }}')">
                        <h3 class="font-display italic text-lg text-oceano pt-4 pb-2" x-show="cat === 'todas'">{{ $g['nombre'] }}</h3>
                        <ul class="border-t border-linea">
                            @foreach ($g['servicios'] as $s)
                                @php $sid = (string) $s->id; $equipo = $s->empleados->where('activo', true); @endphp
                                <li x-show="visible('{{ $sid }}')" class="border-b border-linea">
                                    <label class="flex items-start gap-4 py-3 cursor-pointer group">
                                        <input type="checkbox" name="servicios[]" value="{{ $sid }}" x-model="seleccion"
                                               @change="cambioServicio('{{ $sid }}', $event.target.checked)" class="peer sr-only">
                                        <span class="mt-0.5 w-6 h-6 rounded-full border-[1.5px] grid place-items-center shrink-0 transition-colors peer-focus-visible:ring-2 peer-focus-visible:ring-laguna peer-focus-visible:ring-offset-2"
                                              :class="seleccion.includes('{{ $sid }}') ? 'bg-oceano border-oceano text-white' : 'border-linea text-transparent group-hover:border-oceano'" aria-hidden="true">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                        <span class="flex-1 min-w-0 grid grid-cols-[minmax(0,1fr)_auto] gap-x-4 gap-y-1">
                                            <span class="font-semibold text-tinta leading-snug">{{ $s->nombre_publico }}</span>
                                            <x-precio :valor="$s->precio" :desde="$s->precio_desde" class="text-right text-lg" />
                                            <span class="col-span-2 text-sm text-texto-suave truncate">
                                                {{ $s->duracion_texto }}@if ($s->descripcion), {{ Str::legible($s->descripcion) }}@endif
                                            </span>
                                        </span>
                                    </label>

                                    @if ($equipo->isNotEmpty())
                                        <div x-show="seleccion.includes('{{ $sid }}')" x-cloak class="pl-10 pb-4 -mt-1">
                                            <p class="text-sm text-texto-suave mb-2" id="esp-{{ $sid }}">¿Con quién prefieres?</p>
                                            <div class="flex flex-wrap gap-2" role="radiogroup" aria-labelledby="esp-{{ $sid }}">
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="empleados[{{ $sid }}]" value="" class="peer sr-only"
                                                           @checked(empty($especialistasSel[$sid])) @change="cambioEspecialista('{{ $sid }}', '')">
                                                    <span class="inline-block px-3.5 py-1.5 rounded-full border border-linea text-sm peer-checked:bg-bruma peer-checked:border-oceano peer-checked:text-oceano peer-focus-visible:ring-2 peer-focus-visible:ring-laguna">Sin preferencia</span>
                                                </label>
                                                @foreach ($equipo as $emp)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="empleados[{{ $sid }}]" value="{{ $emp->id }}" class="peer sr-only"
                                                               @checked(($especialistasSel[$sid] ?? null) == $emp->id) @change="cambioEspecialista('{{ $sid }}', '{{ $emp->id }}')">
                                                        <span class="inline-flex items-center gap-2 pl-1 pr-3.5 py-1 rounded-full border border-linea text-sm peer-checked:bg-bruma peer-checked:border-oceano peer-checked:text-oceano peer-focus-visible:ring-2 peer-focus-visible:ring-laguna">
                                                            @if ($emp->foto)
                                                                <img src="{{ asset('storage/' . $emp->foto) }}" alt="" class="w-6 h-6 rounded-full object-cover">
                                                            @else
                                                                <span class="w-6 h-6 rounded-full bg-bruma-honda grid place-items-center font-display text-xs text-oceano" aria-hidden="true">{{ mb_substr($emp->nombre, 0, 1) }}</span>
                                                            @endif
                                                            {{ $emp->nombre }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
                <p x-show="sinResultados" x-cloak class="py-8 text-texto-suave">
                    No encontramos servicios con "<span x-text="busqueda"></span>". Prueba con otra palabra o elige "Todos".
                </p>
            </div>
        </section>

        {{-- ═════════ PASO 2: DÍA Y HORA ═════════ --}}
        <section id="paso-fecha" class="scroll-mt-28 grid gap-6" aria-labelledby="t-paso-2">
            <header class="flex items-center gap-4">
                <span class="w-10 h-10 rounded-full grid place-items-center font-display text-lg bg-laguna text-white shrink-0" aria-hidden="true">2</span>
                <h2 id="t-paso-2" class="text-[1.9rem] leading-none">Elige el día y la hora</h2>
            </header>

            <div class="grid gap-3">
                <p class="font-display italic text-lg text-oceano first-letter:uppercase" x-text="mesVisible"></p>
                <div class="flex gap-2 overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0 pb-2 snap-x" role="group" aria-label="Días disponibles" x-ref="dias">
                    @foreach ($dias as $d)
                        <button type="button" data-fecha="{{ $d['fecha'] }}"
                                @if ($d['abierto']) @click="elegirFecha('{{ $d['fecha'] }}')" @else disabled @endif
                                :aria-pressed="(fecha === '{{ $d['fecha'] }}').toString()"
                                aria-label="{{ $d['hoy'] ? 'Hoy, ' : '' }}{{ $d['dia'] }} {{ $d['numero'] }} de {{ $d['mes'] }}{{ $d['abierto'] ? '' : ', cerrado' }}"
                                class="snap-start shrink-0 w-[4.25rem] py-3 rounded-2xl border grid justify-items-center gap-0.5 disabled:opacity-40 disabled:cursor-not-allowed"
                                :class="fecha === '{{ $d['fecha'] }}' ? 'bg-oceano border-oceano text-white' : 'border-linea text-tinta {{ $d['abierto'] ? 'hover:border-oceano' : '' }}'">
                            <span class="text-xs">{{ $d['hoy'] ? 'hoy' : $d['dia'] }}</span>
                            <span class="font-display text-2xl leading-none tabular">{{ $d['numero'] }}</span>
                            <span class="text-[0.7rem] opacity-80">{{ $d['abierto'] ? mb_substr($d['mes'], 0, 3) : 'cerrado' }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="grid gap-4" aria-live="polite">
                <p x-show="cargando" class="text-texto-suave">Buscando horas libres…</p>
                <p x-show="!cargando && mensaje" x-cloak x-text="mensaje" class="text-texto-suave"></p>

                <template x-for="bloque in bloquesHoras" :key="bloque.nombre">
                    <div x-show="!cargando && bloque.horas.length" class="grid gap-2">
                        <p class="text-sm font-semibold text-texto" x-text="bloque.nombre"></p>
                        <div class="grid grid-cols-3 sm:grid-cols-4 xl:grid-cols-5 gap-2">
                            <template x-for="h in bloque.horas" :key="h">
                                <button type="button" @click="hora = h" :aria-pressed="(hora === h).toString()"
                                        class="py-2.5 rounded-xl border text-[0.9375rem] font-medium tabular"
                                        :class="hora === h ? 'bg-oceano border-oceano text-white' : 'border-linea text-tinta hover:border-oceano bg-white'"
                                        x-text="hora12(h)"></button>
                            </template>
                        </div>
                    </div>
                </template>
                @error('hora')<p class="text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
        </section>

        {{-- ═════════ PASO 3: NOTAS ═════════ --}}
        <section class="grid gap-6" aria-labelledby="t-paso-3">
            <header class="flex items-center gap-4">
                <span class="w-10 h-10 rounded-full grid place-items-center font-display text-lg bg-abismo text-white shrink-0" aria-hidden="true">3</span>
                <h2 id="t-paso-3" class="text-[1.9rem] leading-none">Algo que debamos saber</h2>
            </header>
            <div class="grid gap-2">
                <label for="notas" class="label">Notas para el equipo <span class="font-normal text-texto-suave">(opcional)</span></label>
                <textarea id="notas" name="notas" rows="3" maxlength="500" class="form-input"
                          placeholder="Por ejemplo: tengo el cabello muy largo, o soy alérgica a algún producto.">{{ old('notas', $cita?->notas) }}</textarea>
            </div>

            @if ($esEquipo)
                <div class="grid gap-2 max-w-xs">
                    <label for="estado" class="label">Estado de la cita</label>
                    <select id="estado" name="estado" class="form-input">
                        @foreach (['pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'completada' => 'Completada', 'cancelada' => 'Cancelada'] as $valor => $texto)
                            <option value="{{ $valor }}" @selected(old('estado', $cita?->estado ?? 'pendiente') === $valor)>{{ $texto }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </section>
    </div>

    {{-- ═════════ RESUMEN (escritorio) ═════════ --}}
    <aside class="hidden lg:block sticky top-24" aria-label="Resumen de tu reserva">
        <div class="rounded-2xl border border-linea bg-white p-6 grid gap-5">
            <h2 class="font-display text-2xl">Tu reserva</h2>

            <div>
                <p x-show="!seleccion.length" class="text-sm text-texto-suave">Todavía no has elegido servicios.</p>
                <ul class="grid gap-3" x-show="seleccion.length" x-cloak>
                    <template x-for="id in seleccion" :key="id">
                        <li class="grid grid-cols-[minmax(0,1fr)_auto] gap-3 text-[0.9375rem]">
                            <span>
                                <span class="text-tinta font-medium" x-text="catalogo[id]?.nombre"></span>
                                <span class="block text-xs text-texto-suave" x-show="especialistasSel[id]" x-text="'Con ' + especialistas[especialistasSel[id]]"></span>
                            </span>
                            <span class="font-display text-oceano tabular" x-text="precio(catalogo[id]?.precio)"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <dl class="grid gap-2 border-t border-linea pt-4 text-[0.9375rem]">
                <div class="flex justify-between gap-4"><dt class="text-texto-suave">Día y hora</dt><dd class="text-right text-tinta" x-text="fechaTexto || 'Sin elegir'"></dd></div>
                <div class="flex justify-between gap-4"><dt class="text-texto-suave">Duración aprox.</dt><dd class="text-tinta" x-text="duracionTexto"></dd></div>
                <div class="flex justify-between gap-4 items-baseline pt-2">
                    <dt class="font-semibold text-tinta">Total<span x-show="hayDesde" x-cloak class="font-normal text-texto-suave"> desde</span></dt>
                    <dd class="font-display text-3xl text-oceano tabular" x-text="precio(total)"></dd>
                </div>
            </dl>

            <p x-show="falta" x-cloak x-text="falta" class="text-sm text-red-700" role="alert"></p>
            <button type="submit" class="btn-primary w-full">{{ $cita ? 'Guardar cambios' : 'Confirmar reserva' }}</button>
            <a href="{{ $cancelarUrl }}" class="text-center text-sm text-texto-suave hover:text-oceano">Cancelar</a>
        </div>
        <p class="mt-4 text-xs text-texto-suave leading-relaxed">El precio final puede variar según el caso; te lo confirmamos antes del servicio.</p>
    </aside>

    {{-- ═════════ RESUMEN (barra inferior en celular) ═════════ --}}
    <div class="lg:hidden fixed inset-x-0 bottom-0 z-40 bg-superficie/95 backdrop-blur border-t border-linea pb-[env(safe-area-inset-bottom)]">
        <div class="max-w-arrecife mx-auto px-4 py-3 flex items-center gap-4">
            <div class="flex-1 min-w-0">
                <p class="text-sm text-tinta truncate">
                    <span x-text="seleccion.length ? seleccion.length + (seleccion.length === 1 ? ' servicio' : ' servicios') : 'Elige tus servicios'"></span>
                    <span x-show="seleccion.length" class="font-display text-oceano tabular" x-text="' · ' + precio(total)"></span>
                </p>
                <p class="text-xs truncate" :class="falta ? 'text-red-700' : 'text-texto-suave'" x-text="falta || fechaTexto || 'Sin día ni hora'"></p>
            </div>
            <button type="submit" class="btn-primary shrink-0">{{ $cita ? 'Guardar' : 'Confirmar' }}</button>
        </div>
    </div>
</form>

<script>
function reservaArrecife(cfg) {
    const quitarTildes = (t) => (t || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
    const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    const diasSemana = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
    const moneda = new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 });

    return {
        ...cfg,
        busqueda: '',
        slots: [],
        cargando: false,
        mensaje: '',
        falta: '',

        init() {
            // Asegura que los ids lleguen como texto (los checkboxes usan texto)
            this.seleccion = this.seleccion.map(String);
            this.cargarHorarios();
            this.$nextTick(() => this.centrarDia());
            this.$watch('seleccion', () => { if (this.seleccion.length) this.falta = ''; });
            this.$watch('hora', () => { if (this.hora) this.falta = ''; });
        },

        // ── Servicios ──
        visible(id) {
            const s = this.catalogo[id];
            if (!s) return false;
            if (this.cat !== 'todas' && s.cat !== this.cat) return false;
            const q = quitarTildes(this.busqueda.trim());
            return !q || s.buscar.includes(q);
        },
        grupoVisible(cat) {
            return Object.keys(this.catalogo).some((id) => this.catalogo[id].cat === cat && this.visible(id));
        },
        get sinResultados() {
            return !Object.keys(this.catalogo).some((id) => this.visible(id));
        },
        cuentaEn(cat) {
            return this.seleccion.filter((id) => this.catalogo[id]?.cat === cat).length;
        },
        cambioServicio(id, marcado) {
            if (!marcado && this.especialistasSel[id]) {
                delete this.especialistasSel[id];
                this.cargarHorarios();
            }
        },
        cambioEspecialista(id, empleado) {
            if (empleado) this.especialistasSel[id] = empleado; else delete this.especialistasSel[id];
            this.cargarHorarios();
        },

        // ── Totales ──
        get total() {
            return this.seleccion.reduce((t, id) => t + (this.catalogo[id]?.precio || 0), 0);
        },
        get hayDesde() {
            return this.seleccion.some((id) => this.catalogo[id]?.desde);
        },
        get duracionTexto() {
            const min = this.seleccion.reduce((t, id) => t + (this.catalogo[id]?.duracion || 0), 0);
            if (!min) return '—';
            const h = Math.floor(min / 60), m = min % 60;
            return h ? (m ? `${h} h ${m} min` : `${h} h`) : `${m} min`;
        },
        precio(v) {
            return '$' + moneda.format(Math.round(v || 0));
        },

        // ── Día y hora ──
        get mesVisible() {
            if (!this.fecha) return '';
            const [y, m] = this.fecha.split('-').map(Number);
            return `${meses[m - 1]} ${y}`;
        },
        get fechaTexto() {
            if (!this.fecha || !this.hora) return '';
            const [y, m, d] = this.fecha.split('-').map(Number);
            const fecha = new Date(y, m - 1, d);
            return `${diasSemana[fecha.getDay()]} ${d} de ${meses[m - 1]}, ${this.hora12(this.hora)}`;
        },
        get bloquesHoras() {
            return [
                { nombre: 'Mañana', horas: this.slots.filter((h) => h < '12:00') },
                { nombre: 'Tarde', horas: this.slots.filter((h) => h >= '12:00') },
            ];
        },
        hora12(h) {
            const [hh, mm] = h.split(':').map(Number);
            const sufijo = hh < 12 ? 'a. m.' : 'p. m.';
            return `${((hh + 11) % 12) + 1}:${String(mm).padStart(2, '0')} ${sufijo}`;
        },
        elegirFecha(f) {
            if (this.fecha === f) return;
            this.fecha = f;
            this.hora = '';
            this.cargarHorarios();
        },
        centrarDia() {
            const b = this.$refs.dias?.querySelector(`[data-fecha="${this.fecha}"]`);
            if (b) this.$refs.dias.scrollLeft = b.offsetLeft - this.$refs.dias.offsetLeft - 16;
        },
        async cargarHorarios() {
            if (!this.fecha) return;
            this.cargando = true;
            this.mensaje = '';
            const params = new URLSearchParams({ fecha: this.fecha });
            if (this.citaId) params.append('cita_id', this.citaId);
            Object.entries(this.especialistasSel).forEach(([sid, eid]) => { if (eid) params.append(`empleados[${sid}]`, eid); });

            try {
                const res = await fetch(`${this.urlHorarios}?${params}`, { headers: { Accept: 'application/json' } });
                if (!res.ok) throw new Error();
                const data = await res.json();
                this.slots = data.slots || [];
                this.mensaje = data.mensaje || '';
                if (this.hora && !this.slots.includes(this.hora)) this.hora = '';
            } catch (e) {
                this.slots = [];
                this.mensaje = 'No pudimos cargar las horas. Revisa tu conexión y vuelve a elegir el día.';
            } finally {
                this.cargando = false;
            }
        },

        // ── Envío ──
        enviar(e) {
            const destino = !this.seleccion.length ? 'paso-servicios' : (!this.fecha || !this.hora ? 'paso-fecha' : null);
            if (!destino) return;
            e.preventDefault();
            this.falta = destino === 'paso-servicios' ? 'Elige al menos un servicio.' : 'Elige el día y la hora de tu cita.';
            document.getElementById(destino)?.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
        },
    };
}
</script>
