{{--
    Foto subida por el salón dentro de un marco de proporción fija.
    Se adapta a cualquier foto (vertical, horizontal, cuadrada): se recorta para llenar el marco sin deformarse.

    Uso:
      <x-foto :src="$categoria->imagen" alt="Capilares" proporcion="4/5" foco="arriba" inicial="C" />
      <x-foto :src="$banner->imagen" proporcion="16/9" class="md:aspect-16/9 aspect-4/5">
          <div class="foto-velo">Texto encima de la foto</div>
      </x-foto>

    Props:
      src        Ruta en el disco public (p. ej. "categorias/abc.jpg") o URL completa. Si está vacía, muestra el respaldo de marca.
      alt        Texto alternativo. Vacío si la foto es decorativa.
      proporcion "4/5" (tarjetas) · "3/4" (retratos) · "1/1" (galería) · "16/9" (banners). Se ignora si pasas tu propia clase aspect-*.
      foco       "arriba" · "centro" · "abajo", o un valor CSS como "50% 30%". Evita cortar caras o manos.
      inicial    Letra que se muestra en el respaldo cuando no hay foto.
      prioridad  true para la foto principal de la página (no usa carga diferida).
--}}
@props([
    'src'       => null,
    'alt'       => '',
    'proporcion'=> '4/5',
    'foco'      => 'centro',
    'inicial'   => 'A',
    'prioridad' => false,
])

@php
    $proporciones = [
        '4/5'  => 'aspect-4/5',
        '3/4'  => 'aspect-3/4',
        '1/1'  => 'aspect-square',
        '16/9' => 'aspect-16/9',
    ];
    $focos = ['arriba' => '50% 15%', 'centro' => '50% 50%', 'abajo' => '50% 85%'];

    $claseProporcion = str_contains($attributes->get('class', ''), 'aspect-')
        ? ''
        : ($proporciones[$proporcion] ?? 'aspect-4/5');
    $posicion = $focos[$foco] ?? $foco;

    $url = null;
    if (! empty($src)) {
        $url = \Illuminate\Support\Str::startsWith($src, ['http://', 'https://', '/', 'data:'])
            ? $src
            : asset('storage/' . ltrim($src, '/'));
    }
@endphp

<div {{ $attributes->class(['foto-marco', $claseProporcion]) }}>
    @if ($url)
        <img src="{{ $url }}"
             alt="{{ $alt }}"
             style="object-position: {{ $posicion }}"
             @if ($prioridad) fetchpriority="high" @else loading="lazy" decoding="async" @endif>
    @else
        <div class="foto-respaldo" @if ($alt) role="img" aria-label="{{ $alt }}" @else aria-hidden="true" @endif>
            <span class="foto-respaldo-letra font-display text-oceano/80 leading-none">{{ mb_strtoupper(mb_substr($inicial ?: 'A', 0, 1)) }}</span>
            <span class="foto-respaldo-firma absolute bottom-[6%] inset-x-0 text-center font-display italic tracking-[.12em] text-oceano">Arrecife</span>
        </div>
    @endif
    {{ $slot }}
</div>
