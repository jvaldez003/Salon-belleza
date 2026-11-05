{{--
    Fondo del mar con arrecife: el final del "descenso al arrecife". Ocupa todo el pie de página
    (va como primer hijo de un contenedor "relative isolate" con data-raton-profundidad).

    Ilusión de 3D:
      · cinco capas (lejos, lejos 2, medio, cerca, primer plano) con distinta claridad y desenfoque,
      · cada coral sombreado: claro arriba (luz de superficie) y oscuro en la base, con borde de luz,
      · las capas se desplazan distinto con el mouse (--mx/--my, factor --k) y al bajar (data-profundidad).
    Vida: tortuga, mantarraya, cardumen, medusas, caballito de mar, pulpo, peces junto a la anémona,
    corales (cuerno de ciervo y de alce, cerebro, mesa, pilar, blando, lechuga, copa), esponjas,
    abanicos, látigos de mar, algas, erizo y estrella de mar.
    Marcos laterales de algas altas y medusas solo en pantallas medianas o grandes (en el celular el texto ocupa todo).
    Todo se pausa fuera de pantalla y queda quieto con "reducir movimiento". Estilos: app.css, "Arrecife del pie de página".
--}}
<div {{ $attributes->class(['arrecife pointer-events-none absolute inset-0']) }} aria-hidden="true" data-pausar-fuera>

    {{-- ══ Medusas (arriba, en los márgenes, lejos del texto) ══ --}}
    @foreach ([
        ['left: 1.6%; top: 7%', 46, '15s', '-2s'],
        ['right: 2.2%; top: 24%', 36, '12s', '-7s'],
        ['left: 44%; top: 63%', 26, '11s', '-4s'],
    ] as [$pos, $ancho, $t, $dl])
        <div class="arrecife-medusa hidden md:block" style="{{ $pos }}; width: {{ $ancho }}px; --t: {{ $t }}; --dl: {{ $dl }}">
            <svg viewBox="0 0 60 130" class="w-full h-auto overflow-visible">
                <g class="medusa-tentaculos" fill="none" stroke-linecap="round">
                    <path d="M28 34 C25 52 34 62 29 80 C26 90 31 98 28 108" stroke="rgb(169 198 228 / .35)" stroke-width="3.2"/>
                    <path d="M33 34 C36 50 28 62 33 76" stroke="rgb(169 198 228 / .3)" stroke-width="2.6"/>
                    <path d="M14 34 C10 56 18 72 12 96 M22 35 C20 60 28 78 22 116 M38 35 C40 58 32 76 38 104 M46 34 C50 54 42 70 48 92"
                          stroke="rgb(169 198 228 / .32)" stroke-width="1.3"/>
                </g>
                <g class="medusa-campana">
                    <path d="M4 34 C4 14 16 4 30 4 C44 4 56 14 56 34 C50 32 46 36 42 33 C38 36 34 32 30 35 C26 32 22 36 18 33 C14 36 10 32 4 34Z"
                          fill="rgb(127 216 255 / .2)" stroke="rgb(169 198 228 / .55)" stroke-width="1.2"/>
                    <ellipse cx="30" cy="19" rx="15" ry="8" fill="rgb(234 246 255 / .12)"/>
                    <path d="M14 22 C18 14 24 10 30 10" fill="none" stroke="rgb(234 246 255 / .35)" stroke-width="1.5" stroke-linecap="round"/>
                </g>
            </svg>
        </div>
    @endforeach

    {{-- ══ Marcos laterales: algas altas y abanicos que suben por los costados ══ --}}
    <div class="arrecife-marco hidden md:block absolute bottom-0 left-0 h-[92%] w-[min(20vw,300px)]" data-profundidad="0.08" style="--k: .55">
        <svg class="w-full h-full overflow-visible" viewBox="0 0 300 800" preserveAspectRatio="xMinYMax meet">
            <g fill="url(#arrecife-sombra-medio)" stroke="url(#arrecife-sombra-medio)" filter="url(#arrecife-borde)">
                <g transform="translate(70 800) scale(1.25)"><g class="arrecife-mece" style="--t: 14s; --a: 1.5deg"><use href="#arrecife-abanico"/></g></g>
                <g transform="translate(150 800) scale(1.7)"><g class="arrecife-mece" style="--t: 9s; --a: 3deg; --dl: -2s"><use href="#arrecife-latigo"/></g></g>
            </g>
            <g fill="url(#arrecife-sombra-cerca)" stroke="url(#arrecife-sombra-cerca)" filter="url(#arrecife-borde)">
                <g transform="translate(26 800) scale(2.7)"><g class="arrecife-mece" style="--t: 9s; --a: 3deg"><use href="#arrecife-alga"/></g></g>
                <g transform="translate(64 800) scale(2.15)"><g class="arrecife-mece" style="--t: 7.5s; --a: 4deg; --dl: -3s"><use href="#arrecife-alga"/></g></g>
                <g transform="translate(102 800) scale(1.55)"><g class="arrecife-mece" style="--t: 6.5s; --a: 5deg; --dl: -1s"><use href="#arrecife-alga"/></g></g>
            </g>
        </svg>
    </div>
    <div class="arrecife-marco hidden md:block absolute bottom-0 right-0 h-[88%] w-[min(20vw,300px)]" data-profundidad="0.08" style="--k: .55">
        <svg class="w-full h-full overflow-visible" viewBox="0 0 300 800" preserveAspectRatio="xMaxYMax meet">
            <g fill="url(#arrecife-sombra-medio)" stroke="url(#arrecife-sombra-medio)" filter="url(#arrecife-borde)">
                <g transform="translate(150 800) scale(1.4)"><g class="arrecife-mece" style="--t: 12s; --a: 3deg; --dl: -5s"><use href="#arrecife-latigo"/></g></g>
                <g transform="translate(225 800) scale(1.05)"><g class="arrecife-mece" style="--t: 13s; --a: 1.6deg; --dl: -3s"><use href="#arrecife-abanico"/></g></g>
            </g>
            <g fill="url(#arrecife-sombra-cerca)" stroke="url(#arrecife-sombra-cerca)" filter="url(#arrecife-borde)">
                <g transform="translate(268 800) scale(2.5)"><g class="arrecife-mece" style="--t: 8.5s; --a: 3.5deg; --dl: -4s"><use href="#arrecife-alga"/></g></g>
                <g transform="translate(232 800) scale(1.9)"><g class="arrecife-mece" style="--t: 7s; --a: 4.5deg; --dl: -1.5s"><use href="#arrecife-alga"/></g></g>
                <g transform="translate(196 800) scale(1.35)"><g class="arrecife-mece" style="--t: 6s; --a: 5deg"><use href="#arrecife-alga"/></g></g>
            </g>
        </svg>
    </div>

    {{-- ══ Banda del fondo: el arrecife ══ --}}
    <svg class="absolute inset-x-0 bottom-0 w-full h-64 md:h-[26rem]" viewBox="0 0 1440 360" preserveAspectRatio="xMidYMax slice" focusable="false">
        <defs>
            {{-- Luz de superficie en el borde superior de cada silueta --}}
            <filter id="arrecife-borde" x="-2%" y="-10%" width="104%" height="120%" color-interpolation-filters="sRGB">
                <feOffset in="SourceAlpha" dy="2.2" result="abajo"/>
                <feComposite in="SourceAlpha" in2="abajo" operator="out" result="borde"/>
                <feFlood flood-color="#7FD8FF" flood-opacity=".42"/>
                <feComposite in2="borde" operator="in" result="luz"/>
                <feMerge><feMergeNode in="SourceGraphic"/><feMergeNode in="luz"/></feMerge>
            </filter>
            {{-- Volumen: cada pieza tiene la base en y=0 y crece hacia arriba (y negativa),
                 así el degradado en coordenadas propias la aclara arriba y la oscurece en la base. --}}
            <linearGradient id="arrecife-sombra-lejos2" gradientUnits="userSpaceOnUse" x1="0" y1="-140" x2="0" y2="0">
                <stop offset="0" stop-color="#2E68AE"/><stop offset="1" stop-color="#174886"/>
            </linearGradient>
            <linearGradient id="arrecife-sombra-medio" gradientUnits="userSpaceOnUse" x1="0" y1="-170" x2="0" y2="0">
                <stop offset="0" stop-color="#2160A8"/><stop offset="1" stop-color="#0B3270"/>
            </linearGradient>
            <linearGradient id="arrecife-sombra-cerca" gradientUnits="userSpaceOnUse" x1="0" y1="-170" x2="0" y2="0">
                <stop offset="0" stop-color="#1A549C"/><stop offset="1" stop-color="#05183C"/>
            </linearGradient>
            <linearGradient id="arrecife-sombra-tortuga" gradientUnits="userSpaceOnUse" x1="0" y1="-30" x2="0" y2="30">
                <stop offset="0" stop-color="#2A68B0"/><stop offset="1" stop-color="#0C3373"/>
            </linearGradient>

            {{-- ── Piezas (sin color propio: lo heredan de su capa) ── --}}
            <g id="arrecife-cuerno" fill="none" stroke-width="9" stroke-linecap="round" stroke-linejoin="round">
                <path d="M0 0 C-2 -30 -10 -52 -24 -74 M-24 -74 C-31 -88 -38 -98 -46 -116 M-24 -74 C-16 -90 -12 -102 -11 -122
                         M0 0 C6 -32 16 -50 30 -70 M30 -70 C38 -86 42 -98 42 -118 M30 -70 C22 -84 16 -92 10 -106
                         M2 -10 C18 -20 38 -24 56 -40 M56 -40 C66 -50 72 -60 74 -78
                         M-4 -14 C-22 -22 -42 -26 -58 -44 M-58 -44 C-66 -54 -70 -64 -70 -80"/>
            </g>
            <path id="arrecife-alce" stroke="none"
                  d="M-6 0 C-8 -20 -10 -30 -24 -40 C-40 -50 -60 -52 -74 -66 C-66 -70 -50 -64 -38 -58 C-44 -72 -46 -84 -40 -98
                     C-32 -86 -28 -72 -20 -60 C-16 -76 -8 -90 4 -100 C6 -84 0 -68 2 -54 C12 -66 26 -74 44 -76 C38 -64 24 -54 12 -44
                     C28 -46 46 -40 62 -30 C44 -24 24 -28 8 -30 C8 -20 6 -10 6 0Z"/>
            <g id="arrecife-mesa" stroke="none">
                <path d="M-7 0 C-6 -14 -6 -26 -4 -36 L4 -36 C6 -26 6 -14 7 0Z"/>
                <path d="M-74 -36 C-78 -44 -62 -52 -42 -52 C-20 -58 20 -58 42 -52 C62 -52 80 -44 74 -36 C66 -32 50 -34 40 -31
                         C20 -28 -20 -28 -40 -31 C-50 -34 -66 -32 -74 -36Z"/>
            </g>
            <path id="arrecife-domo" stroke="none" d="M-62 0 C-62 -42 -32 -58 0 -58 C32 -58 62 -42 62 0Z"/>
            <g id="arrecife-surcos" fill="none" stroke-width="2" stroke-linecap="round">
                <path d="M-50 -12 C-38 -28 -24 -16 -14 -34 C-4 -48 10 -32 20 -44 C30 -52 42 -36 50 -16
                         M-42 -4 C-30 -18 -18 -8 -6 -24 C4 -36 18 -22 28 -32 C36 -38 44 -24 46 -8
                         M-26 -44 C-14 -52 2 -50 14 -54"/>
            </g>
            <g id="arrecife-columnas" stroke="none">
                <rect x="-30" y="-58" width="14" height="60" rx="7"/>
                <rect x="-14" y="-90" width="15" height="92" rx="7.5"/>
                <rect x="3" y="-72" width="13" height="74" rx="6.5"/>
                <rect x="18" y="-44" width="12" height="46" rx="6"/>
            </g>
            <g id="arrecife-tubos" stroke="none">
                <rect x="-28" y="-70" width="16" height="72" rx="8"/>
                <rect x="-8" y="-104" width="18" height="106" rx="9"/>
                <rect x="14" y="-56" width="14" height="58" rx="7"/>
            </g>
            {{-- Esponja de barril --}}
            <g id="arrecife-barril" stroke="none">
                <path d="M-30 0 C-34 -30 -40 -60 -36 -84 C-20 -78 20 -78 36 -84 C40 -60 34 -30 30 0Z"/>
                <ellipse cx="0" cy="-82" rx="35" ry="6" opacity=".45"/>
            </g>
            {{-- Coral blando: tallos con puntas esponjosas --}}
            <g id="arrecife-blando">
                <path d="M0 0 C-2 -20 -4 -34 -14 -48 M0 0 C2 -24 8 -38 18 -54 M-1 -18 C-12 -26 -24 -30 -32 -40 M2 -26 C12 -30 22 -36 30 -48
                         M-14 -48 C-18 -58 -16 -66 -10 -74 M18 -54 C16 -64 20 -72 26 -80" fill="none" stroke-width="6" stroke-linecap="round"/>
                <g stroke="none">
                    <circle cx="-32" cy="-44" r="9"/><circle cx="-10" cy="-78" r="10"/><circle cx="-18" cy="-60" r="7"/>
                    <circle cx="26" cy="-84" r="9"/><circle cx="30" cy="-52" r="8"/><circle cx="14" cy="-66" r="7"/><circle cx="4" cy="-92" r="6"/>
                </g>
            </g>
            {{-- Coral lechuga: volantes ondulados --}}
            <g id="arrecife-lechuga" stroke="none">
                <path d="M-50 0 C-56 -14 -44 -22 -36 -18 C-34 -30 -20 -34 -14 -24 C-10 -38 6 -40 10 -28 C16 -40 32 -36 32 -24 C42 -30 54 -20 50 0Z"/>
                <path d="M-36 -16 C-40 -30 -28 -40 -20 -34 C-16 -48 0 -50 4 -38 C10 -50 26 -46 24 -32 C30 -34 36 -26 34 -16Z" opacity=".8"/>
            </g>
            {{-- Coral de copa --}}
            <path id="arrecife-copa" stroke="none" d="M-4 0 L-3 -10 C-10 -11 -12 -18 -9 -21 C-5 -18 5 -18 9 -21 C12 -18 10 -11 3 -10 L4 0Z"/>
            <g id="arrecife-abanico">
                <path d="M0 0 C-60 -40 -90 -110 -70 -170 C-40 -204 40 -204 70 -170 C90 -110 60 -40 0 0Z" stroke="none" opacity=".35"/>
                <path d="M0 -40 C-10 -80 -30 -120 -55 -160 M0 -40 C10 -80 30 -120 55 -160 M0 -40 C-2 -90 -4 -140 -6 -190
                         M-14 -88 C-30 -100 -50 -112 -68 -128 M14 -88 C30 -100 50 -112 68 -128
                         M-4 -120 C-14 -140 -22 -160 -30 -186 M4 -120 C14 -140 22 -160 30 -186
                         M-30 -120 C-40 -136 -48 -150 -58 -168 M30 -120 C40 -136 48 -150 58 -168" fill="none" stroke-width="3" stroke-linecap="round"/>
                <path d="M0 2 L0 -42" fill="none" stroke-width="7" stroke-linecap="round"/>
            </g>
            <g id="arrecife-latigo" fill="none" stroke-width="3.5" stroke-linecap="round">
                <path d="M0 0 C-4 -60 -18 -120 -10 -190 M2 0 C6 -50 18 -100 30 -160 M-2 0 C-10 -40 -30 -80 -40 -130 M1 0 C2 -40 8 -70 4 -110"/>
            </g>
            <path id="arrecife-alga" stroke="none" d="M-4 0 C-14 -40 8 -80 -4 -120 C-16 -160 6 -200 0 -244 C10 -200 20 -160 10 -120 C22 -80 2 -40 6 0 Z"/>
            <g id="arrecife-anemona">
                <path d="M-22 0 C-24 -14 -18 -24 0 -24 C18 -24 24 -14 22 0Z" stroke="none"/>
                <path d="M-18 -20 C-30 -34 -34 -48 -30 -62 M-12 -22 C-18 -40 -16 -54 -10 -68 M-5 -24 C-8 -44 -4 -60 2 -72
                         M3 -24 C6 -44 12 -58 18 -70 M10 -22 C18 -38 26 -50 32 -62 M16 -20 C28 -30 38 -40 42 -54
                         M-20 -16 C-34 -24 -42 -34 -46 -46" fill="none" stroke-width="4" stroke-linecap="round"/>
            </g>
            <g id="arrecife-erizo">
                <circle cx="0" cy="-10" r="11" stroke="none"/>
                <path d="M0 -10 L0 -36 M0 -10 L-14 -33 M0 -10 L14 -33 M0 -10 L-24 -25 M0 -10 L24 -25 M0 -10 L-29 -12 M0 -10 L29 -12
                         M0 -10 L-7 -35 M0 -10 L7 -35 M0 -10 L-20 -30 M0 -10 L20 -30" fill="none" stroke-width="2" stroke-linecap="round"/>
            </g>
            <path id="arrecife-estrella" stroke="none" d="M0 -14 C2 -9 3 -6 5 -5 C9 -5 12 -5 13 -4 C10 -1 7 1 6 2 C7 6 8 9 8 11 C4 9 2 7 0 6 C-2 7 -4 9 -8 11 C-8 9 -7 6 -6 2 C-7 1 -10 -1 -13 -4 C-12 -5 -9 -5 -5 -5 C-3 -6 -2 -9 0 -14Z"/>
            {{-- Pulpo asomado --}}
            <g id="arrecife-pulpo">
                <path d="M-18 -10 C-22 -34 -10 -50 4 -50 C20 -50 28 -34 22 -10Z" stroke="none"/>
                <path d="M-16 -12 C-26 -8 -34 -2 -40 -8 C-44 -12 -40 -18 -34 -16 M-6 -10 C-10 -2 -18 2 -22 0
                         M10 -10 C14 -2 22 2 28 -2 C32 -6 30 -12 24 -12 M20 -12 C30 -14 40 -10 44 -18 C46 -24 40 -26 36 -22"
                      fill="none" stroke-width="5" stroke-linecap="round"/>
            </g>
            <g id="arrecife-caballito" stroke="none">
                <path d="M2 -58 C8 -62 16 -60 18 -56 L31 -54 C32 -52 32 -50 31 -49 L18 -50 C18 -44 14 -40 10 -38 C16 -32 18 -24 16 -16
                         C14 -8 8 -4 4 -2 C0 2 -2 8 2 12 C6 14 10 10 8 6 C11 8 13 14 7 18 C-2 22 -10 14 -6 4 C-4 -2 2 -8 4 -14
                         C6 -20 4 -26 0 -30 C-6 -34 -8 -42 -6 -48 C-4 -54 -2 -56 2 -58Z"/>
                <path d="M-6 -40 C-12 -36 -13 -28 -8 -24 C-6 -30 -5 -35 -6 -40Z"/>
                <path d="M4 -60 L2 -66 L7 -62Z"/>
            </g>
            <path id="arrecife-pez" stroke="none" d="M0 0 C6 -5 16 -5 22 0 C16 5 6 5 0 0Z M21 0 L30 -6 L27 0 L30 6Z"/>
            {{-- Mantarraya vista desde arriba --}}
            <path id="arrecife-manta" stroke="none" d="M0 0 C20 -6 40 -20 64 -4 C46 -2 30 4 12 6 L3 26 L0 8 L-3 26 L-12 6 C-30 4 -46 -2 -64 -4 C-40 -20 -20 -6 0 0Z"/>
        </defs>

        {{-- ── 1. Lejos: cordillera difusa ── --}}
        <g class="arrecife-lejos" data-profundidad="0.04" style="--k: .12" fill="currentColor" stroke="currentColor">
            <path d="M-80 420 L-80 250 C0 238 140 200 210 215 C270 228 300 190 360 185 C430 180 470 225 540 220 C610 215 650 170 720 175
                     C800 182 830 230 900 225 C980 220 1010 180 1080 185 C1150 190 1190 235 1260 228 C1330 222 1380 200 1520 205 L1520 420 Z" stroke="none"/>
            <use href="#arrecife-cuerno" transform="translate(360 190) scale(.55)"/>
            <use href="#arrecife-abanico" transform="translate(712 178) scale(.45)"/>
            <use href="#arrecife-mesa" transform="translate(560 222) scale(.6)"/>
            <use href="#arrecife-cuerno" transform="translate(1085 188) scale(.6)"/>
            <use href="#arrecife-tubos" transform="translate(215 214) scale(.6)"/>
            <use href="#arrecife-mesa" transform="translate(900 226) scale(.5)"/>
            <use href="#arrecife-latigo" transform="translate(1250 230) scale(.55)"/>
            <use href="#arrecife-tubos" transform="translate(1385 206) scale(.55)"/>
        </g>

        {{-- Mantarraya lejana --}}
        <g class="arrecife-manta-ruta"><g class="arrecife-manta"><use href="#arrecife-manta"/></g></g>

        {{-- ── 2. Lejos 2 ── --}}
        <g class="arrecife-lejos2" data-profundidad="0.08" style="--k: .25" fill="url(#arrecife-sombra-lejos2)" stroke="url(#arrecife-sombra-lejos2)">
            <path d="M-80 420 L-80 268 C40 252 120 236 200 246 C290 258 330 236 410 232 C500 228 540 262 620 258 C700 254 740 226 830 230
                     C910 234 960 262 1040 258 C1120 254 1170 230 1250 236 C1330 242 1400 262 1520 252 L1520 420Z" fill="#164785" stroke="none"/>
            <use href="#arrecife-blando" transform="translate(130 248) scale(.7)"/>
            <use href="#arrecife-mesa" transform="translate(470 232) scale(.55)"/>
            <use href="#arrecife-lechuga" transform="translate(760 232) scale(.6)"/>
            <use href="#arrecife-barril" transform="translate(980 258) scale(.5)"/>
            <use href="#arrecife-blando" transform="translate(1180 240) scale(.6)"/>
            <use href="#arrecife-columnas" transform="translate(1330 246) scale(.5)"/>
        </g>

        {{-- Cardumen --}}
        <g class="arrecife-cardumen" fill="currentColor">
            @foreach ([[0, 0, 0], [34, -12, -1.2], [30, 14, -2.1], [66, 2, -.6], [64, -22, -1.7], [96, -6, -2.6], [100, 18, -.3], [130, -14, -1.4], [128, 10, -2.2]] as [$x, $y, $dl])
                <g transform="translate({{ $x }} {{ $y }})"><g class="arrecife-flota" style="--dl: {{ $dl }}s"><use href="#arrecife-pez"/></g></g>
            @endforeach
        </g>

        {{-- Tortuga marina: cruza de vez en cuando entre los corales --}}
        <g class="arrecife-tortuga-ruta"><g class="arrecife-tortuga" fill="url(#arrecife-sombra-tortuga)" filter="url(#arrecife-borde)">
                <g class="tortuga-aleta-atras"><path d="M30 -14 C40 -26 54 -30 62 -28 C56 -18 44 -10 34 -6Z"/></g>
                <g class="tortuga-aleta-abajo"><path d="M-18 18 C-26 34 -42 44 -56 44 C-48 34 -34 24 -22 16Z"/></g>
                <path d="M44 4 L58 8 L44 10Z"/>
                <ellipse cx="0" cy="0" rx="46" ry="26"/>
                <path d="M-44 -4 C-56 -10 -70 -6 -72 2 C-70 8 -58 10 -46 6Z"/>
                <g class="tortuga-aleta"><path d="M-20 -18 C-30 -40 -50 -56 -66 -58 C-56 -44 -40 -28 -26 -14Z"/></g>
                <path d="M-26 -10 L-10 -18 L10 -18 L26 -10 L26 8 L10 16 L-10 16 L-26 8Z M-10 -18 L-6 0 L-10 16 M10 -18 L6 0 L10 16
                         M-26 -10 L-6 0 M26 -10 L6 0 M-26 8 L-6 0 M26 8 L6 0" fill="none" stroke="rgb(127 216 255 / .2)" stroke-width="1.6"/>
                <circle cx="-62" cy="-1" r="1.6" fill="rgb(234 246 255 / .6)"/>
            </g></g>

        {{-- ── 3. Medio ── --}}
        <g class="arrecife-medio" data-profundidad="0.12" style="--k: .45" fill="url(#arrecife-sombra-medio)" stroke="url(#arrecife-sombra-medio)" filter="url(#arrecife-borde)">
            <path d="M-80 420 L-80 300 C100 285 160 262 250 270 C330 278 370 300 450 292 C540 283 580 255 680 262 C770 269 820 298 910 290
                     C1000 282 1060 258 1150 265 C1240 272 1300 296 1520 285 L1520 420 Z" fill="#0C3474" stroke="none"/>
            <use href="#arrecife-domo" transform="translate(120 292) scale(.7)"/>
            <use href="#arrecife-blando" transform="translate(205 282) scale(.8)"/>
            <g transform="translate(300 274) scale(.8)"><g class="arrecife-mece" style="--t: 11s; --a: 2deg"><use href="#arrecife-abanico"/></g></g>
            <use href="#arrecife-mesa" transform="translate(430 294) scale(.9)"/>
            <g transform="translate(560 270) scale(.75)"><g class="arrecife-mece" style="--t: 7s; --a: 5deg"><use href="#arrecife-alga"/></g></g>
            <g transform="translate(588 268) scale(.6)"><g class="arrecife-mece" style="--t: 8.5s; --a: 6deg; --dl: -3s"><use href="#arrecife-alga"/></g></g>
            <use href="#arrecife-columnas" transform="translate(700 264) scale(.9)"/>
            <use href="#arrecife-alce" transform="translate(842 296) scale(.85)"/>
            <use href="#arrecife-cuerno" transform="translate(1010 270) scale(.85)"/>
            <use href="#arrecife-barril" transform="translate(1120 268) scale(.8)"/>
            <use href="#arrecife-tubos" transform="translate(1225 272) scale(.85)"/>
            <g transform="translate(1345 288) scale(.8)"><g class="arrecife-mece" style="--t: 9s; --a: 3.5deg; --dl: -6s"><use href="#arrecife-latigo"/></g></g>
        </g>

        {{-- ── 4. Cerca: las siluetas más oscuras ── --}}
        <g class="arrecife-cerca" data-profundidad="0.22" style="--k: .75" fill="url(#arrecife-sombra-cerca)" stroke="url(#arrecife-sombra-cerca)" filter="url(#arrecife-borde)">
            <path d="M-80 420 L-80 334 C90 324 150 306 230 312 C300 318 340 332 420 330 C500 328 560 312 640 316 C720 320 770 336 860 332
                     C950 328 1010 310 1100 314 C1190 318 1250 334 1330 328 C1380 324 1410 318 1520 320 L1520 420 Z" fill="#05183C" stroke="none"/>
            <g transform="translate(55 330) scale(.9)"><g class="arrecife-mece" style="--t: 8s; --a: 4deg; --dl: -1s"><use href="#arrecife-latigo"/></g></g>
            <use href="#arrecife-cuerno" transform="translate(150 318) scale(1.15)"/>
            <g transform="translate(262 318)"><g class="arrecife-mece" style="--t: 4.5s; --a: 7deg"><use href="#arrecife-anemona"/></g></g>
            {{-- Roca con estrella de mar y corales de copa --}}
            <path d="M300 334 C302 318 318 308 342 308 C366 308 384 318 386 334Z" stroke="none"/>
            <use href="#arrecife-estrella" class="arrecife-acento" transform="translate(340 311) rotate(-18) scale(.9)"/>
            <use href="#arrecife-copa" transform="translate(372 314) scale(1.1)"/>
            <use href="#arrecife-copa" transform="translate(384 324) scale(.8)"/>
            <use href="#arrecife-domo" transform="translate(452 334)"/>
            <use href="#arrecife-surcos" class="arrecife-textura" transform="translate(452 334)"/>
            <use href="#arrecife-erizo" transform="translate(548 330)"/>
            <use href="#arrecife-tubos" transform="translate(628 318)"/>
            <use href="#arrecife-lechuga" transform="translate(700 330) scale(.9)"/>
            <g transform="translate(770 324) scale(1.05)"><g class="arrecife-mece" style="--t: 13s; --a: 1.8deg; --dl: -5s"><use href="#arrecife-abanico"/></g></g>
            <g transform="translate(905 334) scale(1.05)"><g class="arrecife-mece" style="--t: 6.5s; --a: 6deg"><use href="#arrecife-alga"/></g></g>
            <g transform="translate(935 334) scale(.85)"><g class="arrecife-mece" style="--t: 7.5s; --a: 7deg; --dl: -2s"><use href="#arrecife-alga"/></g></g>
            <g transform="translate(962 332) scale(.65)"><g class="arrecife-mece" style="--t: 5.8s; --a: 8deg; --dl: -4s"><use href="#arrecife-alga"/></g></g>
            <g transform="translate(990 250) scale(.85)"><g class="arrecife-caballito"><use href="#arrecife-caballito"/></g></g>
            <use href="#arrecife-copa" transform="translate(1030 328)"/>
            <use href="#arrecife-copa" transform="translate(1044 330) scale(.75)"/>
            <use href="#arrecife-alce" transform="translate(1100 318)"/>
            {{-- Pulpo asomado detrás de una roca --}}
            <g transform="translate(1172 318)"><g class="arrecife-mece" style="--t: 6s; --a: 2.5deg"><use href="#arrecife-pulpo"/></g></g>
            <ellipse cx="1180" cy="324" rx="40" ry="14" stroke="none"/>
            <use href="#arrecife-blando" transform="translate(1250 330) scale(.75)"/>
            <use href="#arrecife-cuerno" transform="translate(1310 326) scale(.95)"/>
            <g transform="translate(1405 322) scale(.75)"><g class="arrecife-mece" style="--t: 5s; --a: 6deg; --dl: -2.5s"><use href="#arrecife-anemona"/></g></g>
            @foreach ([[236, 330, 9], [520, 338, 6], [600, 336, 8], [1060, 334, 7], [1240, 340, 10], [1370, 336, 6]] as [$cx, $cy, $r])
                <ellipse cx="{{ $cx }}" cy="{{ $cy }}" rx="{{ $r }}" ry="{{ round($r * .55, 1) }}" stroke="none"/>
            @endforeach
        </g>

        {{-- Peces junto a la anémona (dan vueltas cortas) --}}
        <g class="arrecife-acento">
            <g transform="translate(232 286) scale(.45)"><g class="arrecife-payaso"><use href="#arrecife-pez"/></g></g>
            <g transform="translate(286 276) scale(.4) scale(-1 1)"><g class="arrecife-payaso" style="--dl: -1.5s"><use href="#arrecife-pez"/></g></g>
        </g>

        {{-- ── 5. Primer plano: siluetas muy cercanas y desenfocadas en las esquinas ── --}}
        <g class="arrecife-frente" data-profundidad="0.3" style="--k: 1">
            <g transform="translate(-6 400) scale(1.9)"><g class="arrecife-mece" style="--t: 9s; --a: 3deg"><use href="#arrecife-alga"/></g></g>
            <g transform="translate(34 404) scale(1.45)"><g class="arrecife-mece" style="--t: 7s; --a: 4deg; --dl: -3s"><use href="#arrecife-alga"/></g></g>
            <use href="#arrecife-cuerno" transform="translate(1428 404) scale(1.7)"/>
            <g transform="translate(1370 408) scale(1.4)"><g class="arrecife-mece" style="--t: 8s; --a: 3.5deg; --dl: -5s"><use href="#arrecife-alga"/></g></g>
        </g>
    </svg>

    {{-- Destellos de luz sobre los corales --}}
    <div class="arrecife-luz absolute inset-x-0 bottom-0 h-64 md:h-[26rem]"></div>
</div>
