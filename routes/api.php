<?php

use App\Http\Controllers\Api\CitaApiController;
use App\Http\Controllers\Api\ServicioApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API REST - AppSalon
|--------------------------------------------------------------------------
| GET  /api/servicios
| GET  /api/citas/usuario/{id}  (auth:sanctum)
| POST /api/citas               (auth:sanctum)
| PUT  /api/citas/{id}          (auth:sanctum)
*/

Route::get('/servicios', [ServicioApiController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::get('/citas/usuario/{id}', [CitaApiController::class, 'porUsuario']);
    Route::get('/citas/horarios', [CitaApiController::class, 'horarios']);
    Route::post('/citas', [CitaApiController::class, 'store']);
    Route::put('/citas/{cita}', [CitaApiController::class, 'update']);
});
