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
use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'welcome')->name('home');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::post('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
    Route::delete('invitations/{invitation}', [TeamInvitationController::class, 'decline'])->name('invitations.decline');
});

require __DIR__.'/settings.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', function () {
        return Inertia::render('admin/dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/roles', [RolController::class, 'index'])->name('admin.roles.index');
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/admin/cuentas-sociales', [CuentaSocialController::class, 'index'])->name('admin.cuentas-sociales.index');
    Route::get('/admin/categorias', [CategoriaController::class, 'index'])->name('admin.categorias.index');
    Route::get('/admin/etiquetas', [EtiquetaController::class, 'index'])->name('admin.etiquetas.index');
    Route::get('/admin/innovaciones', [InnovacionController::class, 'index'])->name('admin.innovaciones.index');
    Route::get('/admin/hitos-financieros', [HitoFinancieroController::class, 'index'])->name('admin.hitos-financieros.index');
    Route::get('/admin/lista-deseos', [ListaDeseoController::class, 'index'])->name('admin.lista-deseos.index');
    Route::get('/admin/carritos', [CarritoController::class, 'index'])->name('admin.carritos.index');
    Route::get('/admin/carrito-detalles', [CarritoDetalleController::class, 'index'])->name('admin.carrito-detalles.index');
    Route::get('/admin/contribuciones', [ContribucionController::class, 'index'])->name('admin.contribuciones.index');
    Route::get('/admin/comentarios', [ComentarioController::class, 'index'])->name('admin.comentarios.index');
    Route::get('/admin/notificaciones', [NotificacionController::class, 'index'])->name('admin.notificaciones.index');
    Route::get('/admin/logs-sistema', [LogSistemaController::class, 'index'])->name('admin.logs-sistema.index');

    Route::get('/admin/innovaciones/crear', function () {
        return Inertia::render('admin/innovaciones/create');
    })->name('admin.innovaciones.create');
});
