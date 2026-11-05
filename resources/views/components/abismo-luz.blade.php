{{--
    Rayos de luz que se mecen y partículas que suben, para secciones con fondo abismo
    (luz entrando bajo el agua). Decorativo: va como primer hijo de una sección con "relative isolate";
    el contenido de la sección debe llevar "relative z-10".
    Uso: <x-abismo-luz />   ·   <x-abismo-luz :particulas="8" />
--}}
@props(['particulas' => 14])

@php
    $rayos = [
        ['x' => '8%',  'w' => '12%', 'r' => '16deg', 't' => '12s', 'dl' => '0s'],
        ['x' => '30%', 'w' => '7%',  'r' => '12deg', 't' => '9s',  'dl' => '-4s'],
        ['x' => '52%', 'w' => '16%', 'r' => '18deg', 't' => '14s', 'dl' => '-7s'],
        ['x' => '78%', 'w' => '9%',  'r' => '10deg', 't' => '10s', 'dl' => '-2s'],
    ];
    // Valores fijos (no aleatorios) para que el dibujo sea igual en cada carga
    $semilla = fn ($n, $a, $b) => $a + (($n * 37 + 11) % 97) / 97 * ($b - $a);
@endphp

<div class="abismo-luz" aria-hidden="true">
    @foreach ($rayos as $r)
        <span class="abismo-rayo" style="--x: {{ $r['x'] }}; --w: {{ $r['w'] }}; --r: {{ $r['r'] }}; --t: {{ $r['t'] }}; --dl: {{ $r['dl'] }}"></span>
    @endforeach
    @for ($n = 0; $n < $particulas; $n++)
        <span class="abismo-particula" style="--x: {{ round($semilla($n, 3, 97), 1) }}%; --s: {{ round($semilla($n + 5, 2, 4.5), 1) }}px; --t: {{ round($semilla($n + 9, 12, 22), 1) }}s; --dl: -{{ round($semilla($n + 3, 0, 20), 1) }}s"></span>
    @endfor
</div>
