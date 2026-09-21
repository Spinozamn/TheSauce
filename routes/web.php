<?php

use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CarritoDetalleController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ContribucionController;
use App\Http\Controllers\CuentaSocialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\HitoFinancieroController;
use App\Http\Controllers\InnovacionController;
use App\Http\Controllers\ListaDeseoController;
use App\Http\Controllers\LogSistemaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/{current_team}/dashboard', DashboardController::class);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('admin/dashboard');
        })->name('dashboard');

        Route::get('/dashboard', function () {
            return Inertia::render('admin/dashboard');
        });

        Route::get('/innovaciones', [InnovacionController::class, 'index'])->name('innovaciones.index');
        Route::get('/innovaciones/crear', [InnovacionController::class, 'create'])->name('innovaciones.create');
        Route::get('/innovaciones/create', [InnovacionController::class, 'create']);
        Route::get('/innovaciones/nuevo', [InnovacionController::class, 'create']);
        Route::post('/innovaciones', [InnovacionController::class, 'store'])->name('innovaciones.store');
        Route::get('/innovaciones/papelera', [InnovacionController::class, 'trash'])->name('innovaciones.trash');
        Route::get('/innovaciones/{id}', [InnovacionController::class, 'show'])->name('innovaciones.show');
        Route::get('/innovaciones/{id}/edit', [InnovacionController::class, 'edit'])->name('innovaciones.edit');
        Route::post('/innovaciones/{id}', [InnovacionController::class, 'update'])->name('innovaciones.update');
        Route::put('/innovaciones/{id}', [InnovacionController::class, 'update']);
        Route::delete('/innovaciones/{id}', [InnovacionController::class, 'destroy'])->name('innovaciones.destroy');
        Route::patch('/innovaciones/{id}/restaurar', [InnovacionController::class, 'restore'])->name('innovaciones.restore');
        Route::delete('/innovaciones/{id}/definitivo', [InnovacionController::class, 'forceDelete'])->name('innovaciones.forceDelete');

        Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
        Route::get('/hitos-financieros', [HitoFinancieroController::class, 'index'])->name('hitos-financieros.index');
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
        Route::get('/etiquetas', [EtiquetaController::class, 'index'])->name('etiquetas.index');
        Route::get('/contribuciones', [ContribucionController::class, 'index'])->name('contribuciones.index');
        Route::get('/comentarios', [ComentarioController::class, 'index'])->name('comentarios.index');
        Route::get('/carritos', [CarritoController::class, 'index'])->name('carritos.index');
        Route::get('/carrito-detalles', [CarritoDetalleController::class, 'index'])->name('carrito-detalles.index');
        Route::get('/lista-deseos', [ListaDeseoController::class, 'index'])->name('lista-deseos.index');
        Route::get('/cuentas-sociales', [CuentaSocialController::class, 'index'])->name('cuentas-sociales.index');
        Route::get('/logs-sistema', [LogSistemaController::class, 'index'])->name('logs-sistema.index');
        Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    });
});

require __DIR__.'/settings.php';