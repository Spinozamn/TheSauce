<?php

namespace Tests\Feature\Auth;

use App\Models\Administrador;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UnifiedLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrador_puede_iniciar_sesion_con_usuario_y_redirige_a_panel(): void
    {
        $admin = Administrador::create([
            'nombre' => 'Admin Test',
            'usuario' => 'admin_test',
            'email' => 'admintest@thesauce.test',
            'password' => Hash::make('Secret123#'),
            'activo' => true,
            'nivel' => 3,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin_test',
            'password' => 'Secret123#',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_administrador_puede_iniciar_sesion_con_correo_y_redirige_a_panel(): void
    {
        $admin = Administrador::create([
            'nombre' => 'Admin Test 2',
            'usuario' => 'admin_test2',
            'email' => 'admintest2@thesauce.test',
            'password' => Hash::make('Secret123#'),
            'activo' => true,
            'nivel' => 3,
        ]);

        $response = $this->post('/login', [
            'email' => 'admintest2@thesauce.test',
            'password' => 'Secret123#',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_usuario_regular_puede_iniciar_sesion_y_redirige_a_panel_usuario(): void
    {
        $user = User::create([
            'name' => 'Usuario Normal',
            'email' => 'usuario@thesauce.test',
            'password' => Hash::make('Secret123#'),
        ]);

        $response = $this->post('/login', [
            'email' => 'usuario@thesauce.test',
            'password' => 'Secret123#',
        ]);

        $this->assertAuthenticatedAs($user, 'web');
        $response->assertRedirect('/dashboard');
    }

    public function test_usuario_regular_puede_iniciar_sesion_con_nombre_de_usuario_y_redirige_a_panel_usuario(): void
    {
        $user = User::create([
            'name' => 'usuarionormal',
            'email' => 'usuario2@thesauce.test',
            'password' => Hash::make('Secret123#'),
        ]);

        $response = $this->post('/login', [
            'email' => 'usuarionormal',
            'password' => 'Secret123#',
        ]);

        $this->assertAuthenticatedAs($user, 'web');
        $response->assertRedirect('/dashboard');
    }

    public function test_credenciales_invalidas_fallan_la_validacion(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'inexistente@thesauce.test',
            'password' => 'PasswordInvalido#',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest('web');
        $this->assertGuest('admin');
    }
}
