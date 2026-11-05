{{--
    Editor de horario semanal (lunes a domingo). Lo usan los horarios del salón y el de cada empleado.
    Envía horarios[<dia>][activo|hora_apertura|hora_cierre], igual que antes.

    Uso:
      <x-horario-semana :dias="$horarios" si="Abierto" no="Cerrado" />
      <x-horario-semana :dias="$horariosDefault" :salon="$salon" si="Trabaja" no="Descansa" />

    Props:
      dias   colección de Horario u HorarioEmpleado (dia, hora_apertura, hora_cierre, activo)
      salon  (opcional) horario del salón, para avisar si el empleado queda por fuera
      si/no  texto del estado de cada día
--}}
@props(['dias', 'salon' => null, 'si' => 'Abierto', 'no' => 'Cerrado'])

@php
    $hora = fn ($t) => substr((string) $t, 0, 5);
    $estado = $dias->mapWithKeys(fn ($h) => [$h->dia => [
        'nombre' => \App\Models\Horario::nombreDia($h->dia),
        'activo' => (bool) old("horarios.{$h->dia}.activo", $errors->any() ? false : $h->activo),
        'desde'  => old("horarios.{$h->dia}.hora_apertura", $hora($h->hora_apertura)),
        'hasta'  => old("horarios.{$h->dia}.hora_cierre", $hora($h->hora_cierre)),
    ]]);
    $refSalon = $salon?->mapWithKeys(fn ($h) => [$h->dia => ['activo' => (bool) $h->activo, 'desde' => $hora($h->hora_apertura), 'hasta' => $hora($h->hora_cierre)]]);
    $mensajes = collect($errors->getMessages())->filter(fn ($m, $k) => str_starts_with($k, 'horarios'))->flatten()->unique();
@endphp

<div class="grid gap-3"
     x-data="{
        dias: {{ Js::from($estado) }},
        salon: {{ Js::from($refSalon) }},
        copiado: false,
        copiarPrimero() {
            const ref = Object.values(this.dias).find(d => d.activo);
            if (!ref) return;
            Object.values(this.dias).forEach(d => { if (d.activo) { d.desde = ref.desde; d.hasta = ref.hasta; } });
            this.copiado = true; setTimeout(() => this.copiado = false, 2000);
        },
        aviso(n) {
            const d = this.dias[n];
            if (!d.activo) return '';
            if (d.hasta && d.desde && d.hasta <= d.desde) return 'La hora final debe ser después de la inicial.';
            if (!this.salon) return '';
            const s = this.salon[n];
            if (!s || !s.activo) return 'El salón está cerrado este día: no se podrán reservar citas.';
            const h12 = (t) => { const [h, m] = t.split(':').map(Number); return (h % 12 || 12) + ':' + String(m).padStart(2, '0') + (h < 12 ? ' a. m.' : ' p. m.'); };
            if (d.desde < s.desde || d.hasta > s.hasta) return 'El salón abre ese día de ' + h12(s.desde) + ' a ' + h12(s.hasta) + '. Fuera de ese horario no se ofrecen reservas.';
            return '';
        }
     }">
    <div class="flex flex-wrap items-center justify-between gap-2">
        {{ $slot }}
        <button type="button" @click="copiarPrimero()" class="text-sm font-semibold text-oceano hover:underline underline-offset-4">
            <span x-show="!copiado">Usar el primer horario en todos los días</span>
            <span x-show="copiado" x-cloak role="status">Listo, revisa y guarda</span>
        </button>
    </div>

    <ul class="card divide-y divide-linea overflow-hidden">
        <template x-for="(d, n) in dias" :key="n">
            <li class="grid grid-cols-[minmax(0,1fr)_auto] sm:grid-cols-[7.5rem_auto_minmax(0,1fr)] items-center gap-x-4 gap-y-2 px-4 sm:px-5 py-3"
                :class="d.activo ? '' : 'bg-bruma/60'">
                <span class="font-semibold text-tinta" x-text="d.nombre"></span>

                <label class="inline-flex items-center gap-2.5 cursor-pointer justify-self-end sm:justify-self-start">
                    <input type="checkbox" value="1" x-model="d.activo" :name="`horarios[${n}][activo]`" class="peer sr-only">
                    <span class="relative w-11 h-6 rounded-full bg-linea peer-checked:bg-oceano transition-colors peer-focus-visible:ring-2 peer-focus-visible:ring-laguna peer-focus-visible:ring-offset-2
                                 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:transition-transform peer-checked:after:translate-x-5"></span>
                    <span class="text-sm w-16" :class="d.activo ? 'font-semibold text-oceano' : 'text-texto-suave'" x-text="d.activo ? '{{ $si }}' : '{{ $no }}'"></span>
                    <span class="sr-only" x-text="d.nombre"></span>
                </label>

                <div class="col-span-2 sm:col-span-1 grid gap-1.5">
                    <div class="flex items-center gap-2" :class="d.activo ? '' : 'opacity-50'">
                        <label class="sr-only" :for="`desde-${n}`" x-text="d.nombre + ', desde'"></label>
                        <input type="time" step="900" :id="`desde-${n}`" :name="`horarios[${n}][hora_apertura]`" x-model="d.desde" :tabindex="d.activo ? 0 : -1"
                               class="form-input !py-2 !px-3 w-full max-w-[9rem] tabular">
                        <span class="text-texto-suave" aria-hidden="true">a</span>
                        <label class="sr-only" :for="`hasta-${n}`" x-text="d.nombre + ', hasta'"></label>
                        <input type="time" step="900" :id="`hasta-${n}`" :name="`horarios[${n}][hora_cierre]`" x-model="d.hasta" :tabindex="d.activo ? 0 : -1"
                               class="form-input !py-2 !px-3 w-full max-w-[9rem] tabular">
                    </div>
                    <p x-show="aviso(n)" x-cloak class="text-sm text-texto" x-text="aviso(n)"></p>
                </div>
            </li>
        </template>
    </ul>

    @if ($mensajes->isNotEmpty())
        <x-input-error :messages="$mensajes->all()" />
    @endif
</div>
