<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $configuracionSitio->nombre_negocio ?? 'Arrecife' }} – Reserva tu Cita</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Poppins', sans-serif; }
            h1, h2, h3, h4, h5 { font-family: 'Playfair Display', serif; }
            [x-cloak] { display: none !important; }
            .hide-scrollbar::-webkit-scrollbar { display: none; }
            .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

            .text-area-clip {
                clip-path: polygon(0 0, 100% 0, 90% 100%, 0% 100%);
            }
            @media (max-width: 1024px) {
                .text-area-clip { clip-path: none; }
            }
        </style>
    </head>
    <body class="antialiased bg-[#F8FCFF] text-[#1F2937]">

        <!-- ── NAVBAR ──────────────────────────────────────────── -->
        <nav class="bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b border-[#D9EAF2]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center">
                        @if(!empty($configuracionSitio->logo))
                            <img src="{{ asset('storage/'.$configuracionSitio->logo) }}"
                                 class="h-12 w-auto object-contain"
                                 alt="{{ $configuracionSitio->nombre_negocio }}">
                        @else
                            <span class="text-2xl font-black tracking-tight uppercase" style="color:#0A2F6B; font-family:'Playfair Display',serif;">
                                {{ $configuracionSitio->nombre_negocio ?? 'Arrecife' }}
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-5">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="text-sm font-semibold text-[#6B7280] hover:text-[#0A2F6B] transition">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="text-sm font-semibold text-[#6B7280] hover:text-[#0A2F6B] transition">
                                    Entrar
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                       class="text-white text-sm font-semibold px-6 py-2.5 rounded-2xl transition active:scale-95 shadow-lg"
                                       style="background: linear-gradient(135deg, #0A2F6B 0%, #0E4A9E 45%, #008FE8 100%);">
                                        Reservar cita
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- ── HERO / SLIDER BANNER ────────────────────────────── -->
        @if($banners->isNotEmpty())
            <header class="relative overflow-hidden min-h-[580px]"
                    style="background:#0A2F6B;"
                    x-data="{
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
                         x-transition:leave="transition ease-in duration-700"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-10"
                         class="absolute inset-0 flex flex-col lg:flex-row">

                        <!-- Texto -->
                        <div class="w-full lg:w-[45%] text-area-clip z-20 flex items-center justify-center lg:justify-end px-10 py-20 lg:py-0"
                             style="background: linear-gradient(135deg, #0A2F6B 0%, #0E4A9E 45%, #008FE8 100%);">
                            <div class="max-w-xl lg:pr-20 text-center lg:text-left">
                                <span class="inline-block font-semibold uppercase tracking-[0.3em] text-[10px] mb-4"
                                      style="color:#7FD8FF;">
                                    Arrecife Beauty Studio
                                </span>
                                <h1 class="text-5xl sm:text-7xl font-black text-white leading-[1.05] mb-8">
                                    {{ $banner->titulo }}
                                </h1>
                                <p class="text-[#7FD8FF]/70 text-lg font-light mb-10 max-w-md mx-auto lg:mx-0 leading-relaxed">
                                    {{ $banner->subtitulo }}
                                </p>
                                <div class="flex flex-col sm:flex-row items-center gap-5 justify-center lg:justify-start">
                                    @if($banner->texto_boton)
                                        <a href="{{ $banner->link_boton ?? '#servicios' }}"
                                           class="bg-white text-[#0A2F6B] px-10 py-4 rounded-2xl font-bold text-base hover:bg-[#EBF5FC] transition shadow-xl active:scale-95">
                                            {{ $banner->texto_boton }}
                                        </a>
                                    @endif
                                    <div class="flex space-x-3">
                                        <div class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/50 hover:text-white hover:border-white/50 transition cursor-pointer">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        </div>
                                        <div class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/50 hover:text-white hover:border-white/50 transition cursor-pointer">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Imagen -->
                        <div class="absolute inset-0 lg:left-[40%] lg:right-0 z-10">
                            <img src="{{ asset('storage/' . $banner->imagen_url) }}"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-r from-[#0A2F6B]/70 via-transparent to-transparent lg:hidden"></div>
                        </div>
                    </div>
                @endforeach

                <!-- Controles -->
                <template x-if="count > 1">
                    <div class="absolute bottom-8 right-8 z-30 flex space-x-3">
                        <button @click="prev()" class="w-11 h-11 bg-white/10 backdrop-blur hover:bg-white/25 text-white rounded-full flex items-center justify-center transition border border-white/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="next()" class="w-11 h-11 bg-white/10 backdrop-blur hover:bg-white/25 text-white rounded-full flex items-center justify-center transition border border-white/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </template>

                <!-- Indicadores -->
                <template x-if="count > 1">
                    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 flex space-x-2">
                        <template x-for="i in count" :key="i">
                            <button @click="active = i-1"
                                    class="h-1.5 rounded-full transition-all duration-300"
                                    :class="active === i-1 ? 'w-8 bg-[#008FE8]' : 'w-2 bg-white/25'"></button>
                        </template>
                    </div>
                </template>
            </header>
        @else
            <header class="py-32 text-center" style="background: linear-gradient(135deg, #0A2F6B 0%, #0E4A9E 45%, #008FE8 100%);">
                <h1 class="text-5xl font-black text-white">{{ $configuracionSitio->nombre_negocio ?? 'Arrecife Beauty Studio' }}</h1>
                <p class="text-[#7FD8FF] mt-4 text-lg font-light">Elegancia y bienestar a tu medida</p>
            </header>
        @endif

        <!-- ── SERVICIOS ──────────────────────────────────────── -->
        <main id="servicios" class="py-24 bg-white"
              x-data="{ cat: 'all' }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="mb-10">
                    <span class="font-semibold uppercase tracking-[0.3em] text-[10px] mb-2 block" style="color:#008FE8;">
                        Nuestros Servicios
                    </span>
                    <h2 class="text-5xl font-black text-[#1F2937] mb-8">Catálogo Exclusivo</h2>

                    {{-- Filtro por categorías --}}
                    @if($categorias->isNotEmpty())
                    <div class="flex flex-wrap gap-3">
                        <button @click="cat = 'all'"
                                :class="cat === 'all'
                                    ? 'text-white shadow-md'
                                    : 'bg-white text-[#6B7280] border border-[#D9EAF2] hover:border-[#008FE8] hover:text-[#0A2F6B]'"
                                class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200"
                                :style="cat === 'all' ? 'background: linear-gradient(135deg, #0A2F6B, #008FE8)' : ''">
                            Todos
                        </button>
                        @foreach($categorias as $c)
                        <button @click="cat = '{{ $c->id }}'"
                                :class="cat === '{{ $c->id }}'
                                    ? 'text-white shadow-md'
                                    : 'bg-white text-[#6B7280] border border-[#D9EAF2] hover:border-[#008FE8] hover:text-[#0A2F6B]'"
                                class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200"
                                :style="cat === '{{ $c->id }}' ? 'background: linear-gradient(135deg, #0A2F6B, #008FE8)' : ''">
                            {{ $c->nombre }}
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($servicios as $servicio)
                    <div x-show="cat === 'all' || cat === '{{ $servicio->categoria_id ?? '' }}'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="group">
                        <div class="bg-white rounded-[2rem] overflow-hidden border border-[#D9EAF2] shadow-sm hover:shadow-xl hover:shadow-[#D9EAF2] transition-all duration-500 h-full flex flex-col">

                            {{-- Imágenes --}}
                            <div class="relative h-56 bg-[#EBF5FC] shrink-0"
                                 x-data="{ active: 0, count: {{ $servicio->imagenes->count() }} }">
                                <template x-for="(img, index) in {{ $servicio->imagenes->map(fn($i) => asset('storage/'.$i->url))->toJson() }}" :key="index">
                                    <div x-show="active === index" class="absolute inset-0">
                                        <img :src="img" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    </div>
                                </template>
                                @if($servicio->imagenes->count() == 0)
                                    @if($servicio->categoria?->imagen)
                                    <div class="absolute inset-0">
                                        <img src="{{ asset('storage/'.$servicio->categoria->imagen) }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    </div>
                                    @else
                                    <div class="absolute inset-0 flex items-center justify-center"
                                         style="background:linear-gradient(135deg,#0A2F6B22,#008FE822)">
                                        <svg class="w-12 h-12 text-[#7FD8FF]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    @endif
                                @endif
                                @if($servicio->imagenes->count() > 1)
                                <div class="absolute inset-0 flex items-center justify-between px-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <button @click.stop="active = active === 0 ? count - 1 : active - 1"
                                            class="w-7 h-7 flex items-center justify-center bg-white/90 rounded-full text-[#0A2F6B] shadow hover:bg-[#0A2F6B] hover:text-white transition">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <button @click.stop="active = active === count - 1 ? 0 : active + 1"
                                            class="w-7 h-7 flex items-center justify-center bg-white/90 rounded-full text-[#0A2F6B] shadow hover:bg-[#0A2F6B] hover:text-white transition">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                                @endif
                                {{-- Badge de categoría --}}
                                @if($servicio->categoria)
                                <span class="absolute top-3 left-3 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full bg-white/90 backdrop-blur"
                                      style="color:#0A2F6B;">
                                    {{ $servicio->categoria->nombre }}
                                </span>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-6 flex flex-col flex-1">
                                <div class="flex justify-between items-start mb-2 flex-1">
                                    <h3 class="text-base font-black text-[#1F2937] leading-snug pr-2">{{ $servicio->nombre }}</h3>
                                    <span class="font-bold text-base shrink-0" style="color:#008FE8;">${{ number_format($servicio->precio, 0) }}</span>
                                </div>
                                <p class="text-[#6B7280] text-xs font-normal line-clamp-2 mb-5 leading-relaxed">
                                    {{ $servicio->descripcion ?? 'Tratamiento premium personalizado.' }}
                                </p>
                                @auth
                                <a href="{{ route('citas.create') }}"
                                   class="block w-full text-center text-white text-xs font-semibold uppercase tracking-widest py-3 rounded-xl transition active:scale-95"
                                   style="background: linear-gradient(135deg, #0A2F6B 0%, #0E4A9E 45%, #008FE8 100%);">
                                    Agendar Cita
                                </a>
                                @else
                                <a href="{{ route('login') }}"
                                   class="block w-full text-center text-white text-xs font-semibold uppercase tracking-widest py-3 rounded-xl transition active:scale-95"
                                   style="background: linear-gradient(135deg, #0A2F6B 0%, #0E4A9E 45%, #008FE8 100%);">
                                    Agendar Cita
                                </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </main>

        <!-- ── PROTOCOLOS ──────────────────────────────────────── -->
        @if($protocolos->isNotEmpty())
        <section id="protocolos" class="py-24 bg-[#F8FCFF]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="mb-16">
                    <span class="font-semibold uppercase tracking-[0.3em] text-[10px] block mb-2" style="color:#008FE8;">
                        Procedimientos especializados
                    </span>
                    <h2 class="text-5xl font-black text-[#1F2937]">Protocolos</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($protocolos as $protocolo)
                    <div class="bg-white rounded-[2rem] overflow-hidden border border-[#D9EAF2] shadow-sm hover:shadow-xl transition-all duration-500">

                        {{-- Slider de medios --}}
                        @if($protocolo->medios->isNotEmpty())
                        <div x-data="{ active: 0, total: {{ $protocolo->medios->count() }}, init() { if(this.total > 1) setInterval(() => { this.active = (this.active + 1) % this.total }, 6000) } }"
                             class="relative">
                            @foreach($protocolo->medios as $i => $medio)
                            <div x-show="active === {{ $i }}"
                                 x-transition:enter="transition ease-out duration-600"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100">
                                @if($medio->tipo === 'imagen')
                                <img src="{{ asset('storage/'.$medio->url) }}"
                                     class="w-full h-auto block"
                                     alt="{{ $protocolo->nombre }}">
                                @elseif($medio->esUrlExterna())
                                <div class="aspect-video">
                                    <iframe src="{{ $medio->url }}"
                                            class="w-full h-full"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                            loading="lazy"></iframe>
                                </div>
                                @else
                                <video class="w-full h-auto block" controls preload="metadata">
                                    <source src="{{ asset('storage/'.$medio->url) }}" type="video/mp4">
                                </video>
                                @endif
                            </div>
                            @endforeach

                            @if($protocolo->medios->count() > 1)
                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
                                @foreach($protocolo->medios as $i => $medio)
                                <button @click="active = {{ $i }}"
                                        class="h-1.5 rounded-full transition-all duration-300"
                                        :class="active === {{ $i }} ? 'w-5 bg-[#008FE8]' : 'w-1.5 bg-[#D9EAF2]'"></button>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="h-40 flex items-center justify-center" style="background: linear-gradient(135deg, #0A2F6B 0%, #008FE8 100%);">
                            <svg class="w-12 h-12 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        @endif

                        {{-- Texto --}}
                        <div class="p-6">
                            <h3 class="text-xl font-black text-[#1F2937] mb-2">{{ $protocolo->nombre }}</h3>
                            @if($protocolo->descripcion)
                            <p class="text-[#6B7280] text-sm leading-relaxed">{{ $protocolo->descripcion }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </section>
        @endif

        <!-- ── QUIÉNES SOMOS / MISIÓN / VISIÓN ────────────────── -->
        @php
            // Combina imágenes de la galería nueva; si no hay, usa la imagen legacy del config
            $imgQS = $seccionImagenes['quienes_somos'] ?? collect();
            if ($imgQS->isEmpty() && !empty($configuracionSitio->quienes_somos_imagen))
                $imgQS = collect([(object)['imagen_url' => $configuracionSitio->quienes_somos_imagen]]);

            $imgMision = $seccionImagenes['mision'] ?? collect();
            if ($imgMision->isEmpty() && !empty($configuracionSitio->mision_imagen))
                $imgMision = collect([(object)['imagen_url' => $configuracionSitio->mision_imagen]]);

            $imgVision = $seccionImagenes['vision'] ?? collect();
            if ($imgVision->isEmpty() && !empty($configuracionSitio->vision_imagen))
                $imgVision = collect([(object)['imagen_url' => $configuracionSitio->vision_imagen]]);

            $haySeccion = $configuracionSitio->quienes_somos_texto
                       || $configuracionSitio->mision_texto
                       || $configuracionSitio->vision_texto
                       || $imgQS->isNotEmpty()
                       || $imgMision->isNotEmpty()
                       || $imgVision->isNotEmpty();
        @endphp

        @if($haySeccion)
        <section class="bg-[#F8FCFF]">

            {{-- ── QUIÉNES SOMOS: texto izquierda · imagen derecha ── --}}
            @if($configuracionSitio->quienes_somos_texto || $imgQS->isNotEmpty())
            <div class="py-24 border-b border-[#D9EAF2]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                        {{-- Texto --}}
                        <div>
                            <span class="font-semibold uppercase tracking-[0.3em] text-[10px] block mb-4" style="color:#008FE8;">Sobre nosotros</span>
                            <h2 class="text-4xl md:text-5xl font-black text-[#1F2937] mb-6 leading-tight">
                                {{ $configuracionSitio->quienes_somos_titulo ?? 'Quiénes Somos' }}
                            </h2>
                            <p class="text-[#6B7280] text-lg leading-relaxed font-light">
                                {{ $configuracionSitio->quienes_somos_texto }}
                            </p>
                        </div>

                        {{-- Slider de imágenes --}}
                        @if($imgQS->isNotEmpty())
                        <div x-data="{ active: 0, total: {{ $imgQS->count() }}, init() { if(this.total > 1) setInterval(() => { this.active = (this.active + 1) % this.total }, 5000) } }"
                             class="relative rounded-[2rem] overflow-hidden shadow-2xl">
                            @foreach($imgQS as $i => $img)
                            <img x-show="active === {{ $i }}"
                                 x-transition:enter="transition ease-out duration-700"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 src="{{ asset('storage/'.$img->imagen_url) }}"
                                 class="w-full h-auto block"
                                 alt="">
                            @endforeach
                            @if($imgQS->count() > 1)
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                                @foreach($imgQS as $i => $img)
                                <button @click="active = {{ $i }}"
                                        class="h-1.5 rounded-full transition-all duration-300"
                                        :class="active === {{ $i }} ? 'w-6 bg-[#008FE8]' : 'w-1.5 bg-warm-200'"></button>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            @endif

            {{-- ── MISIÓN: imagen izquierda · texto derecha ── --}}
            @if($configuracionSitio->mision_texto || $imgMision->isNotEmpty())
            <div class="py-24 border-b border-[#D9EAF2]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                        {{-- Slider de imágenes (izquierda en desktop) --}}
                        @if($imgMision->isNotEmpty())
                        <div x-data="{ active: 0, total: {{ $imgMision->count() }}, init() { if(this.total > 1) setInterval(() => { this.active = (this.active + 1) % this.total }, 5500) } }"
                             class="relative rounded-[2rem] overflow-hidden shadow-2xl order-first lg:order-first">
                            @foreach($imgMision as $i => $img)
                            <img x-show="active === {{ $i }}"
                                 x-transition:enter="transition ease-out duration-700"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 src="{{ asset('storage/'.$img->imagen_url) }}"
                                 class="w-full h-auto block"
                                 alt="">
                            @endforeach
                            @if($imgMision->count() > 1)
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                                @foreach($imgMision as $i => $img)
                                <button @click="active = {{ $i }}"
                                        class="h-1.5 rounded-full transition-all duration-300"
                                        :class="active === {{ $i }} ? 'w-6 bg-[#008FE8]' : 'w-1.5 bg-warm-200'"></button>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- Texto (derecha en desktop) --}}
                        <div class="{{ $imgMision->isEmpty() ? 'lg:col-span-2 max-w-3xl' : '' }}">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6" style="background:#EBF5FC;">
                                <svg class="w-7 h-7" style="color:#0E4A9E;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span class="font-semibold uppercase tracking-[0.3em] text-[10px] block mb-3" style="color:#008FE8;">Nuestra misión</span>
                            <h2 class="text-4xl md:text-5xl font-black text-[#1F2937] mb-6 leading-tight">Misión</h2>
                            <p class="text-[#6B7280] text-lg leading-relaxed font-light">{{ $configuracionSitio->mision_texto }}</p>
                        </div>

                    </div>
                </div>
            </div>
            @endif

            {{-- ── VISIÓN: texto izquierda · imagen derecha ── --}}
            @if($configuracionSitio->vision_texto || $imgVision->isNotEmpty())
            <div class="py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                        {{-- Texto (izquierda en desktop) --}}
                        <div class="{{ $imgVision->isEmpty() ? 'lg:col-span-2 max-w-3xl' : '' }}">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6" style="background:#EBF5FC;">
                                <svg class="w-7 h-7" style="color:#008FE8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <span class="font-semibold uppercase tracking-[0.3em] text-[10px] block mb-3" style="color:#008FE8;">Nuestra visión</span>
                            <h2 class="text-4xl md:text-5xl font-black text-[#1F2937] mb-6 leading-tight">Visión</h2>
                            <p class="text-[#6B7280] text-lg leading-relaxed font-light">{{ $configuracionSitio->vision_texto }}</p>
                        </div>

                        {{-- Slider de imágenes (derecha en desktop) --}}
                        @if($imgVision->isNotEmpty())
                        <div x-data="{ active: 0, total: {{ $imgVision->count() }}, init() { if(this.total > 1) setInterval(() => { this.active = (this.active + 1) % this.total }, 6000) } }"
                             class="relative rounded-[2rem] overflow-hidden shadow-2xl">
                            @foreach($imgVision as $i => $img)
                            <img x-show="active === {{ $i }}"
                                 x-transition:enter="transition ease-out duration-700"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 src="{{ asset('storage/'.$img->imagen_url) }}"
                                 class="w-full h-auto block"
                                 alt="">
                            @endforeach
                            @if($imgVision->count() > 1)
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                                @foreach($imgVision as $i => $img)
                                <button @click="active = {{ $i }}"
                                        class="h-1.5 rounded-full transition-all duration-300"
                                        :class="active === {{ $i }} ? 'w-6 bg-[#008FE8]' : 'w-1.5 bg-warm-200'"></button>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            @endif

        </section>
        @endif

        <!-- ── CTA BAND ────────────────────────────────────────── -->
        @guest
        <section class="py-20" style="background: linear-gradient(135deg, #0A2F6B 0%, #0E4A9E 45%, #008FE8 100%);">
            <div class="max-w-3xl mx-auto px-4 text-center">
                <h2 class="text-4xl font-black text-white mb-4">¿Lista para tu próxima visita?</h2>
                <p class="text-[#7FD8FF] text-lg font-light mb-8">Reserva tu cita en minutos y disfruta de la experiencia Arrecife.</p>
                <a href="{{ route('register') }}"
                   class="inline-block bg-white text-[#0A2F6B] font-bold px-10 py-4 rounded-2xl hover:bg-[#EBF5FC] transition shadow-xl active:scale-95">
                    Crear cuenta gratis
                </a>
            </div>
        </section>
        @endguest

        <!-- ── FOOTER ──────────────────────────────────────────── -->
        <footer class="py-16 border-t border-[#D9EAF2]" style="background:#0A2F6B;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        @if(!empty($configuracionSitio->logo))
                            <img src="{{ asset('storage/'.$configuracionSitio->logo) }}"
                                 class="h-10 object-contain mb-3 mx-auto md:mx-0 brightness-0 invert"
                                 alt="{{ $configuracionSitio->nombre_negocio }}">
                        @else
                            <span class="text-2xl font-black text-white tracking-tight block mb-2"
                                  style="font-family:'Playfair Display',serif;">
                                {{ $configuracionSitio->nombre_negocio ?? 'Arrecife' }}
                            </span>
                        @endif
                        <p class="text-[#7FD8FF]/60 text-xs font-normal">
                            © {{ date('Y') }} {{ $configuracionSitio->nombre_negocio ?? 'Arrecife' }}. Todos los derechos reservados.
                        </p>
                    </div>
                    <div class="flex flex-col items-center md:items-end gap-1.5 text-[#7FD8FF]/70 text-xs font-normal">
                        @if($configuracionSitio->telefono)
                            <span>{{ $configuracionSitio->telefono }}</span>
                        @endif
                        @if($configuracionSitio->email_contacto)
                            <span>{{ $configuracionSitio->email_contacto }}</span>
                        @endif
                        @if($configuracionSitio->direccion)
                            <span>{{ $configuracionSitio->direccion }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
