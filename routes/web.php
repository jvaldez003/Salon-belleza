<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $servicios = \App\Models\Servicio::all();
    $banners = \App\Models\Banner::where('activo', true)->latest()->get();
    return view('welcome', compact('servicios', 'banners'));
});

Route::get('/dashboard', function () {
    $serviciosCount = \App\Models\Servicio::count();
    $usuariosCount = \App\Models\User::count();
    $ultimosServicios = \App\Models\Servicio::orderBy('id', 'desc')->take(3)->get();
    return view('dashboard', compact('serviciosCount', 'usuariosCount', 'ultimosServicios'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Exclusive Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Estadísticas del Sistema
    Route::get('admin', function () {
        $totalUsuarios = \App\Models\User::count();
        $totalServicios = \App\Models\Servicio::count();
        $totalAdmins = \App\Models\User::where('role', 'admin')->count();
        $totalEditores = \App\Models\User::where('role', 'editor')->count();
        $totalUsuariosReg = \App\Models\User::where('role', 'usuario')->count();
        
        // Distribution for table
        $distribucion = [
            'Administradores' => $totalAdmins,
            'Editores' => $totalEditores,
            'Usuarios Regulares' => $totalUsuariosReg
        ];

        return view('admin.index', compact('totalUsuarios', 'totalServicios', 'totalAdmins', 'totalEditores', 'totalUsuariosReg', 'distribucion'));
    })->name('admin.index');
    
    // Usuario Creation & Deletion
    Route::get('usuario/create', [UsuariosController::class, 'create'])->name('usuario.create');
    Route::post('usuario', [UsuariosController::class, 'store'])->name('usuario.store');
    Route::delete('usuario/{id}', [UsuariosController::class, 'destroy'])->name('usuario.destroy');

    // Servicios CRUD (Exclusive to Admin as per manual)
    Route::resource('servicios', ServicioController::class)->except(['show', 'index']);
    Route::delete('servicios/imagen/{id}', [ServicioController::class, 'eliminarImagen'])->name('servicios.eliminarImagen');

    // Banners CRUD (Exclusive to Admin)
    Route::resource('banners', BannerController::class)->except(['show']);
});

// Admin & Editor Shared Routes
Route::middleware(['auth', 'role:admin,editor'])->group(function () {
    Route::get('usuario/{id}/edit', [UsuariosController::class, 'edit'])->name('usuario.edit');
    Route::put('usuario/{id}', [UsuariosController::class, 'update'])->name('usuario.update');
});

// Public Auth Routes (All Roles)
Route::middleware(['auth'])->group(function () {
    Route::get('usuario', [UsuariosController::class, 'index'])->name('usuario.index');
    Route::get('usuario/{id}', [UsuariosController::class, 'show'])->name('usuario.show');
    Route::get('servicios', [ServicioController::class, 'index'])->name('servicios.index');
});

require __DIR__.'/auth.php';
