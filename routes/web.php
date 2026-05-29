<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ResenaController;

use Illuminate\Support\Facades\Route;

/*
    RUTAS WEB - SALÓN DE BELLEZA
    Este archivo define todas las rutas de la aplicación y sus permisos.
*/

// PÁGINA DE INICIO: Carga banners y servicios para el público.
Route::get('/', function () {
    $servicios = \App\Models\Servicio::activos()->with('imagenes')->get();
    $banners = \App\Models\Banner::where('activo', true)->latest()->get();
    return view('welcome', compact('servicios', 'banners'));
});

// DASHBOARD: Resumen informativo después de iniciar sesión.
Route::get('/dashboard', function () {
    $serviciosCount = \App\Models\Servicio::count();
    $usuariosCount = \App\Models\User::count();
    $ultimosServicios = \App\Models\Servicio::orderBy('id', 'desc')->take(3)->get();
    $citasHoy = \App\Models\Cita::delDia()->activas()->count();
    $proximasCitas = \App\Models\Cita::with('servicios')
        ->where('user_id', auth()->id())
        ->activas()
        ->whereDate('fecha', '>=', now())
        ->orderBy('fecha')
        ->orderBy('hora')
        ->take(3)
        ->get();
    return view('dashboard', compact('serviciosCount', 'usuariosCount', 'ultimosServicios', 'citasHoy', 'proximasCitas'));
})->middleware(['auth', 'verified'])->name('dashboard');

// GESTIÓN DE PERFIL: Rutas estándar para el usuario logueado.
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// RUTAS ADMINISTRATIVAS: Solo accesibles por el rol 'admin'.
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Vista de Estadísticas avanzadas.
    Route::get('admin', function () {
        $totalUsuarios = \App\Models\User::count();
        $totalServicios = \App\Models\Servicio::count();
        $totalAdmins = \App\Models\User::where('role', 'admin')->count();
        $totalEditores = \App\Models\User::where('role', 'editor')->count();
        $totalUsuariosReg = \App\Models\User::where('role', 'usuario')->count();
        $citasHoy = \App\Models\Cita::delDia()->activas()->count();
        $ingresosMes = \App\Models\Cita::whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->whereNot('estado', 'cancelada')
            ->sum('total');

        $distribucion = [
            'Administradores' => $totalAdmins,
            'Editores' => $totalEditores,
            'Usuarios Regulares' => $totalUsuariosReg
        ];

        return view('admin.index', compact(
            'totalUsuarios', 'totalServicios', 'totalAdmins', 'totalEditores',
            'totalUsuariosReg', 'distribucion', 'citasHoy', 'ingresosMes'
        ));
    })->name('admin.index');

    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/exportar/csv', [ReporteController::class, 'exportarCsv'])->name('reportes.csv');
    Route::get('reportes/exportar/pdf', [ReporteController::class, 'exportarPdf'])->name('reportes.pdf');

    Route::get('resenas/admin', [ResenaController::class, 'adminIndex'])->name('resenas.admin');

    Route::get('citas/horarios-salon', [CitaController::class, 'horariosSalon'])->name('citas.horarios.salon');
    Route::patch('usuario/{id}/activo', [UsuariosController::class, 'toggleActivo'])->name('usuario.toggleActivo');

    // Gestión de Usuarios (Admin solamente).
    Route::get('usuario', [UsuariosController::class, 'index'])->name('usuario.index');
    Route::get('usuario/create', [UsuariosController::class, 'create'])->name('usuario.create');
    Route::post('usuario', [UsuariosController::class, 'store'])->name('usuario.store');
    Route::get('usuario/{id}', [UsuariosController::class, 'show'])->name('usuario.show');
    Route::get('usuario/{id}/edit', [UsuariosController::class, 'edit'])->name('usuario.edit');
    Route::put('usuario/{id}', [UsuariosController::class, 'update'])->name('usuario.update');
    Route::delete('usuario/{id}', [UsuariosController::class, 'destroy'])->name('usuario.destroy');

    // Gestión de Catálogo de Servicios (Admin solamente).
    Route::resource('servicios', ServicioController::class)->except(['show', 'index']);
    Route::delete('servicios/imagen/{id}', [ServicioController::class, 'eliminarImagen'])->name('servicios.eliminarImagen');

    // Gestión de Banners (Admin solamente).
    Route::resource('banners', BannerController::class)->except(['show']);
});

// RUTAS DE CONSULTA (Todos los usuarios autenticados).
Route::middleware(['auth'])->group(function () {
    Route::get('servicios', [ServicioController::class, 'index'])->name('servicios.index');

    Route::middleware('role:admin,editor')->group(function () {
        Route::get('citas/agenda', [CitaController::class, 'agenda'])->name('citas.agenda');
    });

    Route::get('citas/calendario', [CitaController::class, 'calendario'])->name('citas.calendario');
    Route::get('citas/eventos-calendario', [CitaController::class, 'eventosCalendario'])->name('citas.eventos');
    Route::get('citas/horarios-disponibles', [CitaController::class, 'horariosDisponibles'])->name('citas.horarios.json');
    Route::get('historial', [ResenaController::class, 'historial'])->name('resenas.historial');
    Route::get('citas/{cita}/resena', [ResenaController::class, 'create'])->name('resenas.create');
    Route::post('citas/{cita}/resena', [ResenaController::class, 'store'])->name('resenas.store');
    Route::resource('citas', CitaController::class);
});

require __DIR__.'/auth.php';
