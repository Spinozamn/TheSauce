<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ListadosTest extends TestCase
{
    use RefreshDatabase;

    private User $administrador;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->administrador = User::factory()->create();
    }

    public static function modulos(): array
    {
        return [
            'roles' => ['/admin/roles', 'admin/roles/index', 'roles', 12],
            'usuarios' => ['/admin/usuarios', 'admin/usuarios/index', 'usuarios', 20],
            'cuentas sociales' => ['/admin/cuentas-sociales', 'admin/cuentas-sociales/index', 'cuentasSociales', 15],
            'categorias' => ['/admin/categorias', 'admin/categorias/index', 'categorias', 12],
            'etiquetas' => ['/admin/etiquetas', 'admin/etiquetas/index', 'etiquetas', 12],
            'innovaciones' => ['/admin/innovaciones', 'admin/innovaciones/index', 'innovaciones', 25],
            'hitos financieros' => ['/admin/hitos-financieros', 'admin/hitos-financieros/index', 'hitosFinancieros', 30],
            'lista de deseos' => ['/admin/lista-deseos', 'admin/lista-deseos/index', 'listaDeseos', 20],
            'carritos' => ['/admin/carritos', 'admin/carritos/index', 'carritos', 12],
            'detalles de carrito' => ['/admin/carrito-detalles', 'admin/carrito-detalles/index', 'carritoDetalles', 24],
            'contribuciones' => ['/admin/contribuciones', 'admin/contribuciones/index', 'contribuciones', 30],
            'comentarios' => ['/admin/comentarios', 'admin/comentarios/index', 'comentarios', 25],
            'notificaciones' => ['/admin/notificaciones', 'admin/notificaciones/index', 'notificaciones', 20],
            'logs del sistema' => ['/admin/logs-sistema', 'admin/logs-sistema/index', 'logsSistema', 20],
        ];
    }

    #[DataProvider('modulos')]
    public function test_se_muestra_el_listado_con_paginacion(string $ruta, string $componente, string $propiedad, int $total): void
    {
        $this->actingAs($this->administrador)
            ->get($ruta)
            ->assertOk()
            ->assertInertia(fn (Assert $pagina) => $pagina
                ->component($componente)
                ->has($propiedad.'.data', 10)
                ->where($propiedad.'.total', $total)
                ->where($propiedad.'.per_page', 10)
                ->where($propiedad.'.current_page', 1)
                ->where($propiedad.'.last_page', (int) ceil($total / 10)));
    }

    public function test_los_invitados_son_redirigidos_al_inicio_de_sesion(): void
    {
        $this->get('/admin/innovaciones')->assertRedirect(route('login'));
    }

    public function test_el_listado_de_innovaciones_muestra_las_relaciones(): void
    {
        $this->actingAs($this->administrador)
            ->get('/admin/innovaciones')
            ->assertInertia(fn (Assert $pagina) => $pagina
                ->where('innovaciones.data.0.usuario.nombre', fn (string $nombre) => filled($nombre))
                ->where('innovaciones.data.0.categoria.nombre', fn (string $nombre) => filled($nombre))
                ->has('innovaciones.data.0.etiquetas', 3)
                ->where('innovaciones.data.0.etiquetas.0.nombre', fn (string $nombre) => filled($nombre)));
    }

    public function test_los_listados_muestran_informacion_descriptiva_de_las_relaciones(): void
    {
        $aserciones = [
            ['/admin/usuarios', 'usuarios.data.0.rol.nombre'],
            ['/admin/cuentas-sociales', 'cuentasSociales.data.0.usuario.nombre'],
            ['/admin/hitos-financieros', 'hitosFinancieros.data.0.innovacion.titulo'],
            ['/admin/lista-deseos', 'listaDeseos.data.0.usuario.nombre'],
            ['/admin/lista-deseos', 'listaDeseos.data.0.innovacion.titulo'],
            ['/admin/carritos', 'carritos.data.0.usuario.nombre'],
            ['/admin/carrito-detalles', 'carritoDetalles.data.0.carrito.usuario.nombre'],
            ['/admin/carrito-detalles', 'carritoDetalles.data.0.innovacion.titulo'],
            ['/admin/contribuciones', 'contribuciones.data.0.usuario.nombre'],
            ['/admin/contribuciones', 'contribuciones.data.0.innovacion.titulo'],
            ['/admin/comentarios', 'comentarios.data.0.usuario.nombre'],
            ['/admin/comentarios', 'comentarios.data.0.innovacion.titulo'],
            ['/admin/notificaciones', 'notificaciones.data.0.usuario.nombre'],
            ['/admin/logs-sistema', 'logsSistema.data.0.usuario.nombre'],
        ];

        foreach ($aserciones as [$ruta, $propiedad]) {
            $this->actingAs($this->administrador)
                ->get($ruta)
                ->assertOk()
                ->assertInertia(fn (Assert $pagina) => $pagina
                    ->where($propiedad, fn (string $valor) => filled($valor)));
        }
    }

    public function test_la_paginacion_permite_navegar_entre_paginas(): void
    {
        $this->actingAs($this->administrador)
            ->get('/admin/innovaciones?page=3')
            ->assertOk()
            ->assertInertia(fn (Assert $pagina) => $pagina
                ->where('innovaciones.current_page', 3)
                ->where('innovaciones.last_page', 3)
                ->has('innovaciones.data', 5)
                ->where('innovaciones.next_page_url', null)
                ->where('innovaciones.prev_page_url', fn (?string $url) => filled($url)));

        $this->actingAs($this->administrador)
            ->get('/admin/carritos?page=2')
            ->assertOk()
            ->assertInertia(fn (Assert $pagina) => $pagina
                ->where('carritos.current_page', 2)
                ->has('carritos.data', 2));
    }
}
