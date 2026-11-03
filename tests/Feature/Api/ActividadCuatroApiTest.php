<?php

namespace Tests\Feature\Api;

use App\Models\Categoria;
use App\Models\Contribucion;
use App\Models\Innovacion;
use App\Models\Rol;
use App\Models\User;
use App\Models\Usuario;
use Database\Seeders\AdministradorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActividadCuatroApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdministradorSeeder::class);

        $rol = Rol::create([
            'nombre'      => 'Colaborador',
            'descripcion' => 'Perfil base de cliente',
        ]);

        $categoria = Categoria::create([
            'nombre'      => 'Energia Renovable',
            'descripcion' => 'Iniciativas ecologicas',
            'icono'       => 'zap',
            'estado'      => 'activa',
        ]);

        $creador = Usuario::create([
            'rol_id'           => $rol->id,
            'nombre'           => 'Creador Demo',
            'apellido_paterno' => 'Sauce',
            'email'            => 'creador@thesauce.test',
            'password'         => 'password',
            'estado'           => 'activo',
        ]);

        Innovacion::create([
            'usuario_id'      => $creador->id,
            'categoria_id'    => $categoria->id,
            'titulo'          => 'Turbina Eolica Urbana',
            'descripcion'     => 'Generador eolico de eje vertical para techos de viviendas comunitarias.',
            'meta_financiera' => 50000.00,
            'monto_recaudado' => 1000.00,
            'estado'          => 'activo',
            'fecha_inicio'    => '2026-11-01',
            'fecha_fin'       => '2026-12-01',
        ]);
    }

    public function test_visitante_puede_consultar_catalogo_publico_sin_autenticacion(): void
    {
        $this->getJson('/api/v1/catalogo/innovaciones')
            ->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);

        $this->getJson('/api/v1/catalogo/categorias')
            ->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_cliente_puede_registrarse_e_iniciar_sesion(): void
    {
        $registroResponse = $this->postJson('/api/v1/cliente/registro', [
            'nombre'                => 'Ana Lopez',
            'email'                 => 'ana.lopez@thesauce.test',
            'password'              => 'SecretPass123#',
            'password_confirmation' => 'SecretPass123#',
        ]);

        $registroResponse->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $loginResponse = $this->postJson('/api/v1/cliente/login', [
            'email'    => 'ana.lopez@thesauce.test',
            'password' => 'SecretPass123#',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertNotNull($loginResponse->json('token'));
    }

    public function test_cliente_puede_consultar_y_actualizar_su_perfil(): void
    {
        $user = User::create([
            'name'     => 'Beatriz Gomez',
            'email'    => 'beatriz@thesauce.test',
            'password' => 'password',
        ]);

        $token = $user->createToken('cliente-token', ['cliente:acceso'])->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/cliente/perfil')
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'cliente' => [
                    'email' => 'beatriz@thesauce.test',
                ],
            ]);

        $this->withToken($token)
            ->patchJson('/api/v1/cliente/perfil', [
                'nombre' => 'Beatriz Gomez Actualizada',
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'cliente' => [
                    'nombre' => 'Beatriz Gomez Actualizada',
                ],
            ]);
    }

    public function test_cliente_puede_contribuir_y_consultar_su_propia_contribucion(): void
    {
        $user = User::create([
            'name'     => 'Carlos Mejia',
            'email'    => 'carlos.mejia@thesauce.test',
            'password' => 'password',
        ]);

        $token = $user->createToken('cliente-token', ['cliente:acceso'])->plainTextToken;
        $innovacion = Innovacion::first();

        $contribucionResponse = $this->withToken($token)
            ->postJson('/api/v1/cliente/contribuciones', [
                'innovacion_id' => $innovacion->id,
                'monto'         => 250.00,
                'metodo_pago'   => 'tarjeta',
            ]);

        $contribucionResponse->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $contribucionId = $contribucionResponse->json('data.id');

        $this->withToken($token)
            ->getJson("/api/v1/cliente/contribuciones/{$contribucionId}")
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_cliente_no_puede_consultar_contribucion_de_otro_cliente(): void
    {
        $userA = User::create([
            'name'     => 'Usuario A',
            'email'    => 'usera@thesauce.test',
            'password' => 'password',
        ]);

        $userB = User::create([
            'name'     => 'Usuario B',
            'email'    => 'userb@thesauce.test',
            'password' => 'password',
        ]);

        $usuarioA = Usuario::create([
            'rol_id'           => 1,
            'nombre'           => 'Usuario A',
            'apellido_paterno' => 'Apellido A',
            'email'            => 'usera@thesauce.test',
            'password'         => 'password',
            'estado'           => 'activo',
        ]);

        $innovacion = Innovacion::first();

        $contribucion = Contribucion::create([
            'usuario_id'         => $usuarioA->id,
            'innovacion_id'      => $innovacion->id,
            'monto'              => 500.00,
            'metodo_pago'        => 'paypal',
            'estado'             => 'aprobada',
            'fecha_contribucion' => now(),
        ]);

        $tokenB = $userB->createToken('cliente-token', ['cliente:acceso'])->plainTextToken;

        $this->withToken($tokenB)
            ->getJson("/api/v1/cliente/contribuciones/{$contribucion->id}")
            ->assertStatus(403);
    }

    public function test_cliente_no_puede_acceder_a_rutas_administrativas(): void
    {
        $user = User::create([
            'name'     => 'Cliente Bloqueado',
            'email'    => 'clientebloqueado@thesauce.test',
            'password' => 'password',
        ]);

        $token = $user->createToken('cliente-token', ['cliente:acceso'])->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/admin/innovaciones')
            ->assertStatus(403);

        $this->withToken($token)
            ->postJson('/api/v1/admin/categorias', [
                'nombre' => 'Categoria No Permitida',
            ])
            ->assertStatus(403);
    }

    public function test_admin_nivel_1_puede_consultar_pero_no_crear_en_area_admin(): void
    {
        $loginResponse = $this->postJson('/api/v1/admin/login', [
            'usuario'  => 'admin_consulta',
            'password' => 'Admin1234#',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');

        $this->withToken($token)
            ->getJson('/api/v1/admin/innovaciones')
            ->assertStatus(200);

        $this->withToken($token)
            ->getJson('/api/v1/admin/categorias')
            ->assertStatus(200);

        $this->withToken($token)
            ->postJson('/api/v1/admin/categorias', [
                'nombre' => 'Robotica Avanzada',
            ])
            ->assertStatus(403);
    }

    public function test_admin_nivel_2_puede_crear_categoria_pero_no_eliminarla(): void
    {
        $loginResponse = $this->postJson('/api/v1/admin/login', [
            'usuario'  => 'admin_editor',
            'password' => 'Admin1234#',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');

        $createResponse = $this->withToken($token)
            ->postJson('/api/v1/admin/categorias', [
                'nombre' => 'Biotecnologia',
            ]);

        $createResponse->assertStatus(201);
        $categoriaId = $createResponse->json('data.id');

        $this->withToken($token)
            ->deleteJson("/api/v1/admin/categorias/{$categoriaId}")
            ->assertStatus(403);
    }

    public function test_admin_nivel_3_puede_eliminar_categoria(): void
    {
        $categoria = Categoria::create([
            'nombre' => 'Categoria Temporal',
            'estado' => 'activa',
        ]);

        $loginResponse = $this->postJson('/api/v1/admin/login', [
            'usuario'  => 'admin_general',
            'password' => 'Admin1234#',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');

        $this->withToken($token)
            ->deleteJson("/api/v1/admin/categorias/{$categoria->id}")
            ->assertStatus(200);
    }

    public function test_peticion_con_datos_invalidos_retorna_422(): void
    {
        $user = User::create([
            'name'     => 'Cliente Validador',
            'email'    => 'validador@thesauce.test',
            'password' => 'password',
        ]);

        $token = $user->createToken('cliente-token', ['cliente:acceso'])->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/v1/cliente/contribuciones', [
                'innovacion_id' => 1,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['monto', 'metodo_pago']);
    }

    public function test_recurso_inexistente_retorna_404(): void
    {
        $this->getJson('/api/v1/catalogo/innovaciones/999999')
            ->assertStatus(404);
    }
}
