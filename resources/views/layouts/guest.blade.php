<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PeluqueríaApp') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Outfit', sans-serif; }
        </style>
    </head>
    <body class="antialiased bg-slate-50 text-slate-900 selection:bg-indigo-100 selection:text-indigo-700">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Decorative Background -->
            <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-indigo-100/30 rounded-bl-[20rem] -z-10"></div>
            <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-indigo-50/50 rounded-tr-[20rem] -z-10"></div>

            <div class="absolute top-8 left-8">
                <a href="/" class="flex items-center text-slate-400 hover:text-indigo-600 transition-colors font-black text-[10px] uppercase tracking-widest group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Volver al Inicio
                </a>
            </div>

            <div class="mb-10 text-center animate-fade-in">
                <a href="/">
                    <span class="text-4xl font-black text-indigo-600 tracking-tighter">PELUQUERÍA<span class="text-slate-900">APP</span></span>
                </a>
                <p class="text-slate-400 text-xs font-black uppercase tracking-[0.3em] mt-2">Premium Experience</p>
            </div>

            <div class="w-full sm:max-w-md bg-white p-10 shadow-2xl shadow-indigo-100 rounded-[3rem] border border-slate-100 animate-fade-in">
                {{ $slot }}
            </div>
            
            <footer class="mt-10 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                © 2024 Salón de Belleza. Calidad y Estilo.
            </footer>
        </div>

        <style>
            @keyframes fade-in {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in { animation: fade-in 1s ease-out forwards; }
        </style>
    </body>
</html>
