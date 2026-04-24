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
            [x-cloak] { display: none !important; }
            .hide-scrollbar::-webkit-scrollbar { display: none; }
            .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            
            /* Sharp Slanted Clipping for the Text Area */
            .text-area-clip {
                clip-path: polygon(0 0, 100% 0, 90% 100%, 0% 100%);
            }
            
            @media (max-width: 1024px) {
                .text-area-clip { clip-path: none; }
            }
        </style>
    </head>
    <body class="antialiased bg-slate-50 text-slate-900 selection:bg-indigo-100 selection:text-indigo-700">
        <!-- Navigation -->
        <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
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

        <!-- Modern Slider Banner -->
        @if($banners->isNotEmpty())
            <header class="relative bg-slate-900 overflow-hidden min-h-[550px]" x-data="{ 
                active: 0, 
                count: {{ $banners->count() }},
                next() { this.active = (this.active + 1) % this.count },
                prev() { this.active = (this.active - 1 + this.count) % this.count },
                init() { setInterval(() => this.next(), 8000) }
            }">
                @foreach($banners as $index => $banner)
                    <div x-show="active === {{ $index }}" 
                         x-transition:enter="transition ease-out duration-1000"
                         x-transition:enter-start="opacity-0 translate-x-10"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-800"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-10"
                         class="absolute inset-0 flex flex-col lg:flex-row">
                        
                        <!-- Left: Text Area with Slanted Clip -->
                        <div class="w-full lg:w-[45%] bg-slate-900 text-area-clip z-20 flex items-center justify-center lg:justify-end px-10 py-20 lg:py-0">
                            <div class="max-w-xl lg:pr-20 text-center lg:text-left">
                                <span class="inline-block text-indigo-400 font-black uppercase tracking-[0.3em] text-[10px] mb-4">
                                    Excelencia Premium
                                </span>
                                <h1 class="text-5xl sm:text-7xl font-black text-white leading-[0.95] tracking-tighter mb-8">
                                    {{ $banner->titulo }}
                                </h1>
                                <p class="text-indigo-100/60 text-lg font-medium mb-10 max-w-md mx-auto lg:mx-0 leading-relaxed">
                                    {{ $banner->subtitulo }}
                                </p>
                                <div class="flex flex-col sm:flex-row items-center gap-6 justify-center lg:justify-start">
                                    @if($banner->texto_boton)
                                        <a href="{{ $banner->link_boton ?? '#servicios' }}" class="bg-indigo-600 text-white px-10 py-5 rounded-xl font-black text-lg hover:bg-indigo-500 transition shadow-2xl shadow-indigo-900/40">
                                            {{ $banner->texto_boton }}
                                        </a>
                                    @endif
                                    <div class="flex space-x-4">
                                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-white/40 hover:text-white transition">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        </div>
                                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-white/40 hover:text-white transition">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Full Coverage Image -->
                        <div class="absolute inset-0 lg:left-[40%] lg:right-0 z-10">
                            <img src="{{ asset('storage/' . $banner->imagen_url) }}" 
                                 class="w-full h-full object-cover"
                                 style="image-rendering: high-quality; -webkit-backface-visibility: hidden; backface-visibility: hidden;">
                            <!-- Subtle Gradient Overlay to blend with text area -->
                            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 via-transparent to-transparent lg:hidden"></div>
                        </div>
                    </div>
                @endforeach

                <!-- Slider Controls -->
                <template x-if="count > 1">
                    <div class="absolute bottom-10 right-10 z-30 flex space-x-3">
                        <button @click="prev()" class="w-12 h-12 bg-white/10 backdrop-blur hover:bg-white/20 text-white rounded-full flex items-center justify-center transition">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <button @click="next()" class="w-12 h-12 bg-white/10 backdrop-blur hover:bg-white/20 text-white rounded-full flex items-center justify-center transition">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </template>
                
                <!-- Indicators -->
                <template x-if="count > 1">
                    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-30 flex space-x-2">
                        <template x-for="i in count" :key="i">
                            <button @click="active = i-1" 
                                    class="h-1.5 rounded-full transition-all duration-300" 
                                    :class="active === i-1 ? 'w-8 bg-indigo-600' : 'w-2 bg-white/20'"></button>
                        </template>
                    </div>
                </template>
            </header>
        @else
            <header class="bg-indigo-900 py-32 text-center">
                <h1 class="text-5xl font-black text-white">Salón de Belleza</h1>
            </header>
        @endif

        <!-- Services Section - Slider -->
        <main id="servicios" class="py-24 bg-white" x-data="{ 
            scrollTo(direction) {
                const container = this.$refs.container;
                const scrollAmount = 400;
                container.scrollBy({ left: direction === 'next' ? scrollAmount : -scrollAmount, behavior: 'smooth' });
            }
        }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-end mb-16">
                    <div>
                        <span class="text-indigo-600 font-black uppercase tracking-[0.3em] text-[10px] mb-2 block italic">Nuestros Servicios</span>
                        <h2 class="text-5xl font-black text-slate-900 tracking-tighter">Catálogo Exclusivo</h2>
                    </div>
                    <div class="flex space-x-3">
                        <button @click="scrollTo('prev')" class="w-12 h-12 flex items-center justify-center bg-slate-50 rounded-full text-slate-400 hover:bg-indigo-600 hover:text-white transition shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <button @click="scrollTo('next')" class="w-12 h-12 flex items-center justify-center bg-slate-50 rounded-full text-slate-400 hover:bg-indigo-600 hover:text-white transition shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>

                <div x-ref="container" class="flex overflow-x-auto space-x-10 pb-10 hide-scrollbar snap-x snap-mandatory">
                    @foreach($servicios as $servicio)
                        <div class="flex-none w-[320px] snap-center group">
                            <div class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500">
                                <div class="relative h-64 bg-slate-100" x-data="{ active: 0, count: {{ $servicio->imagenes->count() }} }">
                                    <template x-for="(img, index) in {{ $servicio->imagenes->map(fn($i) => asset('storage/'.$i->url))->toJson() }}" :key="index">
                                        <div x-show="active === index" class="absolute inset-0">
                                            <img :src="img" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[1.5s]">
                                        </div>
                                    </template>

                                    @if($servicio->imagenes->count() == 0)
                                        <div class="absolute inset-0 flex items-center justify-center text-slate-200">
                                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                    @endif

                                    <div class="absolute inset-0 flex items-center justify-between px-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <button @click.stop="active = active === 0 ? count - 1 : active - 1" class="w-8 h-8 flex items-center justify-center bg-white/90 backdrop-blur rounded-full text-slate-900 shadow-lg hover:bg-indigo-600 hover:text-white transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
                                        </button>
                                        <button @click.stop="active = active === count - 1 ? 0 : active + 1" class="w-8 h-8 flex items-center justify-center bg-white/90 backdrop-blur rounded-full text-slate-900 shadow-lg hover:bg-indigo-600 hover:text-white transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="p-8">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-xl font-black text-slate-900 truncate pr-2">{{ $servicio->nombre }}</h3>
                                        <span class="text-indigo-600 font-black text-lg">${{ number_format($servicio->precio, 0) }}</span>
                                    </div>
                                    <p class="text-slate-400 text-xs font-medium line-clamp-2 h-8 mb-6">{{ $servicio->descripcion ?? 'Tratamiento premium personalizado.' }}</p>
                                    <a href="javascript:void(0)" class="block w-full text-center bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest py-4 rounded-xl hover:bg-indigo-600 transition shadow-lg shadow-slate-100 active:scale-95">
                                        Agendar Cita
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 py-16 text-center border-t border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <span class="text-2xl font-black text-white tracking-tighter block mb-4 uppercase">Peluquería<span class="text-indigo-500">App</span></span>
                <p class="text-slate-500 text-xs font-medium">© 2024 Innovación en Belleza. Medellín, Colombia.</p>
            </div>
        </footer>
    </body>
</html>
