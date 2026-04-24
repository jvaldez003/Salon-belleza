<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Salón de Belleza - Reserva tu Cita</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-slate-50">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center">
                        <span class="text-2xl font-black text-indigo-600 tracking-tighter">PELUQUERÍA<span class="text-slate-900">APP</span></span>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition">Entrar</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-md shadow-indigo-200">Registrarse</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="relative bg-indigo-900 py-24 overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <h1 class="text-4xl sm:text-6xl font-black text-white mb-6 tracking-tight">
                    Resalta tu Belleza <br><span class="text-indigo-400">Natural</span>
                </h1>
                <p class="text-lg text-indigo-100 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Experimenta el lujo y el cuidado que te mereces. Nuestros expertos están listos para transformar tu look con los mejores servicios de la ciudad.
                </p>
                <a href="#servicios" class="inline-block bg-white text-indigo-900 px-8 py-4 rounded-full font-black text-lg hover:bg-indigo-50 transition shadow-xl">
                    Ver Servicios
                </a>
            </div>
        </header>

        <!-- Services Section -->
        <main id="servicios" class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Nuestros Servicios</h2>
                    <p class="text-slate-500 max-w-lg mx-auto">Calidad excepcional en cada detalle. Elige el servicio perfecto para ti.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($servicios as $servicio)
                        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col">
                            <!-- Carousel Section -->
                            <div class="relative h-64 bg-slate-200 overflow-hidden" x-data="{ active: 0, count: {{ $servicio->imagenes->count() }} }">
                                @if($servicio->imagenes->count() > 0)
                                    @foreach($servicio->imagenes as $index => $imagen)
                                        <div x-show="active === {{ $index }}" 
                                             x-transition:enter="transition ease-out duration-500"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-300"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95"
                                             class="absolute inset-0 flex items-center justify-center bg-slate-50">
                                            <img src="{{ asset('storage/' . $imagen->url) }}" class="h-full w-full object-contain">
                                        </div>
                                    @endforeach
                                    
                                    <!-- Controls -->
                                    <template x-if="count > 1">
                                        <div class="absolute inset-0 flex items-center justify-between px-4 z-10">
                                            <button @click="active = active === 0 ? count - 1 : active - 1" class="p-2 bg-white/90 backdrop-blur rounded-full shadow-lg text-slate-900 hover:bg-white transition" aria-label="Anterior">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
                                            </button>
                                            <button @click="active = active === count - 1 ? 0 : active + 1" class="p-2 bg-white/90 backdrop-blur rounded-full shadow-lg text-slate-900 hover:bg-white transition" aria-label="Siguiente">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                                            </button>
                                        </div>
                                    </template>
                                    
                                    <!-- Indicators -->
                                    <template x-if="count > 1">
                                        <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2 z-10">
                                            <template x-for="i in count" :key="i">
                                                <button @click="active = i-1" class="h-1.5 rounded-full transition-all duration-300" :class="active === i-1 ? 'w-6 bg-white shadow-md' : 'w-1.5 bg-white/50'"></button>
                                            </template>
                                        </div>
                                    </template>
                                @else
                                    <div class="h-full w-full flex items-center justify-center">
                                        <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="p-8 flex-1 flex flex-col">
                                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $servicio->nombre }}</h3>
                                <p class="text-slate-500 text-sm mb-4 line-clamp-3">{{ $servicio->descripcion ?? 'Un servicio excepcional diseñado para tu bienestar.' }}</p>
                                
                                <div class="flex items-baseline space-x-1 mt-auto">
                                    <span class="text-3xl font-black text-indigo-600">${{ number_format($servicio->precio, 0) }}</span>
                                    <span class="text-slate-400 text-sm font-medium">/ servicio</span>
                                </div>
                                <hr class="my-6 border-slate-100">
                                <a href="{{ route('login') }}" class="block w-full text-center bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-slate-800 transition active:scale-[0.98]">
                                    Reservar Ahora
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 py-12 text-center border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <span class="text-xl font-black text-white tracking-tighter mb-4 block">PELUQUERÍA<span class="text-indigo-400">APP</span></span>
                <p class="text-slate-400 text-sm">© 2024 Salón de Belleza. Todos los derechos reservados.</p>
            </div>
        </footer>
    </body>
</html>
