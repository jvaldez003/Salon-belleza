<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-warm-900 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- BIENVENIDA -->
            <div class="mb-10 rounded-[3rem] p-12 text-white shadow-xl relative overflow-hidden group"
                 style="background: var(--gradient-main);">
                <div class="relative z-10">
                    <span class="inline-block px-4 py-1 rounded-full bg-white/20 text-white text-[10px] font-black uppercase tracking-widest mb-4 border border-white/30">
                        Acceso: {{ strtoupper(Auth::user()->role) }}
                    </span>
                    <h1 class="text-4xl sm:text-5xl font-black mb-4 tracking-tighter">¡Hola, {{ Auth::user()->name }}!</h1>
                    <p class="text-white/80 text-lg max-w-xl leading-relaxed">Bienvenido al panel de gestión de Arrecife Beauty Studio. Navega a las secciones según tu nivel de acceso.</p>
                </div>
                <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                <div class="absolute top-10 right-20 w-12 h-12 bg-white/10 rounded-2xl rotate-12"></div>
                <div class="absolute top-4 right-40 w-6 h-6 bg-white/10 rounded-full"></div>
            </div>

            <!-- GRILLA DE SECCIONES -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">

                <!-- USUARIOS / MI PERFIL -->
                @admin
                <a href="{{ route('usuario.index') }}" class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-warm-200 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-cream rounded-2xl flex items-center justify-center text-warm-400 group-hover:bg-warm-900 group-hover:text-white transition-all duration-500 mb-6">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <h3 class="text-2xl font-black text-warm-900 mb-2 tracking-tight">Gestión de Usuarios</h3>
                        <p class="text-warm-400 text-sm font-medium mb-6">Administra el equipo y visualiza los niveles de acceso.</p>
                        <span class="inline-flex items-center text-rose-dark font-black text-[10px] uppercase tracking-widest group-hover:translate-x-2 transition-transform">
                            Acceder Ahora
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </span>
                    </div>
                    <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-cream rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                </a>
                @else
                <a href="{{ route('profile.edit') }}" class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-warm-200 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-cream rounded-2xl flex items-center justify-center text-warm-400 group-hover:bg-warm-900 group-hover:text-white transition-all duration-500 mb-6">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <h3 class="text-2xl font-black text-warm-900 mb-2 tracking-tight">Mi Perfil</h3>
                        <p class="text-warm-400 text-sm font-medium mb-6">Actualiza tus datos personales y contraseña.</p>
                        <span class="inline-flex items-center text-rose-dark font-black text-[10px] uppercase tracking-widest group-hover:translate-x-2 transition-transform">
                            Editar Perfil
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </span>
                    </div>
                </a>
                @endadmin

                <!-- MIS CITAS -->
                <a href="{{ route('citas.index') }}" class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-warm-200 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-rose-light/30 rounded-2xl flex items-center justify-center text-rose-dark group-hover:bg-rose-primary group-hover:text-white transition-all duration-500 mb-6">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <h3 class="text-2xl font-black text-warm-900 mb-2 tracking-tight">Mis Citas</h3>
                        <p class="text-warm-400 text-sm font-medium mb-6">Reserva, consulta o cancela tus citas. Hoy hay {{ $citasHoy }} citas en el salón.</p>
                        <span class="inline-flex items-center text-rose-dark font-black text-[10px] uppercase tracking-widest group-hover:translate-x-2 transition-transform">
                            Gestionar Citas
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </span>
                    </div>
                </a>

                <!-- CATÁLOGO DE SERVICIOS -->
                <a href="{{ route('servicios.index') }}" class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-warm-200 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-gold-light/30 rounded-2xl flex items-center justify-center text-gold-dark group-hover:bg-gold-primary group-hover:text-white transition-all duration-500 mb-6">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <h3 class="text-2xl font-black text-warm-900 mb-2 tracking-tight">Catálogo Servicios</h3>
                        <p class="text-warm-400 text-sm font-medium mb-6">Visualiza los tratamientos disponibles y sus precios.</p>
                        <span class="inline-flex items-center text-gold-dark font-black text-[10px] uppercase tracking-widest group-hover:translate-x-2 transition-transform">
                            Ver Catálogo
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </span>
                    </div>
                    <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-gold-light/20 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                </a>

                <!-- ESTADÍSTICAS (Admin) -->
                @admin
                <a href="{{ route('admin.index') }}" class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-warm-200 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-warm-100 rounded-2xl flex items-center justify-center text-warm-500 group-hover:bg-warm-900 group-hover:text-white transition-all duration-500 mb-6">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        </div>
                        <h3 class="text-2xl font-black text-warm-900 mb-2 tracking-tight">Estadísticas</h3>
                        <p class="text-warm-400 text-sm font-medium mb-6">Resumen general y métricas de crecimiento del sistema.</p>
                        <span class="inline-flex items-center text-warm-600 font-black text-[10px] uppercase tracking-widest group-hover:translate-x-2 transition-transform">
                            Ver Reportes
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </span>
                    </div>
                    <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-warm-100 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                </a>
                @else
                <div class="bg-warm-50 p-10 rounded-[2.5rem] border border-dashed border-warm-200 flex items-center justify-center text-center">
                    <div>
                        <svg class="w-12 h-12 text-warm-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        <p class="text-[10px] font-black text-warm-400 uppercase tracking-widest">Estadísticas restringidas</p>
                    </div>
                </div>
                @endadmin

            </div>

            <!-- GESTIÓN DE BANNERS (Admin) -->
            @admin
            <div class="mt-12 bg-white p-8 rounded-[2rem] border border-warm-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center space-x-4 text-center md:text-left">
                    <div class="w-12 h-12 bg-rose-light/30 text-rose-dark rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-black text-warm-900 tracking-tight">Gestión de Publicidad</h4>
                        <p class="text-xs text-warm-400 font-medium">Configura los banners de la página principal.</p>
                    </div>
                </div>
                <a href="{{ route('banners.index') }}" class="bg-rose-primary text-white font-black px-8 py-3 rounded-2xl hover:bg-rose-dark transition shadow-sm text-xs uppercase tracking-widest">
                    Administrar Banners
                </a>
            </div>
            @endadmin

        </div>
    </div>
</x-app-layout>
