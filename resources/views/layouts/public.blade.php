<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="theme-color" content="#F8FCFF">
        <title>@yield('title', $configuracionSitio->nombre_negocio ?? 'Arrecife') – @yield('subtitle', 'Arrecife Beauty Studio')</title>
        @include('partials.fuentes')
        @include('partials.iconos')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak] { display: none !important; }</style>
        <script>document.documentElement.classList.add('js')</script>
        @stack('styles')
    </head>
    <body class="min-h-screen flex flex-col">

        @php
            $whatsappNumero = $configuracionSitio->whatsapp_numero;
            // Íconos de redes en un solo color (el de la marca), no con los colores de cada red
            $iconos = [
                'whatsapp'  => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z',
                'instagram' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z',
                'facebook'  => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
                'tiktok'    => 'M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.75a4.85 4.85 0 01-1.01-.06z',
                'youtube'   => 'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z',
            ];
            $redes = array_filter([
                'Instagram' => ['url' => $configuracionSitio->instagram, 'icono' => $iconos['instagram']],
                'Facebook'  => ['url' => $configuracionSitio->facebook,  'icono' => $iconos['facebook']],
                'TikTok'    => ['url' => $configuracionSitio->tiktok,    'icono' => $iconos['tiktok']],
                'YouTube'   => ['url' => $configuracionSitio->youtube,   'icono' => $iconos['youtube']],
            ], fn ($r) => ! empty($r['url']));

            $enlaces = [['Servicios', url('/') . '#servicios', request()->is('/')]];
            if ($tieneProtocolos ?? false) $enlaces[] = ['Protocolos', route('protocolos.publico'), request()->routeIs('protocolos.publico')];
            if ($tieneNosotros ?? false)   $enlaces[] = ['Quiénes somos', route('nosotros'), request()->routeIs('nosotros')];
        @endphp

        {{-- ── Encabezado ─────────────────────────────────────── --}}
        <header class="sticky top-0 z-50 bg-superficie/90 backdrop-blur-md border-b border-linea pt-[env(safe-area-inset-top)]"
                x-data="{ abierto: false }" @keydown.escape.window="abierto = false">
            <div class="max-w-arrecife mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between gap-6 h-[72px]">

                    <a href="{{ url('/') }}" class="shrink-0" aria-label="{{ $configuracionSitio->nombre_negocio ?? 'Arrecife' }}, inicio">
                        <x-marca />
                    </a>

                    <nav class="hidden md:flex items-center gap-8 text-[0.9375rem] font-medium" aria-label="Principal">
                        @foreach ($enlaces as [$texto, $href, $activo])
                            <a href="{{ $href }}"
                               @if ($activo && $texto !== 'Servicios') aria-current="page" @endif
                               @class(['transition-colors hover:text-oceano', 'text-oceano' => $activo && $texto !== 'Servicios', 'text-texto' => ! ($activo && $texto !== 'Servicios')])>{{ $texto }}</a>
                        @endforeach
                    </nav>

                    <div class="flex items-center gap-4 shrink-0">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="hidden md:inline text-[0.9375rem] font-medium text-texto hover:text-oceano transition-colors">Mi cuenta</a>
                        @else
                            <a href="{{ route('login') }}" class="hidden md:inline text-[0.9375rem] font-medium text-texto hover:text-oceano transition-colors">Entrar</a>
                        @endauth
                        <x-boton :href="$urlReserva" class="hidden sm:inline-flex">Reservar cita</x-boton>

                        <button type="button" @click="abierto = !abierto"
                                class="md:hidden w-11 h-11 -mr-2 flex items-center justify-center rounded-full text-tinta hover:bg-bruma transition-colors"
                                :aria-expanded="abierto.toString()" aria-controls="menu-movil"
                                :aria-label="abierto ? 'Cerrar menú' : 'Abrir menú'">
                            <svg x-show="!abierto" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                            <svg x-show="abierto" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Menú móvil --}}
            <div id="menu-movil" x-show="abierto" x-cloak
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-end="opacity-0"
                 class="md:hidden border-t border-linea bg-superficie">
                <nav class="max-w-arrecife mx-auto px-4 sm:px-6 py-4 flex flex-col" aria-label="Principal móvil">
                    @foreach ($enlaces as [$texto, $href])
                        <a href="{{ $href }}" @click="abierto = false"
                           class="py-3 font-display text-2xl text-tinta border-b border-linea/70">{{ $texto }}</a>
                    @endforeach
                    @auth
                        <a href="{{ url('/dashboard') }}" class="py-3 text-texto font-medium">Mi cuenta</a>
                    @else
                        <a href="{{ route('login') }}" class="py-3 text-texto font-medium">Entrar</a>
                    @endauth
                    <x-boton :href="$urlReserva" class="mt-2 w-full">Reservar cita</x-boton>
                </nav>
            </div>
        </header>

        {{-- ── Contenido ──────────────────────────────────────── --}}
        {{-- El último bloque se estira hasta el pie, para que no quede un hueco en páginas cortas --}}
        <div class="flex-1 flex flex-col [&>*:last-child]:flex-1">
            @yield('content')
        </div>

        @yield('cta')

        {{-- ── Pie de página (abismo) ─────────────────────────── --}}
        <footer data-raton-profundidad class="relative isolate overflow-hidden bg-gradient-to-b from-abismo from-40% to-abismo-fondo text-sobre-abismo-suave pb-[env(safe-area-inset-bottom)]">
            {{-- El fondo del mar: el final del descenso. Burbujas que suben desde el arrecife. --}}
            <x-arrecife-fondo />
            <x-abismo-luz :rayos="false" :particulas="12" />
            {{-- Velo detrás del texto: el arrecife lo rodea pero la información siempre se lee --}}
            <div class="arrecife-velo absolute inset-0 pointer-events-none" aria-hidden="true"></div>
            <div class="relative z-10 max-w-arrecife mx-auto px-4 sm:px-6">
                <div class="py-14 grid gap-10 sm:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr]">

                    <div class="grid gap-5 content-start" data-revelar="sube">
                        <a href="{{ url('/') }}" aria-label="Inicio"><x-marca tono="claro" /></a>
                        @if ($configuracionSitio->eslogan)
                            <p class="max-w-[36ch] text-[0.9375rem] leading-relaxed">{{ Str::legible($configuracionSitio->eslogan) }}</p>
                        @endif
                        @if (count($redes))
                            <div class="flex items-center gap-2">
                                @foreach ($redes as $nombre => $red)
                                    <a href="{{ $red['url'] }}" target="_blank" rel="noopener"
                                       class="w-10 h-10 rounded-full border border-sobre-abismo-suave/30 flex items-center justify-center text-agua hover:bg-agua hover:text-abismo hover:border-agua transition-colors"
                                       aria-label="{{ $nombre }} de Arrecife">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $red['icono'] }}"/></svg>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <nav aria-label="Pie de página" data-revelar="sube" style="--d: 120ms">
                        <p class="font-display italic text-lg text-white mb-4">Explorar</p>
                        <ul class="grid gap-2.5 text-[0.9375rem]">
                            @foreach ($enlaces as [$texto, $href])
                                <li><a href="{{ $href }}" class="hover:text-white transition-colors">{{ $texto }}</a></li>
                            @endforeach
                            <li><a href="{{ $urlReserva }}" class="hover:text-white transition-colors">Reservar cita</a></li>
                        </ul>
                    </nav>

                    <div data-revelar="sube" style="--d: 240ms">
                        <p class="font-display italic text-lg text-white mb-4">Contacto</p>
                        <ul class="grid gap-3 text-[0.9375rem]">
                            @if ($whatsappNumero)
                                <li>
                                    <a href="https://wa.me/{{ $whatsappNumero }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2.5 hover:text-white transition-colors">
                                        <svg class="w-4 h-4 text-agua shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $iconos['whatsapp'] }}"/></svg>
                                        WhatsApp {{ $configuracionSitio->whatsapp }}
                                    </a>
                                </li>
                            @endif
                            @if ($configuracionSitio->telefono)
                                <li>Teléfono {{ $configuracionSitio->telefono }}</li>
                            @endif
                            @if ($configuracionSitio->email_contacto)
                                <li class="break-all">{{ $configuracionSitio->email_contacto }}</li>
                            @endif
                            @if ($configuracionSitio->direccion)
                                <li>{{ $configuracionSitio->direccion }}</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="h-px bg-sobre-abismo-suave/20" data-revelar="linea" style="--d: 300ms" aria-hidden="true"></div>
                <div class="py-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs text-sobre-abismo-suave/80">
                    <p>© {{ date('Y') }} {{ $configuracionSitio->nombre_negocio ?? 'Arrecife' }} Beauty Studio</p>
                    @guest
                        <a href="{{ route('login') }}" class="hover:text-white transition-colors">Acceso del equipo</a>
                    @endguest
                </div>
                {{-- Espacio para que el arrecife se vea completo debajo del texto --}}
                <div class="h-40 md:h-72" aria-hidden="true"></div>
            </div>
        </footer>

        {{-- ── Botón flotante de WhatsApp ─────────────────────── --}}
        @if ($whatsappNumero && ! View::hasSection('sin_whatsapp_flotante'))
            <a href="https://wa.me/{{ $whatsappNumero }}?text={{ urlencode('Hola, me gustaría reservar una cita') }}"
               target="_blank" rel="noopener"
               class="fixed z-40 right-[max(1.25rem,env(safe-area-inset-right))] bottom-[max(1.25rem,env(safe-area-inset-bottom))] w-14 h-14 rounded-full bg-oceano text-white flex items-center justify-center shadow-[0_12px_30px_-8px_rgb(10_47_107/.6)] hover:bg-abismo transition-colors"
               aria-label="Escribir por WhatsApp para reservar">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $iconos['whatsapp'] }}"/></svg>
            </a>
        @endif

    </body>
</html>
