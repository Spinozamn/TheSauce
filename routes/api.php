<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\InnovacionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');

    Route::get('innovaciones', [InnovacionController::class, 'index'])->name('innovaciones.index');
    Route::get('innovaciones/{id}', [InnovacionController::class, 'show'])->name('innovaciones.show');

    Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('categorias/{id}', [CategoriaController::class, 'show'])->name('categorias.show');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me', [AuthController::class, 'me'])->name('auth.me');
        Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

        Route::post('innovaciones', [InnovacionController::class, 'store'])->name('innovaciones.store');
        Route::put('innovaciones/{id}', [InnovacionController::class, 'update'])->name('innovaciones.update');
        Route::patch('innovaciones/{id}', [InnovacionController::class, 'update'])->name('innovaciones.actualizar-parcialmente');
        Route::delete('innovaciones/{id}', [InnovacionController::class, 'destroy'])->name('innovaciones.destroy');
        Route::patch('innovaciones/{id}/restore', [InnovacionController::class, 'restore'])->name('innovaciones.restaurar');
        Route::delete('innovaciones/{id}/force', [InnovacionController::class, 'forceDelete'])->name('innovaciones.eliminar-permanentemente');
    });
});
