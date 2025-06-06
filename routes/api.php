<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\AuthController;

// 🟢 Rutas públicas
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']); // Agregada ruta de registro
Route::post('/contacto', [ContactoController::class, 'store']); // Enviar mensaje

// 🔒 Rutas protegidas (requieren token JWT)
Route::middleware(['auth:api'])->group(function () {

    // 🔐 Autenticación
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);

    // 📦 Menú
    Route::get('/menu', [MenuController::class, 'index']); // Ver menú
    Route::post('/menu', [MenuController::class, 'store']);
    Route::put('/menu/{id}', [MenuController::class, 'update']);
    Route::delete('/menu/{id}', [MenuController::class, 'destroy']);

    // 📅 Reservas
    Route::get('/reservas', [ReservaController::class, 'index']);
    Route::post('/reservas', [ReservaController::class, 'store']); // Crear reserva
    Route::put('/reservas/{id}', [ReservaController::class, 'update']);
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy']);
});