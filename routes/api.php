<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RestauranteController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('restaurante',        [RestauranteController::class, 'show']);
    Route::put('restaurante',        [RestauranteController::class, 'update']);

    Route::get('productos',          [ProductoController::class, 'index']);
    Route::post('productos',         [ProductoController::class, 'store']);
    Route::put('productos/{producto}',    [ProductoController::class, 'update']);
    Route::delete('productos/{producto}', [ProductoController::class, 'destroy']);

    Route::get('orders/daily',            [OrderController::class, 'daily']);
    Route::get('orders/weekly',           [OrderController::class, 'weekly']);
    Route::get('orders/monthly',          [OrderController::class, 'monthly']);
    Route::get('orders/yearly',           [OrderController::class, 'yearly']);
    Route::get('orders/filter',           [OrderController::class, 'filter']);
    Route::post('orders',            [OrderController::class, 'store']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);
});
