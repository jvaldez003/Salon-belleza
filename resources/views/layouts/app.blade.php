<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0A2F6B">
        <title>{{ $titulo ?? 'Panel' }} – {{ $configuracionSitio->nombre_negocio ?? 'Arrecife' }}</title>
        @include('partials.fuentes')
        @include('partials.iconos')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
        <style>[x-cloak] { display: none !important; }</style>
    </head>
    {{--
        Panel de Arrecife: menú lateral en azul abismo (fijo en escritorio, deslizable en el celular)
        y el contenido sobre "superficie". Las vistas siguen usando <x-app-layout> con el slot "header".
    --}}
    <body class="min-h-screen bg-superficie" x-data="{ menu: false }" @keydown.escape.window="menu = false">

        {{-- Barra superior (celular y tableta) --}}
        <div class="lg:hidden sticky top-0 z-40 bg-abismo text-white pt-[env(safe-area-inset-top)]">
            <div class="flex items-center justify-between gap-4 h-14 px-4">
                <a href="{{ route('dashboard') }}" aria-label="Inicio del panel"><x-marca tono="claro" :subtitulo="false" /></a>
                <button type="button" @click="menu = true" class="w-11 h-11 -mr-2 grid place-items-center rounded-full hover:bg-white/10"
                        aria-controls="menu-panel" :aria-expanded="menu.toString()" aria-label="Abrir menú">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Fondo oscuro detrás del menú en el celular --}}
        <div x-show="menu" x-cloak x-transition.opacity @click="menu = false" class="lg:hidden fixed inset-0 z-40 bg-abismo/60" aria-hidden="true"></div>

        {{-- Menú lateral --}}
        <aside id="menu-panel"
               class="fixed inset-y-0 left-0 z-50 w-72 lg:w-64 bg-abismo text-sobre-abismo-suave flex flex-col transition-transform duration-200 -translate-x-full lg:translate-x-0 pt-[env(safe-area-inset-top)] pb-[env(safe-area-inset-bottom)]"
               :class="menu && '!translate-x-0'">
            @include('layouts.navigation')
        </aside>

        {{-- Contenido --}}
        <div class="lg:pl-64 min-h-screen flex flex-col">
            @isset($header)
                <header class="border-b border-linea bg-superficie">
                    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-6 md:py-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
