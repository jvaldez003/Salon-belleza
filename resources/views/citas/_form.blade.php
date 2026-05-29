@php
    $jsfecha = $fechaInicial ?? old('fecha', ($cita && $cita->fecha ? $cita->fecha->format('Y-m-d') : now()->format('Y-m-d')));
    $jsHora = old('hora', ($cita && $cita->hora ? substr($cita->hora, 0, 5) : ''));
    $jsCitaId = $cita ? $cita->id : null;

    // IDs de servicios seleccionados (strings para compatibilidad con Alpine x-model)
    $citaServiciosIds = collect(old('servicios', $cita ? $cita->servicios->pluck('id') : []))
        ->map(fn($id) => (string)$id)->values()->toArray();

    // Mapa { "servicio_id" => "empleado_id" } para pre-seleccionar empleado en edición
    $citaEmpleadosMap = [];
    if ($cita && $cita->servicios->isNotEmpty()) {
        foreach ($cita->servicios as $s) {
            if ($s->pivot->empleado_id) {
                $citaEmpleadosMap[(string)$s->id] = (string)$s->pivot->empleado_id;
            }
        }
    }
    foreach (old('empleados', []) as $sid => $eid) {
        if ($eid) $citaEmpleadosMap[(string)$sid] = (string)$eid;
    }
@endphp

<div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm" x-data="citaForm()" x-init="init()">
    <form method="POST" action="{{ $action }}">
        @csrf
        @if($method === 'PUT') @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <x-input-label for="fecha" value="Fecha" />
                <input id="fecha" type="date" name="fecha" x-model="fecha" @change="cargarHorarios()"
                    value="{{ old('fecha', $fechaInicial ?? $cita?->fecha?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                    min="{{ now()->format('Y-m-d') }}"
                    class="mt-1 w-full rounded-xl border-slate-200" required>
                <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="hora" value="Hora disponible" />
                <select id="hora" name="hora" x-ref="horaSelect" class="mt-1 w-full rounded-xl border-slate-200" required>
                    <option value="">Selecciona fecha primero</option>
                </select>
                <p x-show="mensajeHorarios" x-text="mensajeHorarios" class="mt-2 text-sm font-bold text-amber-600"></p>
                <p x-show="!mensajeHorarios && slots.length > 0" class="mt-2 text-xs text-slate-400">
                    Selecciona un horario disponible
                </p>
                <x-input-error :messages="$errors->get('hora')" class="mt-2" />
            </div>
        </div>

        <div class="mb-6">
            <x-input-label value="Servicios" />
            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-80 overflow-y-auto p-4 bg-slate-50 rounded-2xl">
                @foreach($servicios as $servicio)
                <div>
                    <label class="flex items-center space-x-3 p-3 bg-white rounded-xl border cursor-pointer hover:border-indigo-300 transition-colors"
                           :class="serviciosActivos.includes('{{ $servicio->id }}') ? 'border-indigo-300 bg-indigo-50/50' : 'border-slate-100'">
                        <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                               x-model="serviciosActivos"
                               @change="onServicioChange($event.target.checked, '{{ $servicio->id }}')"
                               class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-bold text-slate-800 flex-1">{{ $servicio->nombre }}</span>
                        <span class="text-indigo-600 text-xs font-black">${{ number_format($servicio->precio,0) }}</span>
                    </label>

                    @php $empleadosActivos = $servicio->empleados->where('activo', true) @endphp
                    @if($empleadosActivos->count() > 0)
                    <div x-show="serviciosActivos.includes('{{ $servicio->id }}')" x-cloak class="mt-1 px-1">
                        <select name="empleados[{{ $servicio->id }}]"
                                @change="onEmpleadoChange('{{ $servicio->id }}', $event.target.value)"
                                class="w-full text-sm rounded-xl border-slate-200 py-2 focus:ring-indigo-500">
                            <option value="">— Sin preferencia —</option>
                            @foreach($empleadosActivos as $emp)
                            <option value="{{ $emp->id }}"
                                @selected(!empty($citaEmpleadosMap[(string)$servicio->id]) && $citaEmpleadosMap[(string)$servicio->id] == $emp->id)>
                                {{ $emp->nombre }}{{ $emp->especialidad ? ' · '.$emp->especialidad : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            <x-input-error :messages="$errors->get('servicios')" class="mt-2" />
        </div>

        @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
        <div class="mb-6">
            <x-input-label for="estado" value="Estado" />
            <select name="estado" id="estado" class="mt-1 w-full rounded-xl border-slate-200">
                @foreach(['pendiente','confirmada','completada','cancelada'] as $estado)
                    <option value="{{ $estado }}" @selected(old('estado', $cita?->estado ?? 'pendiente') === $estado)>{{ ucfirst($estado) }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="mb-8">
            <x-input-label for="notas" value="Notas" />
            <textarea name="notas" id="notas" rows="3" class="mt-1 w-full rounded-xl border-slate-200">{{ old('notas', $cita?->notas) }}</textarea>
        </div>

        <div class="flex gap-4">
            <x-primary-button>Guardar cita</x-primary-button>
            <a href="{{ route('citas.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-100 rounded-xl font-bold text-slate-600 text-sm">Cancelar</a>
        </div>
    </form>
</div>

<script>
function citaForm() {
    return {
        fecha: @json($jsfecha),
        slots: [],
        mensajeHorarios: '',
        horaSeleccionada: @json($jsHora),
        citaId: @json($jsCitaId),
        serviciosActivos: @json($citaServiciosIds),
        empleadosSeleccionados: @json((object)$citaEmpleadosMap),

        init() {
            window.addEventListener('fecha-cita-seleccionada', (e) => {
                this.fecha = e.detail;
                this.cargarHorarios();
            });
            this.cargarHorarios();
        },

        onServicioChange(checked, servicioId) {
            if (!checked) {
                delete this.empleadosSeleccionados[servicioId];
                this.cargarHorarios();
            }
        },

        onEmpleadoChange(servicioId, value) {
            if (value) {
                this.empleadosSeleccionados[servicioId] = value;
            } else {
                delete this.empleadosSeleccionados[servicioId];
            }
            this.cargarHorarios();
        },

        actualizarSelectHoras() {
            const select = this.$refs.horaSelect;
            if (!select) return;

            select.innerHTML = '';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = this.slots.length
                ? 'Selecciona una hora'
                : (this.mensajeHorarios || 'Sin horarios disponibles');
            select.appendChild(placeholder);

            this.slots.forEach((slot) => {
                const option = document.createElement('option');
                option.value = slot;
                option.textContent = slot;
                if (slot === this.horaSeleccionada) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        },

        async cargarHorarios() {
            if (!this.fecha) return;

            this.mensajeHorarios = 'Cargando horarios...';
            this.slots = [];
            this.actualizarSelectHoras();

            const params = new URLSearchParams({ fecha: this.fecha });
            if (this.citaId) params.append('cita_id', this.citaId);
            Object.entries(this.empleadosSeleccionados).forEach(([sid, eid]) => {
                if (eid) params.append(`empleados[${sid}]`, eid);
            });

            try {
                const res = await fetch(`{{ route('citas.horarios.json') }}?${params}`);
                if (!res.ok) {
                    this.mensajeHorarios = 'No se pudieron cargar los horarios. Recarga la página.';
                    this.actualizarSelectHoras();
                    return;
                }

                const data = await res.json();
                this.slots = data.slots || [];
                this.mensajeHorarios = data.mensaje || '';

                if (this.horaSeleccionada && !this.slots.includes(this.horaSeleccionada)) {
                    this.horaSeleccionada = '';
                }

                this.actualizarSelectHoras();
            } catch (error) {
                this.mensajeHorarios = 'Error de conexión al cargar horarios.';
                this.actualizarSelectHoras();
            }
        }
    }
}
</script>
