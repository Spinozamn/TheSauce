<?php

use App\Http\Controllers\Api\Admin\AdminAuthController;
use App\Http\Controllers\Api\Admin\CategoriaAdminController;
use App\Http\Controllers\Api\Admin\ContribucionAdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\Cliente\ClienteAuthController;
use App\Http\Controllers\Api\Cliente\ContribucionClienteController;
use App\Http\Controllers\Api\Cliente\InnovacionClienteController;
use App\Http\Controllers\Api\InnovacionApiController;
use App\Http\Controllers\Api\InnovacionController;
use App\Http\Controllers\Api\Publico\CatalogoApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::prefix('catalogo')->group(function () {
        Route::get('/innovaciones', [CatalogoApiController::class, 'innovaciones']);
        Route::get('/innovaciones/{id}', [CatalogoApiController::class, 'detalleInnovacion']);
        Route::get('/categorias', [CatalogoApiController::class, 'categorias']);
    });

    Route::prefix('cliente')->group(function () {
        Route::post('/registro', [ClienteAuthController::class, 'registro']);
        Route::post('/login', [ClienteAuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [ClienteAuthController::class, 'logout']);
            Route::get('/perfil', [ClienteAuthController::class, 'perfil']);
            Route::patch('/perfil', [ClienteAuthController::class, 'actualizarPerfil']);

            Route::get('/contribuciones', [ContribucionClienteController::class, 'index']);
            Route::post('/contribuciones', [ContribucionClienteController::class, 'store']);
            Route::get('/contribuciones/{id}', [ContribucionClienteController::class, 'show']);

            Route::get('/innovaciones', [InnovacionClienteController::class, 'index']);
            Route::post('/innovaciones', [InnovacionClienteController::class, 'store']);
            Route::patch('/innovaciones/{id}', [InnovacionClienteController::class, 'update']);
        });
    });

    Route::prefix('admin')->group(function () {
        Route::post('/login', [AdminAuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AdminAuthController::class, 'logout']);
            Route::get('/perfil', [AdminAuthController::class, 'perfil']);

            Route::get('/innovaciones', [InnovacionApiController::class, 'index']);
            Route::get('/innovaciones/papelera', [InnovacionApiController::class, 'trash']);
            Route::get('/innovaciones/{id}', [InnovacionApiController::class, 'show']);
            Route::post('/innovaciones', [InnovacionApiController::class, 'store']);
            Route::match(['put', 'patch'], '/innovaciones/{id}', [InnovacionApiController::class, 'update']);
            Route::delete('/innovaciones/{id}', [InnovacionApiController::class, 'destroy']);
            Route::post('/innovaciones/{id}/restaurar', [InnovacionApiController::class, 'restore']);
            Route::delete('/innovaciones/{id}/forzar', [InnovacionApiController::class, 'forceDelete']);

            Route::get('/categorias', [CategoriaAdminController::class, 'index']);
            Route::post('/categorias', [CategoriaAdminController::class, 'store']);
            Route::patch('/categorias/{id}', [CategoriaAdminController::class, 'update']);
            Route::delete('/categorias/{id}', [CategoriaAdminController::class, 'destroy']);

            Route::get('/contribuciones', [ContribucionAdminController::class, 'index']);
            Route::get('/contribuciones/{id}', [ContribucionAdminController::class, 'show']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
    });

    Route::get('/innovaciones', [InnovacionController::class, 'index']);
    Route::post('/innovaciones', [InnovacionController::class, 'store']);
    Route::get('/innovaciones/{id}', [InnovacionController::class, 'show']);
    Route::match(['put', 'patch'], '/innovaciones/{id}', [InnovacionController::class, 'update']);
    Route::delete('/innovaciones/{id}', [InnovacionController::class, 'destroy']);
    Route::post('/innovaciones/{id}/imagen', [InnovacionController::class, 'subirImagen']);
    Route::delete('/innovaciones/{id}/imagen', [InnovacionController::class, 'eliminarImagen']);

    Route::get('/categorias', [CategoriaController::class, 'index']);
    Route::post('/categorias', [CategoriaController::class, 'store']);
    Route::get('/categorias/{id}', [CategoriaController::class, 'show']);
    Route::match(['put', 'patch'], '/categorias/{id}', [CategoriaController::class, 'update']);
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);
});
