<?php

use App\Http\Controllers\Api\Admin\AdminAuthController;
use App\Http\Controllers\Api\Admin\CategoriaAdminController;
use App\Http\Controllers\Api\Admin\ContribucionAdminController;
use App\Http\Controllers\Api\Admin\InnovacionAdminController;
use App\Http\Controllers\Api\Admin\UsuarioAdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\Cliente\ClientAuthController;
use App\Http\Controllers\Api\Cliente\ClienteAuthController;
use App\Http\Controllers\Api\Cliente\ClientRegistroController;
use App\Http\Controllers\Api\Cliente\ContribucionClienteController;
use App\Http\Controllers\Api\Cliente\InnovacionClienteController;
use App\Http\Controllers\Api\InnovacionApiController;
use App\Http\Controllers\Api\InnovacionController;
use App\Http\Controllers\Api\Publico\CatalogoApiController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::prefix('catalogo')->group(function () {
        Route::get('/innovaciones', [CatalogoApiController::class, 'innovaciones']);
        Route::get('/innovaciones/{id}', [CatalogoApiController::class, 'detalleInnovacion']);
        Route::get('/categorias', [CatalogoApiController::class, 'categorias']);
    });

    Route::prefix('client')->group(function () {
        Route::post('/register', [ClientAuthController::class, 'register']);
        Route::post('/login', [ClientAuthController::class, 'login']);

        Route::get('/catalogo', [ClientRegistroController::class, 'catalogo']);
        Route::get('/catalogo/{id}', [ClientRegistroController::class, 'catalogoDetalle']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [ClientAuthController::class, 'logout']);
            Route::get('/perfil', [ClientAuthController::class, 'perfil']);
            Route::match(['put', 'patch'], '/perfil', [ClientAuthController::class, 'actualizarPerfil']);

            Route::get('/mis-registros', [ClientRegistroController::class, 'index']);
            Route::post('/mis-registros', [ClientRegistroController::class, 'store']);
            Route::get('/mis-registros/{id}', [ClientRegistroController::class, 'show']);
            Route::match(['put', 'patch'], '/mis-registros/{id}', [ClientRegistroController::class, 'update']);
            Route::delete('/mis-registros/{id}', [ClientRegistroController::class, 'destroy']);

            Route::get('/innovaciones', [ClientRegistroController::class, 'index']);
            Route::post('/innovaciones', [ClientRegistroController::class, 'store']);
            Route::get('/innovaciones/{id}', [ClientRegistroController::class, 'show']);
            Route::match(['put', 'patch'], '/innovaciones/{id}', [ClientRegistroController::class, 'update']);
            Route::delete('/innovaciones/{id}', [ClientRegistroController::class, 'destroy']);
        });
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

        Route::middleware(['auth:sanctum', EnsureAdmin::class])->group(function () {
            Route::post('/logout', [AdminAuthController::class, 'logout']);
            Route::get('/perfil', [AdminAuthController::class, 'perfil']);

            Route::get('/recursos', [InnovacionAdminController::class, 'index']);
            Route::get('/recursos/{id}', [InnovacionAdminController::class, 'show']);
            Route::post('/recursos', [InnovacionAdminController::class, 'store']);
            Route::match(['put', 'patch'], '/recursos/{id}', [InnovacionAdminController::class, 'update']);
            Route::delete('/recursos/{id}', [InnovacionAdminController::class, 'destroy']);
            Route::post('/recursos/{id}/restaurar', [InnovacionAdminController::class, 'restore']);

            Route::get('/innovaciones', [InnovacionAdminController::class, 'index']);
            Route::get('/innovaciones/{id}', [InnovacionAdminController::class, 'show']);
            Route::post('/innovaciones', [InnovacionAdminController::class, 'store']);
            Route::match(['put', 'patch'], '/innovaciones/{id}', [InnovacionAdminController::class, 'update']);
            Route::delete('/innovaciones/{id}', [InnovacionAdminController::class, 'destroy']);
            Route::post('/innovaciones/{id}/restaurar', [InnovacionAdminController::class, 'restore']);

            Route::get('/usuarios', [UsuarioAdminController::class, 'index']);
            Route::get('/usuarios/{id}', [UsuarioAdminController::class, 'show']);
            Route::patch('/usuarios/{id}/estado', [UsuarioAdminController::class, 'cambiarEstado']);

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
