<nav x-data="{ open: false }" class="bg-white border-b border-warm-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" aria-label="Ir al Inicio">
                        @if(!empty($configuracionSitio->logo))
                            <img src="{{ asset('storage/'.$configuracionSitio->logo) }}" class="h-9 w-auto object-contain" alt="{{ $configuracionSitio->nombre_negocio }}">
                        @else
                            <span class="text-xl font-black text-rose-dark tracking-tighter uppercase">{{ $configuracionSitio->nombre_negocio ?? 'Arrecife' }}</span>
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-8 sm:flex sm:items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('servicios.index')" :active="request()->routeIs('servicios.*')">
                        {{ __('Servicios') }}
                    </x-nav-link>
                    <x-nav-link :href="route('citas.index')" :active="request()->routeIs('citas.*')">
                        {{ __('Citas') }}
                    </x-nav-link>
                    <x-nav-link :href="route('citas.calendario')" :active="request()->routeIs('citas.calendario')">
                        {{ __('Calendario') }}
                    </x-nav-link>
                    <x-nav-link :href="route('resenas.historial')" :active="request()->routeIs('resenas.historial')">
                        {{ __('Historial') }}
                    </x-nav-link>
                    @admin
                    {{-- Dropdown Admin --}}
                    <div x-data="{ adminOpen: false }" class="relative" @keydown.escape.window="adminOpen = false">
                        <button @click="adminOpen = !adminOpen"
                                :class="adminOpen ? 'text-rose-dark border-rose-dark' : 'text-warm-500 border-transparent'"
                                class="inline-flex items-center gap-1 px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 focus:outline-none hover:text-warm-700 hover:border-warm-300">
                            Admin
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="adminOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="adminOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                             @click.outside="adminOpen = false"
                             class="absolute left-0 mt-2 w-52 rounded-2xl bg-white shadow-xl border border-warm-100 py-2 z-50"
                             x-cloak>
                            <a href="{{ route('resenas.admin') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-warm-700 hover:bg-cream hover:text-rose-dark transition {{ request()->routeIs('resenas.admin') ? 'text-rose-dark bg-cream' : '' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                Reseñas
                            </a>
                            <a href="{{ route('empleados.index') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-warm-700 hover:bg-cream hover:text-rose-dark transition {{ request()->routeIs('empleados.*') ? 'text-rose-dark bg-cream' : '' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Empleados
                            </a>
                            <a href="{{ route('horarios.index') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-warm-700 hover:bg-cream hover:text-rose-dark transition {{ request()->routeIs('horarios.*') ? 'text-rose-dark bg-cream' : '' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Horarios
                            </a>
                            <a href="{{ route('usuario.index') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-warm-700 hover:bg-cream hover:text-rose-dark transition {{ request()->routeIs('usuario.*') ? 'text-rose-dark bg-cream' : '' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Usuarios
                            </a>
                            <a href="{{ route('reportes.index') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-warm-700 hover:bg-cream hover:text-rose-dark transition {{ request()->routeIs('reportes.*') ? 'text-rose-dark bg-cream' : '' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Reportes
                            </a>
                            <div class="my-1 border-t border-warm-100"></div>
                            <a href="{{ route('protocolos.index') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-warm-700 hover:bg-cream hover:text-rose-dark transition {{ request()->routeIs('protocolos.*') ? 'text-rose-dark bg-cream' : '' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Protocolos
                            </a>
                            <a href="{{ route('categorias.index') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-warm-700 hover:bg-cream hover:text-rose-dark transition {{ request()->routeIs('categorias.*') ? 'text-rose-dark bg-cream' : '' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                Categorías
                            </a>
                            <a href="{{ route('configuracion.edit') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-warm-700 hover:bg-cream hover:text-rose-dark transition {{ request()->routeIs('configuracion.*') ? 'text-rose-dark bg-cream' : '' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Configuración del Sitio
                            </a>
                        </div>
                    </div>
                    @endadmin
                    <x-nav-link :href="url('/')" class="text-rose-dark font-bold">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        {{ __('Ver Sitio') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-warm-200 rounded-xl text-sm font-medium text-warm-600 bg-white hover:text-warm-800 hover:bg-cream focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-warm-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-warm-400 hover:text-warm-600 hover:bg-cream focus:outline-none focus:bg-cream focus:text-warm-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/')">
                {{ __('Ver Sitio') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-warm-200">
            <div class="px-4">
                <div class="font-bold text-base text-warm-900">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-warm-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
