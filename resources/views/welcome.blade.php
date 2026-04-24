<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Salón de Belleza - Reserva tu Cita</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
            [x-cloak] { display: none !important; }
            .hide-scrollbar::-webkit-scrollbar { display: none; }
            .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    </head>
    <body class="antialiased bg-slate-50 text-slate-900 selection:bg-indigo-100 selection:text-indigo-700">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center">
                        <span class="text-3xl font-black text-indigo-600 tracking-tighter">PELUQUERÍA<span class="text-slate-900">APP</span></span>
                    </div>
                    <div class="flex items-center space-x-6">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Entrar</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl text-sm font-black hover:bg-indigo-700 transition shadow-xl shadow-indigo-100 active:scale-95">Registrarse</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Dynamic Hero Banner -->
        @if($banner)
            <header class="relative min-h-[70vh] flex items-center overflow-hidden bg-white py-16">
                <div class="absolute top-0 right-0 w-1/3 h-full bg-indigo-50/50 rounded-l-[5rem] -z-10 translate-x-10"></div>
                
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="space-y-6 text-center lg:text-left">
                        <h1 class="text-5xl sm:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                            {{ $banner->titulo }}
                        </h1>
                        <p class="text-lg text-slate-500 max-w-xl mx-auto lg:mx-0 font-medium leading-relaxed">
                            {{ $banner->subtitulo }}
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                            @if($banner->texto_boton)
                                <a href="{{ $banner->link_boton ?? '#servicios' }}" class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-black text-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                                    {{ $banner->texto_boton }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="relative">
                        <div class="bg-slate-100 rounded-[3rem] overflow-hidden shadow-2xl">
                            <img src="{{ asset('storage/' . $banner->imagen_url) }}" class="w-full h-[400px] object-cover">
                        </div>
                    </div>
                </div>
            </header>
        @endif

        <!-- Services Section - Minimalist & Compact -->
        <main id="servicios" class="py-24 bg-slate-50" x-data="{ 
            scroll: 0,
            scrollTo(direction) {
                const container = this.$refs.container;
                const scrollAmount = 400;
                container.scrollBy({ left: direction === 'next' ? scrollAmount : -scrollAmount, behavior: 'smooth' });
            }
        }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-12">
                    <div>
                        <span class="text-indigo-600 font-black uppercase tracking-[0.2em] text-[10px] mb-2 block">Lo que hacemos</span>
                        <h2 class="text-4xl font-black text-slate-900">Nuestros Servicios</h2>
                    </div>
                    <!-- Slider Controls -->
                    <div class="hidden md:flex space-x-2">
                        <button @click="scrollTo('prev')" class="p-4 bg-white border border-slate-100 rounded-2xl text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition shadow-sm">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <button @click="scrollTo('next')" class="p-4 bg-white border border-slate-100 rounded-2xl text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition shadow-sm">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Horizontal Scroll Container -->
                <div x-ref="container" class="flex overflow-x-auto space-x-8 pb-10 hide-scrollbar snap-x snap-mandatory">
                    @foreach($servicios as $servicio)
                        <div class="flex-none w-[350px] snap-center group">
                            <div class="bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500">
                                <!-- Card Image with Hover Carousel -->
                                <div class="relative h-64 bg-slate-100" x-data="{ active: 0, count: {{ $servicio->imagenes->count() }} }">
                                    <!-- Perfect Fit Image -->
                                    <template x-for="(img, index) in {{ $servicio->imagenes->map(fn($i) => asset('storage/'.$i->url))->toJson() }}" :key="index">
                                        <div x-show="active === index" class="absolute inset-0">
                                            <img :src="img" class="w-full h-full object-cover">
                                        </div>
                                    </template>

                                    @if($servicio->imagenes->count() == 0)
                                        <div class="absolute inset-0 flex items-center justify-center text-slate-200">
                                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                    @endif

                                    <!-- Hover Arrows -->
                                    <div class="absolute inset-0 flex items-center justify-between px-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                        <button @click.stop="active = active === 0 ? count - 1 : active - 1" class="p-2 bg-white/90 backdrop-blur rounded-full text-slate-900 shadow-lg pointer-events-auto hover:bg-indigo-600 hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
                                        </button>
                                        <button @click.stop="active = active === count - 1 ? 0 : active + 1" class="p-2 bg-white/90 backdrop-blur rounded-full text-slate-900 shadow-lg pointer-events-auto hover:bg-indigo-600 hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                                        </button>
                                    </div>

                                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-black text-slate-900 tracking-widest uppercase shadow-sm">
                                        Premium
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="p-8">
                                    <h3 class="text-xl font-black text-slate-900 mb-2 truncate">{{ $servicio->nombre }}</h3>
                                    <p class="text-slate-500 text-xs font-medium line-clamp-2 mb-6 h-8">{{ $servicio->descripcion ?? 'Tratamiento exclusivo personalizado.' }}</p>
                                    
                                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                                        <span class="text-2xl font-black text-indigo-600">${{ number_format($servicio->precio, 0) }}</span>
                                        <a href="{{ route('login') }}" class="bg-slate-900 text-white text-xs font-bold px-6 py-3 rounded-xl hover:bg-indigo-600 transition shadow-md shadow-slate-100">
                                            Reservar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white py-12 border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="text-2xl font-black text-slate-900 tracking-tighter block mb-2">PELUQUERÍA<span class="text-indigo-600">APP</span></span>
                <p class="text-slate-400 text-xs font-medium">© 2024 Salón de Belleza. Calidad superior.</p>
            </div>
        </footer>
    </body>
</html>
