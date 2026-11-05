{{--
    Menú lateral del panel. Cada persona ve solo lo que su rol puede abrir:
      clienta  → su inicio, reservar, sus citas e historial
      editor   → agenda, calendario y citas
      admin    → todo lo anterior + catálogo, sitio web, equipo y resultados
    Para agregar una opción: añade una fila al grupo que corresponda (texto, ruta, rutas activas, ícono).
--}}
@php
    $usuario  = Auth::user();
    $esAdmin  = $usuario->isAdmin();
    $esEquipo = $esAdmin || $usuario->isEditor();

    // Íconos (trazos de 24×24)
    $i = [
        'inicio'     => 'M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1h-5v-6h-6v6H4a1 1 0 01-1-1v-9.5z',
        'reservar'   => 'M12 8v8m-4-4h8M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z',
        'citas'      => 'M8 3v3m8-3v3M4 9h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z',
        'agenda'     => 'M4 6h16M4 12h10M4 18h7',
        'historial'  => 'M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z',
        'servicios'  => 'M4 6h16M4 10h16M4 14h10M4 18h7',
        'categorias' => 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z',
        'protocolos' => 'M9 12h6m-6 4h6M7 3h7l5 5v12a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z',
        'banners'    => 'M4 5h16v14H4zM4 15l5-5 4 4 3-3 4 4',
        'sitio'      => 'M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8M12 3a14 14 0 010 18M12 3a14 14 0 000 18',
        'empleados'  => 'M16 20v-1a4 4 0 00-4-4H7a4 4 0 00-4 4v1M9.5 11a3.5 3.5 0 100-7 3.5 3.5 0 000 7zM21 20v-1a4 4 0 00-3-3.87M16 4.13a3.5 3.5 0 010 6.75',
        'horarios'   => 'M12 7v5l3 3M12 21a9 9 0 100-18 9 9 0 000 18z',
        'usuarios'   => 'M12 12a4 4 0 100-8 4 4 0 000 8zM4 21a8 8 0 0116 0',
        'estadisticas' => 'M4 20V10m6 10V4m6 16v-7m4 7H2',
        'reportes'   => 'M7 3h7l5 5v12a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1zM9 17v-3m3 3v-5m3 5v-2',
        'resenas'    => 'M12 3l2.6 5.6 6 .7-4.5 4.1 1.2 6-5.3-3-5.3 3 1.2-6L3.4 9.3l6-.7L12 3z',
        'ver-sitio'  => 'M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1h5',
        'perfil'     => 'M12 12a4 4 0 100-8 4 4 0 000 8zM4 21a8 8 0 0116 0',
        'salir'      => 'M15 4h4a1 1 0 011 1v14a1 1 0 01-1 1h-4M10 17l-5-5 5-5M5 12h11',
    ];

    // [texto, url, patrones de ruta activa, ícono]
    $grupos = [];
    $grupos[''] = [['Inicio', route('dashboard'), ['dashboard'], 'inicio']];

    if ($esEquipo) {
        $grupos['Día a día'] = [
            ['Agenda', route('citas.agenda'), ['citas.agenda'], 'agenda'],
            ['Calendario', route('citas.calendario'), ['citas.calendario'], 'citas'],
            ['Citas', route('citas.index'), ['citas.index', 'citas.show', 'citas.edit'], 'historial'],
            ['Nueva cita', route('citas.create'), ['citas.create'], 'reservar'],
        ];
    } else {
        $grupos[''][] = ['Reservar cita', route('citas.create'), ['citas.create'], 'reservar'];
        $grupos[''][] = ['Mis citas', route('citas.index'), ['citas.index', 'citas.show', 'citas.edit'], 'citas'];
        $grupos[''][] = ['Historial y reseñas', route('resenas.historial'), ['resenas.historial', 'resenas.create'], 'historial'];
    }

    if ($esAdmin) {
        $grupos['Catálogo y sitio web'] = [
            ['Servicios', route('servicios.index'), ['servicios.*'], 'servicios'],
            ['Categorías', route('categorias.index'), ['categorias.*'], 'categorias'],
            ['Protocolos', route('protocolos.index'), ['protocolos.*'], 'protocolos'],
            ['Banners', route('banners.index'), ['banners.*'], 'banners'],
            ['Configuración del sitio', route('configuracion.edit'), ['configuracion.*'], 'sitio'],
        ];
        $grupos['Equipo y clientes'] = [
            ['Empleados', route('empleados.index'), ['empleados.*'], 'empleados'],
            ['Horarios del salón', route('horarios.index'), ['horarios.*'], 'horarios'],
            ['Usuarios', route('usuario.index'), ['usuario.*'], 'usuarios'],
        ];
        $grupos['Resultados'] = [
            ['Estadísticas', route('admin.index'), ['admin.index'], 'estadisticas'],
            ['Reportes', route('reportes.index'), ['reportes.*'], 'reportes'],
            ['Reseñas', route('resenas.admin'), ['resenas.admin'], 'resenas'],
        ];
    }
@endphp

<div class="flex items-center justify-between gap-3 px-6 h-16 lg:h-20 shrink-0">
    <a href="{{ route('dashboard') }}" aria-label="Inicio del panel"><x-marca tono="claro" :subtitulo="false" /></a>
    <button type="button" @click="menu = false" class="lg:hidden w-10 h-10 -mr-2 grid place-items-center rounded-full hover:bg-white/10 text-white" aria-label="Cerrar menú">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

<nav class="flex-1 overflow-y-auto px-3 pb-6" aria-label="Panel">
    @foreach ($grupos as $titulo => $items)
        <div @class(['mt-6' => $titulo !== ''])>
            @if ($titulo !== '')
                <p class="px-3 mb-2 font-display italic text-[0.95rem] text-agua/80">{{ $titulo }}</p>
            @endif
            <ul class="grid gap-0.5">
                @foreach ($items as [$texto, $url, $patrones, $icono])
                    @php $activo = request()->routeIs(...$patrones); @endphp
                    <li>
                        <a href="{{ $url }}" @if ($activo) aria-current="page" @endif
                           @class([
                               'flex items-center gap-3 px-3 py-2.5 rounded-xl text-[0.9375rem] transition-colors',
                               'bg-white/10 text-white font-semibold' => $activo,
                               'hover:bg-white/5 hover:text-white' => ! $activo,
                           ])>
                            <svg @class(['w-5 h-5 shrink-0', 'text-agua' => $activo]) fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $i[$icono] }}"/></svg>
                            {{ $texto }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</nav>

<div class="shrink-0 border-t border-white/10 px-3 py-4 grid gap-0.5">
    <a href="{{ url('/') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[0.9375rem] hover:bg-white/5 hover:text-white transition-colors">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $i['ver-sitio'] }}"/></svg>
        Ver el sitio web
    </a>
    <a href="{{ route('profile.edit') }}" @if (request()->routeIs('profile.*')) aria-current="page" @endif
       @class(['flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors', 'bg-white/10 text-white' => request()->routeIs('profile.*'), 'hover:bg-white/5 hover:text-white' => ! request()->routeIs('profile.*')])>
        <span class="w-8 h-8 -ml-1 rounded-full bg-agua text-abismo grid place-items-center font-display text-sm shrink-0" aria-hidden="true">{{ mb_strtoupper(mb_substr($usuario->name, 0, 1)) }}</span>
        <span class="min-w-0">
            <span class="block text-[0.9375rem] text-white truncate">{{ $usuario->name }}</span>
            <span class="block text-xs truncate">Mi perfil</span>
        </span>
    </a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[0.9375rem] hover:bg-white/5 hover:text-white transition-colors">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $i['salir'] }}"/></svg>
            Cerrar sesión
        </button>
    </form>
</div>
