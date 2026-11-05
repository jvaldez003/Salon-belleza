{{--
    Botón "Eliminar" que pide confirmación en la misma página (sin ventanas del navegador).
    Uso:
      <x-confirmar-borrado :action="route('servicios.destroy', $s)" que="el servicio Spa capilar" />
      <x-confirmar-borrado :action="..." que="esta foto" texto="Quitar" compacto />
    Props:
      action   URL del formulario DELETE
      que      lo que se borra, para la pregunta ("¿Eliminar el servicio Spa capilar?")
      texto    texto del botón (por defecto "Eliminar")
      compacto botón pequeño, para filas de tablas y fotos
--}}
@props(['action', 'que', 'texto' => 'Eliminar', 'compacto' => false])

<div x-data="{ seguro: false }" {{ $attributes->class(['relative inline-flex']) }} @keydown.escape="seguro = false">
    <button type="button" x-show="!seguro" @click="seguro = true; $nextTick(() => $refs.si.focus())"
            @class([
                'font-semibold text-red-700 hover:underline underline-offset-4',
                'text-sm' => $compacto,
            ])>{{ $texto }}<span class="sr-only"> {{ $que }}</span></button>

    <form x-show="seguro" x-cloak method="POST" action="{{ $action }}"
          class="flex flex-wrap items-center gap-2 rounded-xl bg-red-50 border border-red-200 px-3 py-2">
        @csrf
        @method('DELETE')
        <span class="text-sm text-red-900">¿{{ $texto }} {{ $que }}?</span>
        <button x-ref="si" type="submit" class="text-sm font-semibold text-white bg-red-700 hover:bg-red-800 rounded-full px-3 py-1">Sí, {{ \Illuminate\Support\Str::lower($texto) }}</button>
        <button type="button" @click="seguro = false" class="text-sm text-red-900 hover:underline underline-offset-4">No</button>
    </form>
</div>
