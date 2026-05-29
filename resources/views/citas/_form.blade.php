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
                    Horario del salón: {{ config('salon.hora_apertura') }} – {{ config('salon.hora_cierre') }} (lun–sáb)
                </p>
                <x-input-error :messages="$errors->get('hora')" class="mt-2" />
            </div>
        </div>

        <div class="mb-6">
            <x-input-label value="Servicios (selección múltiple)" />
            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-64 overflow-y-auto p-4 bg-slate-50 rounded-2xl">
                @foreach($servicios as $servicio)
                <label class="flex items-center space-x-3 p-3 bg-white rounded-xl border border-slate-100 cursor-pointer hover:border-indigo-300">
                    <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                        @checked(in_array($servicio->id, old('servicios', $cita?->servicios->pluck('id')->toArray() ?? [])))>
                    <span class="text-sm font-bold text-slate-800">{{ $servicio->nombre }}</span>
                    <span class="text-indigo-600 text-xs font-black ml-auto">${{ number_format($servicio->precio,0) }}</span>
                </label>
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
        fecha: @json($fechaInicial ?? old('fecha', $cita?->fecha?->format('Y-m-d') ?? now()->format('Y-m-d'))),
        slots: [],
        mensajeHorarios: '',
        horaSeleccionada: @json(old('hora', $cita ? substr($cita->hora, 0, 5) : '')),
        citaId: @json($cita?->id),
        init() {
            window.addEventListener('fecha-cita-seleccionada', (e) => {
                this.fecha = e.detail;
                this.cargarHorarios();
            });
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
