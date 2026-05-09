<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RestauranteController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('restaurantes', RestauranteController::class);

    Route::get('productos/{restaurante}',  [ProductoController::class, 'index']);
    Route::post('productos',               [ProductoController::class, 'store']);
    Route::put('productos/{producto}',     [ProductoController::class, 'update']);
    Route::delete('productos/{producto}',  [ProductoController::class, 'destroy']);

    Route::get('orders/{restaurante}',              [OrderController::class, 'index']);
    Route::get('orders/{restaurante}/today',        [OrderController::class, 'today']);
    Route::post('orders',                           [OrderController::class, 'store']);
    Route::patch('orders/{order}/status',           [OrderController::class, 'updateStatus']);
});
