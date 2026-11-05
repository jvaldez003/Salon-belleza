<div>
    <label class="flex items-start gap-3 p-3 bg-white rounded-xl border cursor-pointer hover:border-indigo-300 transition-colors"
           :class="serviciosActivos.includes('{{ $servicio->id }}') ? 'border-indigo-400 bg-indigo-50/60 shadow-sm' : 'border-slate-100'">
        <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
               x-model="serviciosActivos"
               @change="onServicioChange($event.target.checked, '{{ $servicio->id }}')"
               class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500 shrink-0">
        <div class="flex-1 min-w-0">
            <span class="text-sm font-bold text-slate-800 block leading-snug">{{ $servicio->nombre }}</span>
            @if($servicio->descripcion)
            <span class="text-xs text-slate-400 leading-snug line-clamp-1">{{ $servicio->descripcion }}</span>
            @endif
        </div>
        <span class="text-indigo-600 text-xs font-black shrink-0 mt-0.5">${{ number_format($servicio->precio, 0) }}</span>
    </label>

    @php $empleadosActivos = $servicio->empleados->where('activo', true) @endphp
    @if($empleadosActivos->count() > 0)
    <div x-show="serviciosActivos.includes('{{ $servicio->id }}')" x-cloak class="mt-1 px-1">
        <select name="empleados[{{ $servicio->id }}]"
                @change="onEmpleadoChange('{{ $servicio->id }}', $event.target.value)"
                class="w-full text-xs rounded-xl border-slate-200 py-2 focus:ring-indigo-500 bg-white">
            <option value="">— Sin preferencia de especialista —</option>
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
