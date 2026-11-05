{{--
    Precio en pesos colombianos, alineado para cartas de precios.
    Redondea al peso (29999.99 → $30.000).

    Uso:
      <x-precio :valor="$servicio->precio" />
      <x-precio :valor="$servicio->precio" desde />     (muestra "desde $30.000")
--}}
@props(['valor' => 0, 'desde' => false])

<span {{ $attributes->class(['precio whitespace-nowrap']) }}>
    @if ($desde)<span class="font-sans text-xs text-texto-suave mr-1">desde</span>@endif${{ number_format(round((float) $valor), 0, ',', '.') }}
</span>
