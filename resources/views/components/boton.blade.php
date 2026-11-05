{{--
    Botón del sistema Arrecife. Con href se dibuja como enlace; sin href, como <button>.

    Uso:
      <x-boton href="{{ route('citas.create') }}">Reservar cita</x-boton>
      <x-boton variante="secundario" href="#servicios">Ver servicios y precios</x-boton>
      <x-boton variante="claro">Reservar cita</x-boton>         (sobre fondo abismo)
      <x-boton type="submit">Guardar cambios</x-boton>

    variante: primario (por defecto) · secundario · claro · peligro
--}}
@props(['variante' => 'primario', 'href' => null])

@php
    $clase = [
        'primario'   => 'btn-primary',
        'secundario' => 'btn-secondary',
        'claro'      => 'btn-claro',
        'peligro'    => 'btn-peligro',
    ][$variante] ?? 'btn-primary';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class([$clase]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'button'])->class([$clase]) }}>{{ $slot }}</button>
@endif
