<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\Categoria;
use App\Models\Innovacion;
use App\Models\Usuario;
use Database\Seeders\AdministradorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AdministradorSeeder::class);
    }

    private function crearInnovacion(): Innovacion
    {
        $rol = \App\Models\Rol::create([
            'nombre'      => 'General',
            'descripcion' => 'Rol general',
        ]);

        $usuario = Usuario::create([
            'rol_id'           => $rol->id,
            'nombre'           => 'Juan',
            'apellido_paterno' => 'Perez',
            'email'            => 'juan@thesauce.test',
            'password'         => bcrypt('password'),
        ]);

        $categoria = Categoria::create([
            'nombre'      => 'Tecnología',
            'descripcion' => 'Proyectos tecnológicos',
        ]);

        return Innovacion::create([
            'usuario_id'      => $usuario->id,
            'categoria_id'    => $categoria->id,
            'titulo'          => 'Proyecto de prueba',
            'descripcion'     => 'Descripción detallada para la prueba de autorización',
            'meta_financiera' => 50000.00,
            'monto_recaudado' => 0.00,
            'estado'          => 'borrador',
            'fecha_inicio'    => now()->format('Y-m-d'),
            'fecha_fin'       => now()->addMonths(2)->format('Y-m-d'),
        ]);
    }

    public function test_administrador_nivel_1_puede_consultar_pero_no_crear_editar_ni_eliminar(): void
    {
        $admin = Administrador::where('usuario', 'admin_consulta')->first();
        $innovacion = $this->crearInnovacion();

        $this->actingAs($admin, 'admin')
            ->get('/admin/innovaciones')
            ->assertStatus(200);

        $this->actingAs($admin, 'admin')
            ->get('/admin/innovaciones/crear')
            ->assertStatus(403);

        $this->actingAs($admin, 'admin')
            ->post('/admin/innovaciones', [
                'titulo' => 'Nuevo proyecto',
            ])
            ->assertStatus(403);

        $this->actingAs($admin, 'admin')
            ->get("/admin/innovaciones/{$innovacion->id}/edit")
            ->assertStatus(403);

        $this->actingAs($admin, 'admin')
            ->delete("/admin/innovaciones/{$innovacion->id}")
            ->assertStatus(403);
    }

    public function test_administrador_nivel_2_puede_consultar_crear_y_editar_pero_no_eliminar(): void
    {
        $admin = Administrador::where('usuario', 'admin_editor')->first();
        $innovacion = $this->crearInnovacion();

        $this->actingAs($admin, 'admin')
            ->get('/admin/innovaciones')
            ->assertStatus(200);

        $this->actingAs($admin, 'admin')
            ->get('/admin/innovaciones/crear')
            ->assertStatus(200);

        $this->actingAs($admin, 'admin')
            ->get("/admin/innovaciones/{$innovacion->id}/edit")
            ->assertStatus(200);

        $this->actingAs($admin, 'admin')
            ->get('/admin/innovaciones/papelera')
            ->assertStatus(403);

        $this->actingAs($admin, 'admin')
            ->delete("/admin/innovaciones/{$innovacion->id}")
            ->assertStatus(403);
    }

    public function test_administrador_nivel_3_tiene_acceso_total_a_todas_las_operaciones(): void
    {
        $admin = Administrador::where('usuario', 'admin_general')->first();
        $innovacion = $this->crearInnovacion();

        $this->actingAs($admin, 'admin')
            ->get('/admin/innovaciones')
            ->assertStatus(200);

        $this->actingAs($admin, 'admin')
            ->get('/admin/innovaciones/crear')
            ->assertStatus(200);

        $this->actingAs($admin, 'admin')
            ->get("/admin/innovaciones/{$innovacion->id}/edit")
            ->assertStatus(200);

        $this->actingAs($admin, 'admin')
            ->get('/admin/innovaciones/papelera')
            ->assertStatus(200);

        $this->actingAs($admin, 'admin')
            ->delete("/admin/innovaciones/{$innovacion->id}")
            ->assertRedirect(route('admin.innovaciones.index'));

        $this->assertSoftDeleted('innovaciones', [
            'id' => $innovacion->id,
        ]);
    }

    public function test_portal_blade_muestra_opciones_segun_las_directivas_de_autorizacion(): void
    {
        $admin1 = Administrador::where('usuario', 'admin_consulta')->first();
        $response1 = $this->actingAs($admin1, 'admin')->get('/admin/portal');
        $response1->assertStatus(200);
        $response1->assertSee('Administrador Consulta (Nivel 1)');
        $response1->assertSee('No Autorizado');
        $response1->assertSee('Restringido');

        $admin2 = Administrador::where('usuario', 'admin_editor')->first();
        $response2 = $this->actingAs($admin2, 'admin')->get('/admin/portal');
        $response2->assertStatus(200);
        $response2->assertSee('Administrador Editor (Nivel 2)');
        $response2->assertSee('Nueva Innovación');
        $response2->assertSee('Restringido');

        $admin3 = Administrador::where('usuario', 'admin_general')->first();
        $response3 = $this->actingAs($admin3, 'admin')->get('/admin/portal');
        $response3->assertStatus(200);
        $response3->assertSee('Superadministrador (Nivel 3)');
        $response3->assertSee('Nueva Innovación');
        $response3->assertSee('Gestionar Papelera');
        $response3->assertSee('Privilegios Activos');
    }
}
