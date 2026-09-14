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

Route::inertia('panel', 'Panel/Index')->name('panel');

Route::resource('roles', RolController::class)->only(['index']);
Route::resource('usuarios', UsuarioController::class)->only(['index']);
Route::resource('cuentas-sociales', CuentaSocialController::class)->only(['index']);
Route::resource('categorias', CategoriaController::class)->only(['index']);
Route::resource('etiquetas', EtiquetaController::class)->only(['index']);
Route::resource('innovaciones', InnovacionController::class)->only(['index']);
Route::resource('hitos-financieros', HitoFinancieroController::class)->only(['index']);
Route::resource('lista-deseos', ListaDeseoController::class)->only(['index']);
Route::resource('carritos', CarritoController::class)->only(['index']);
Route::resource('carrito-detalles', CarritoDetalleController::class)->only(['index']);
Route::resource('contribuciones', ContribucionController::class)->only(['index']);
Route::resource('comentarios', ComentarioController::class)->only(['index']);
Route::resource('notificaciones', NotificacionController::class)->only(['index']);
Route::resource('logs-sistema', LogSistemaController::class)->only(['index']);

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

    Route::get('/admin/innovaciones', function () {
        return Inertia::render('admin/innovaciones/index');
    })->name('admin.innovaciones.index');

    Route::get('/admin/innovaciones/crear', function () {
        return Inertia::render('admin/innovaciones/create');
    })->name('admin.innovaciones.create');
});