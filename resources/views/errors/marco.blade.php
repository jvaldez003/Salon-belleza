{{--
    Marco de las páginas de error. No depende de la base de datos (puede fallar justo por eso).
    Cada página define: titulo, mensaje y, opcionalmente, acciones.
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('titulo') · Arrecife Beauty Studio</title>
    @include('partials.iconos')
    @include('partials.fuentes')
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-superficie">
    <main class="min-h-screen grid lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <div class="px-4 sm:px-10 py-12 grid content-center gap-8 max-w-xl">
            <a href="{{ url('/') }}" class="w-fit">
                <img src="{{ asset('img/logo-concha.png') }}" alt="Arrecife Beauty Studio, ir al inicio" width="171" height="160" class="h-12 w-auto">
            </a>
            <div class="grid gap-4">
                <p class="text-sm font-semibold text-oceano tabular">Error @yield('codigo')</p>
                <h1 class="text-[clamp(2.2rem,1.6rem+2.4vw,3.4rem)] leading-[1.05]">@yield('titulo')</h1>
                <p class="text-lg text-texto max-w-prose">@yield('mensaje')</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @hasSection('acciones')
                    @yield('acciones')
                @else
                    <a href="{{ url('/') }}" class="btn-primary">Ir al inicio</a>
                    <a href="javascript:history.back()" class="btn-secondary">Volver</a>
                @endif
            </div>
        </div>
        {{-- Panel de marca: el fondo del arrecife --}}
        <div class="hidden lg:block bg-abismo relative overflow-hidden" aria-hidden="true">
            <div class="absolute inset-0" style="background: radial-gradient(70% 50% at 30% 15%, rgb(127 216 255 / .35), transparent 70%), radial-gradient(60% 60% at 90% 100%, rgb(0 143 232 / .35), transparent 70%);"></div>
            <p class="absolute bottom-10 left-10 font-display italic text-6xl text-sobre-abismo/80">@yield('codigo')</p>
        </div>
    </main>
</body>
</html>
