{{--
    Etiqueta del estado de una cita. Los colores de estado (verde, rojo) son semánticos
    y no forman parte de la paleta de marca.
    Uso: <x-estado-cita :estado="$cita->estado" />
--}}
@props(['estado'])

@php
    [$texto, $clase] = match ($estado) {
        'confirmada' => ['Confirmada', 'bg-agua/35 text-abismo'],
        'completada' => ['Completada', 'bg-emerald-50 text-emerald-800'],
        'cancelada'  => ['Cancelada', 'bg-red-50 text-red-800'],
        default      => ['Pendiente', 'bg-bruma-honda text-texto'],
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap', $clase]) }}>
    <span class="w-1.5 h-1.5 rounded-full bg-current" aria-hidden="true"></span>{{ $texto }}
</span>
