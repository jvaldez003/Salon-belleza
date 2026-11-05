{{--
    Reflejo de luz de agua (cáusticas) animado. Es el único efecto llamativo del sistema:
    úsalo solo en la foto principal del inicio. Debe ir dentro de un contenedor con position: relative
    (por ejemplo, dentro de <x-foto>). Respeta "reducir movimiento" y se pausa fuera de pantalla.

    Uso:
      <x-foto :src="$banner->imagen" prioridad>
          <x-luz-agua />
      </x-foto>
--}}
<canvas data-luz-agua width="160" height="200" aria-hidden="true"
        class="absolute inset-0 w-full h-full pointer-events-none mix-blend-screen opacity-[.55] blur-[1.2px]"></canvas>
