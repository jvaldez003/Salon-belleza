<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-warm-900 leading-tight">Catálogo de Servicios</h1>
                <p class="text-warm-400 text-sm mt-1">
                    {{ $servicios->count() }} servicios en {{ $categorias->count() }} categorías
                </p>
            </div>
            @admin
            <div class="flex items-center gap-3">
                <a href="{{ route('categorias.index') }}"
                   class="flex items-center gap-2 py-3 px-5 rounded-2xl border border-warm-200 bg-white text-warm-600 hover:border-rose-primary hover:text-rose-primary font-bold text-sm transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    Categorías
                </a>
                <a href="{{ route('servicios.create') }}"
                   class="flex items-center gap-2 bg-rose-primary hover:bg-rose-dark text-white font-bold py-3 px-6 rounded-2xl transition shadow-sm group">
                    <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    Nuevo Servicio
                </a>
            </div>
            @endadmin
        </div>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-6 alert-success" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            @endif

            @if($servicios->isEmpty())
            <div class="bg-white p-20 text-center rounded-[3rem] shadow-sm border border-warm-200">
                <div class="w-24 h-24 bg-cream rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-warm-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
                <h3 class="text-2xl font-black text-warm-800 mb-2">Catálogo Vacío</h3>
                <p class="text-warm-500 mb-8">No hay servicios registrados aún.</p>
                @admin
                <a href="{{ route('servicios.create') }}" class="inline-block bg-rose-primary text-white font-bold py-3 px-8 rounded-2xl transition">
                    Crear primer servicio
                </a>
                @endadmin
            </div>
            @else

            @php
                $catConServs = $categorias->filter(fn($c) => $servicios->where('categoria_id', $c->id)->isNotEmpty());
                $sinCat      = $servicios->whereNull('categoria_id');
                $defaultTab  = $catConServs->first()?->id ?? ($sinCat->isNotEmpty() ? 'sin' : 'all');
            @endphp

            <div x-data="{ tab: '{{ $defaultTab }}' }">

                {{-- ── TABS ── --}}
                <div class="flex gap-1 overflow-x-auto hide-scrollbar mb-0 bg-white rounded-t-[2rem] px-6 pt-5 border border-b-0 border-warm-200">

                    @foreach($catConServs as $cat)
                    @php $cnt = $servicios->where('categoria_id', $cat->id)->count(); @endphp
                    <button type="button"
                            @click="tab = '{{ $cat->id }}'"
                            class="flex items-center gap-2 px-5 py-3 text-sm font-bold whitespace-nowrap shrink-0 rounded-t-xl transition-all duration-200 border-b-2 -mb-px"
                            :class="tab === '{{ $cat->id }}'
                                ? 'border-rose-primary text-rose-primary bg-rose-light/10'
                                : 'border-transparent text-warm-400 hover:text-warm-700 hover:bg-cream'">
                        {{ $cat->nombre }}
                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full transition-colors"
                              :class="tab === '{{ $cat->id }}' ? 'bg-rose-primary/15 text-rose-primary' : 'bg-warm-100 text-warm-400'">
                            {{ $cnt }}
                        </span>
                    </button>
                    @endforeach

                    @if($sinCat->isNotEmpty())
                    <button type="button"
                            @click="tab = 'sin'"
                            class="flex items-center gap-2 px-5 py-3 text-sm font-bold whitespace-nowrap shrink-0 rounded-t-xl transition-all duration-200 border-b-2 -mb-px"
                            :class="tab === 'sin'
                                ? 'border-rose-primary text-rose-primary bg-rose-light/10'
                                : 'border-transparent text-warm-400 hover:text-warm-700 hover:bg-cream'">
                        Sin categoría
                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full transition-colors"
                              :class="tab === 'sin' ? 'bg-rose-primary/15 text-rose-primary' : 'bg-warm-100 text-warm-400'">
                            {{ $sinCat->count() }}
                        </span>
                    </button>
                    @endif
                </div>

                {{-- ── CONTENIDO DE CADA TAB ── --}}
                <div class="bg-white rounded-b-[2rem] rounded-tr-[2rem] border border-warm-200 overflow-hidden">

                    @foreach($catConServs as $cat)
                    @php $catServs = $servicios->where('categoria_id', $cat->id); @endphp
                    <div x-show="tab === '{{ $cat->id }}'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        @include('servicios._tabla_servicios', ['items' => $catServs])
                    </div>
                    @endforeach

                    @if($sinCat->isNotEmpty())
                    <div x-show="tab === 'sin'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        @include('servicios._tabla_servicios', ['items' => $sinCat])
                    </div>
                    @endif

                </div>

            </div>
            @endif

        </div>
    </div>
</x-app-layout>
