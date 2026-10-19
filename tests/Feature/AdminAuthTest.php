<?php

namespace Tests\Feature;

use App\Models\Administrador;
use Database\Seeders\AdministradorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AdministradorSeeder::class);
    }

    public function test_formulario_de_login_se_muestra_correctamente(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('TheSauce');
        $response->assertSee('Nombre de Usuario');
    }

    public function test_administrador_activo_puede_iniciar_sesion(): void
    {
        $response = $this->post('/admin/login', [
            'usuario'  => 'admin_general',
            'password' => 'Admin1234#',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs(
            Administrador::where('usuario', 'admin_general')->first(),
            'admin'
        );
    }

    public function test_administrador_inactivo_no_puede_iniciar_sesion(): void
    {
        $response = $this->from('/admin/login')->post('/admin/login', [
            'usuario'  => 'admin_bloqueado',
            'password' => 'Admin1234#',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors('usuario');
        $this->assertGuest('admin');
    }

    public function test_credenciales_incorrectas_son_rechazadas(): void
    {
        $response = $this->from('/admin/login')->post('/admin/login', [
            'usuario'  => 'admin_general',
            'password' => 'password_incorrecto',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors('usuario');
        $this->assertGuest('admin');
    }

    public function test_visitante_no_autenticado_es_redirigido_al_login_admin(): void
    {
        $response = $this->get('/admin/innovaciones');

        $response->assertRedirect('/admin/login');
    }

    public function test_administrador_puede_cerrar_sesion(): void
    {
        $admin = Administrador::where('usuario', 'admin_general')->first();

        $response = $this->actingAs($admin, 'admin')->post('/admin/logout');

        $response->assertRedirect('/admin/login');
        $this->assertGuest('admin');
    }

    public function test_administrador_autenticado_puede_acceder_al_panel(): void
    {
        $admin = Administrador::where('usuario', 'admin_general')->first();

        $response = $this->actingAs($admin, 'admin')->get('/admin');

        $response->assertStatus(200);
    }

    public function test_cierre_de_sesion_desde_inertia_fuerza_redireccion_externa(): void
    {
        $admin = Administrador::where('usuario', 'admin_general')->first();

        $response = $this->actingAs($admin, 'admin')
            ->withHeader('X-Inertia', 'true')
            ->post('/admin/logout');

        $response->assertStatus(409);
        $response->assertHeader('X-Inertia-Location', route('admin.login'));
        $this->assertGuest('admin');
    }
}
