<div class="space-y-3">
    <label class="label">Horario del Empleado</label>
    <p class="text-xs text-warm-400">Define los días y horas en que este empleado está disponible para citas.</p>

    @foreach($horariosDefault as $h)
    @php $esActivo = old("horarios.{$h->dia}.activo", $h->activo) ? 'true' : 'false'; @endphp
    <div class="bg-cream rounded-2xl px-5 py-4 flex items-center gap-4"
         x-data="{ abierto: {{ $esActivo }} }">

        <span class="font-black text-warm-700 w-24 shrink-0 text-sm">
            {{ \App\Models\Horario::nombreDia($h->dia) }}
        </span>

        <label class="relative inline-flex items-center cursor-pointer shrink-0">
            <input type="checkbox" name="horarios[{{ $h->dia }}][activo]" value="1"
                   x-model="abierto" class="sr-only peer"
                   @if(old("horarios.{$h->dia}.activo", $h->activo)) checked @endif>
            <div class="w-10 h-5 bg-warm-300 peer-checked:bg-rose-primary rounded-full transition-colors
                        after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white
                        after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full"></div>
        </label>

        <input type="hidden" name="horarios[{{ $h->dia }}][dia]" value="{{ $h->dia }}">

        <div class="flex items-center gap-2 flex-1 transition-opacity"
             :class="abierto ? 'opacity-100' : 'opacity-30 pointer-events-none'">
            <input type="time" name="horarios[{{ $h->dia }}][hora_apertura]"
                   value="{{ old("horarios.{$h->dia}.hora_apertura", substr($h->hora_apertura, 0, 5)) }}"
                   :disabled="!abierto"
                   class="flex-1 rounded-xl border-warm-200 bg-white text-sm font-bold text-warm-700 focus:ring-rose-primary focus:border-rose-primary">
            <span class="text-warm-400 text-xs font-bold">–</span>
            <input type="time" name="horarios[{{ $h->dia }}][hora_cierre]"
                   value="{{ old("horarios.{$h->dia}.hora_cierre", substr($h->hora_cierre, 0, 5)) }}"
                   :disabled="!abierto"
                   class="flex-1 rounded-xl border-warm-200 bg-white text-sm font-bold text-warm-700 focus:ring-rose-primary focus:border-rose-primary">
        </div>

        <span x-show="abierto"  class="text-xs font-black text-rose-dark w-14 text-right shrink-0">Trabaja</span>
        <span x-show="!abierto" class="text-xs font-black text-warm-400  w-14 text-right shrink-0">Libre</span>
    </div>
    @endforeach
</div>
