<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::apiResource('menu-items', MenuItemController::class);
    Route::apiResource('orders', OrderController::class);

    Route::get('/reports/orders', [ReportController::class, 'orders'])->name('api.reports.orders');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('api.reports.export');
});