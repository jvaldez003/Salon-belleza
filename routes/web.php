<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $servicios = \App\Models\Servicio::all();
    return view('welcome', compact('servicios'));
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

// Roles routes for Usuarios
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Solo administradores
    Route::get('admin', function () {
        $usersCount = \App\Models\User::count();
        $serviciosCount = \App\Models\Servicio::count();
        return view('admin.index', compact('usersCount', 'serviciosCount'));
    })->name('admin.index');
    
    Route::get('usuario/create', [UsuariosController::class, 'create'])->name('usuario.create');
    Route::post('usuario', [UsuariosController::class, 'store'])->name('usuario.store');
    Route::delete('usuario/{id}', [UsuariosController::class, 'destroy'])->name('usuario.destroy');
});

Route::middleware(['auth', 'role:admin,editor'])->group(function () {
    // Administradores y editores
    Route::get('usuario/{id}/edit', [UsuariosController::class, 'edit'])->name('usuario.edit');
    Route::put('usuario/{id}', [UsuariosController::class, 'update'])->name('usuario.update');
    
    // Servicios CRUD
    Route::get('servicios', [ServicioController::class, 'index'])->name('servicios.index');
    Route::get('servicios/create', [ServicioController::class, 'create'])->name('servicios.create');
    Route::post('servicios', [ServicioController::class, 'store'])->name('servicios.store');
    Route::get('servicios/{id}/edit', [ServicioController::class, 'edit'])->name('servicios.edit');
    Route::put('servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');
    Route::delete('servicios/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');
    Route::delete('servicios/imagen/{id}', [ServicioController::class, 'eliminarImagen'])->name('servicios.eliminarImagen');
});

Route::middleware(['auth'])->group(function () {
    // Todos los usuarios autenticados
    Route::get('usuario', [UsuariosController::class, 'index'])->name('usuario.index');
    Route::get('usuario/{id}', [UsuariosController::class, 'show'])->name('usuario.show');
});

require __DIR__.'/auth.php';
