<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-800 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Section -->
            <div class="mb-10 bg-gradient-to-r from-indigo-600 to-violet-700 rounded-3xl p-10 text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <h1 class="text-4xl font-black mb-2">¡Hola, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-indigo-100 text-lg max-w-xl">Bienvenido de nuevo al sistema de gestión de tu Salón de Belleza. Aquí tienes un resumen de lo que está pasando hoy.</p>
                </div>
                <!-- Decorative Circle -->
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-400/20 rounded-full blur-2xl"></div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                <!-- Total Services -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                    </div>
                    <h3 class="text-slate-500 font-bold uppercase tracking-wider text-[10px] mb-1">Total Servicios</h3>
                    <div class="text-4xl font-black text-slate-900">{{ $serviciosCount }}</div>
                </div>

                <!-- Total Users -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-4 bg-violet-50 rounded-2xl text-violet-600 group-hover:bg-violet-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                    </div>
                    <h3 class="text-slate-500 font-bold uppercase tracking-wider text-[10px] mb-1">Usuarios Activos</h3>
                    <div class="text-4xl font-black text-slate-900">{{ $usuariosCount }}</div>
                </div>

                <!-- Banners Info -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-4 bg-pink-50 rounded-2xl text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1 1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
                        </div>
                    </div>
                    <h3 class="text-slate-500 font-bold uppercase tracking-wider text-[10px] mb-1">Banner Principal</h3>
                    <div class="text-xs font-black text-emerald-500 bg-emerald-50 inline-block px-3 py-1 rounded-full">CONFIGURADO</div>
                </div>

                <!-- Roles Stat -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-4 bg-slate-50 rounded-2xl text-slate-600 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                    </div>
                    <h3 class="text-slate-500 font-bold uppercase tracking-wider text-[10px] mb-1">Rol Actual</h3>
                    <div class="text-lg font-black text-slate-900 uppercase tracking-tighter">{{ Auth::user()->role }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Quick Actions -->
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-100">
                    <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center">
                        <span class="w-2 h-8 bg-indigo-600 rounded-full mr-4"></span>
                        Accesos Rápidos
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('servicios.index') }}" class="flex items-center p-5 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-indigo-50 hover:border-indigo-200 transition-all group">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-600 mr-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800 text-sm">Servicios</h4>
                                <p class="text-[10px] text-slate-500">Catálogo beauty</p>
                            </div>
                        </a>

                        <a href="{{ route('banners.index') }}" class="flex items-center p-5 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-pink-50 hover:border-pink-200 transition-all group">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-pink-600 mr-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z" /></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800 text-sm">Banners</h4>
                                <p class="text-[10px] text-slate-500">Página de inicio</p>
                            </div>
                        </a>

                        @if(Auth::user()->role == 'admin')
                        <a href="{{ route('usuario.index') }}" class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-emerald-50 hover:border-emerald-200 transition-all group">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-600 mr-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800">Control de Usuarios</h4>
                                <p class="text-xs text-slate-500">Gestiona los accesos del personal.</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        @endif

                        <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-violet-50 hover:border-violet-200 transition-all group">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-violet-600 mr-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800">Mi Perfil</h4>
                                <p class="text-xs text-slate-500">Configura tu cuenta y seguridad.</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>

                <!-- Recent Services -->
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-100">
                    <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center">
                        <span class="w-2 h-8 bg-violet-600 rounded-full mr-4"></span>
                        Últimos Servicios
                    </h2>
                    <div class="space-y-4">
                        @foreach($ultimosServicios as $servicio)
                        <div class="flex items-center p-4 rounded-2xl hover:bg-slate-50 transition-colors">
                            <div class="w-14 h-14 bg-slate-100 rounded-xl overflow-hidden mr-4">
                                @if($servicio->imagenes->count() > 0)
                                    <img src="{{ asset('storage/' . $servicio->imagenes->first()->url) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800">{{ $servicio->nombre }}</h4>
                                <p class="text-xs text-indigo-600 font-bold">${{ number_format($servicio->precio, 0) }}</p>
                            </div>
                            <a href="{{ route('servicios.edit', $servicio->id) }}" class="p-2 text-slate-400 hover:text-indigo-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </a>
                        </div>
                        @endforeach
                        
                        @if($ultimosServicios->isEmpty())
                        <p class="text-slate-400 text-sm text-center py-10">Aún no has creado servicios.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
