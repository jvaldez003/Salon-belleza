<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Arrecife') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="antialiased bg-cream text-warm-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Decorative Background -->
            <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-rose-light/20 rounded-bl-[20rem] -z-10"></div>
            <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-gold-light/10 rounded-tr-[20rem] -z-10"></div>

            <div class="absolute top-8 left-8">
                <a href="/" class="flex items-center text-warm-400 hover:text-rose-dark transition-colors font-bold text-xs uppercase tracking-widest group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Volver al Inicio
                </a>
            </div>

            <div class="mb-10 text-center">
                <a href="/">
                    <span class="text-4xl font-black text-rose-dark tracking-tighter uppercase">Arrecife</span>
                    <span class="text-4xl font-black text-warm-900 tracking-tighter"> Beauty</span>
                </a>
                <p class="text-warm-400 text-xs font-bold uppercase tracking-[0.3em] mt-2">Studio & Spa</p>
            </div>

            <div class="w-full sm:max-w-md bg-white p-10 shadow-xl shadow-warm-200/50 rounded-[2.5rem] border border-warm-200">
                {{ $slot }}
            </div>

            <footer class="mt-10 text-warm-400 text-xs font-bold uppercase tracking-widest">
                © {{ date('Y') }} Arrecife Beauty Studio. Todos los derechos reservados.
            </footer>
        </div>
    </body>
</html>
