<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#F8FCFF">
        <title>{{ $titulo ?? 'Tu cuenta' }} – {{ $configuracionSitio->nombre_negocio ?? 'Arrecife' }} Beauty Studio</title>
        @include('partials.fuentes')
        @include('partials.iconos')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak] { display: none !important; }</style>
    </head>
    {{--
        Acceso (entrar, crear cuenta, recuperar contraseña).
        Escritorio: foto del estudio a la izquierda (azul abismo) y el formulario a la derecha.
        Celular: solo el formulario, con la marca arriba.
    --}}
    <body class="min-h-screen bg-superficie">
        <div class="min-h-screen grid lg:grid-cols-[minmax(0,5fr)_minmax(0,6fr)]">

            {{-- Foto del estudio --}}
            <div class="hidden lg:block relative bg-abismo overflow-hidden">
                @if (! empty($configuracionSitio->quienes_somos_imagen))
                    <img src="{{ asset('storage/' . $configuracionSitio->quienes_somos_imagen) }}" alt=""
                         class="absolute inset-0 w-full h-full object-cover object-[50%_30%] opacity-80">
                @endif
                <div class="absolute inset-0 bg-gradient-to-b from-abismo/85 via-abismo/25 via-40% to-abismo" aria-hidden="true"></div>
                <div class="relative h-full flex flex-col justify-between p-12">
                    <a href="{{ url('/') }}" aria-label="Ir al sitio de Arrecife"><x-marca tono="claro" /></a>
                    <p class="font-display italic text-[1.75rem] leading-snug text-white max-w-[24ch]">
                        Tu lugar para desconectarte del mundo y salir en tu mejor versión.
                    </p>
                </div>
            </div>

            {{-- Formulario --}}
            <div class="flex flex-col px-4 sm:px-6 pt-[max(1.5rem,env(safe-area-inset-top))] pb-[max(1.5rem,env(safe-area-inset-bottom))]">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ url('/') }}" class="ml-auto inline-flex items-center gap-2 text-sm font-medium text-texto hover:text-oceano transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Volver al sitio
                    </a>
                </div>

                <main class="flex-1 grid place-items-center py-8">
                    <div class="w-full max-w-[26rem]">
                        {{-- Logo completo (vertical): solo sobre fondo claro, su texto es negro --}}
                        <a href="{{ url('/') }}" class="block w-fit mb-8" aria-label="Ir al sitio de Arrecife">
                            <img src="{{ asset('img/logo-completo.png') }}" alt="Arrecife Beauty Studio" width="527" height="600" class="h-28 w-auto">
                        </a>
                        {{ $slot }}
                    </div>
                </main>

                <p class="text-xs text-texto-suave text-center">© {{ date('Y') }} {{ $configuracionSitio->nombre_negocio ?? 'Arrecife' }} Beauty Studio</p>
            </div>
        </div>
    </body>
</html>
