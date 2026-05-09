<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RestauranteController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Restaurantes
    Route::apiResource('restaurantes', RestauranteController::class);

    // Productos (nested under restaurante)
    Route::apiResource('restaurantes.productos', ProductoController::class)->scoped(['restaurante' => 'id', 'producto' => 'id']);

    // Orders (nested under restaurante)
    Route::get('restaurantes/{restaurante}/orders',         [OrderController::class, 'index']);
    Route::get('restaurantes/{restaurante}/orders/today',   [OrderController::class, 'today']);
    Route::post('restaurantes/{restaurante}/orders',        [OrderController::class, 'store']);
    Route::patch('restaurantes/{restaurante}/orders/{order}/status', [OrderController::class, 'updateStatus']);
});
