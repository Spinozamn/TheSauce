<?php

namespace Tests\Feature\Api;

use App\Models\Categoria;
use App\Models\Rol;
use App\Models\User;
use App\Models\Usuario;
use Database\Seeders\AdministradorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SanctumApiAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdministradorSeeder::class);

        User::factory()->create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        Categoria::create([
            'nombre'      => 'Tecnologia',
            'descripcion' => 'Iniciativas de desarrollo tecnologico.',
        ]);

        $rol = Rol::create([
            'nombre'      => 'Colaborador',
            'descripcion' => 'Perfil base',
        ]);

        Usuario::create([
            'rol_id'           => $rol->id,
            'nombre'           => 'Carlos',
            'apellido_paterno' => 'Mendoza',
            'email'            => 'carlos.mendoza@thesauce.test',
            'password'         => 'Secret123#',
            'estado'           => 'activo',
        ]);
    }

    public function test_login_con_credenciales_invalidas_retorna_401(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'usuario'  => 'admin_consulta',
            'password' => 'PasswordIncorrecta123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_admin_inactivo_no_puede_iniciar_sesion_retorna_403(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'usuario'  => 'admin_bloqueado',
            'password' => 'Admin1234#',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_admin_consulta_nivel_1_puede_consultar_pero_no_crear_ni_eliminar(): void
    {
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'usuario'  => 'admin_consulta',
            'password' => 'Admin1234#',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');

        $this->withToken($token)
            ->getJson('/api/v1/innovaciones')
            ->assertStatus(200);

        $this->withToken($token)
            ->postJson('/api/v1/innovaciones', [
                'titulo'          => 'Purificador Solar Modular',
                'categoria_id'    => 1,
                'descripcion'     => 'Sistema de potabilizacion fototermica para comunidades de escasos recursos.',
                'meta_financiera' => 50000,
                'fecha_inicio'    => '2026-11-01',
                'fecha_fin'       => '2026-12-01',
            ])
            ->assertStatus(403);

        $this->withToken($token)
            ->deleteJson('/api/v1/innovaciones/1')
            ->assertStatus(403);
    }

    public function test_admin_editor_nivel_2_puede_crear_y_editar_pero_no_eliminar(): void
    {
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'usuario'  => 'admin_editor',
            'password' => 'Admin1234#',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');

        $storeResponse = $this->withToken($token)
            ->postJson('/api/v1/innovaciones', [
                'titulo'          => 'Sensor Inteligente de Riego',
                'categoria_id'    => 1,
                'descripcion'     => 'Monitoreo de humedad en suelo agricola mediante telemetria de bajo consumo.',
                'meta_financiera' => 60000,
                'fecha_inicio'    => '2026-11-01',
                'fecha_fin'       => '2026-12-01',
            ]);

        $storeResponse->assertStatus(201);
        $id = $storeResponse->json('data.id');

        $this->withToken($token)
            ->putJson("/api/v1/innovaciones/{$id}", [
                'titulo' => 'Sensor Inteligente de Riego v2',
            ])
            ->assertStatus(200);

        $this->withToken($token)
            ->deleteJson("/api/v1/innovaciones/{$id}")
            ->assertStatus(403);
    }

    public function test_admin_general_nivel_3_puede_eliminar_y_acceder_a_papelera(): void
    {
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'usuario'  => 'admin_general',
            'password' => 'Admin1234#',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');

        $storeResponse = $this->withToken($token)
            ->postJson('/api/v1/innovaciones', [
                'titulo'          => 'Bicicletas Urbanas Compartidas',
                'categoria_id'    => 1,
                'descripcion'     => 'Estaciones de transporte compartido no motorizado en corredores estudiantiles.',
                'meta_financiera' => 80000,
                'fecha_inicio'    => '2026-11-01',
                'fecha_fin'       => '2026-12-01',
            ]);

        $storeResponse->assertStatus(201);
        $id = $storeResponse->json('data.id');

        $this->withToken($token)
            ->deleteJson("/api/v1/innovaciones/{$id}")
            ->assertStatus(200);

        $this->withToken($token)
            ->getJson('/api/v1/innovaciones/papelera')
            ->assertStatus(200);

        $this->withToken($token)
            ->postJson("/api/v1/innovaciones/{$id}/restaurar")
            ->assertStatus(200);
    }

    public function test_usuario_regular_puede_autenticarse_pero_no_acceder_a_rutas_administrativas(): void
    {
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');

        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'usuario' => [
                    'tipo' => 'usuario',
                ],
            ]);

        $this->withToken($token)
            ->getJson('/api/v1/innovaciones')
            ->assertStatus(403);
    }

    public function test_logout_revoca_el_token_sanctum(): void
    {
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'usuario'  => 'admin_consulta',
            'password' => 'Admin1234#',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');

        $this->withToken($token)
            ->postJson('/api/v1/auth/logout')
            ->assertStatus(200);

        Auth::forgetGuards();
        $this->flushHeaders();

        $this->withToken($token)
            ->getJson('/api/v1/innovaciones')
            ->assertStatus(401);
    }
}
