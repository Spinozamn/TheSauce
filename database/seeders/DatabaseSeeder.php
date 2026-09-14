<?php

namespace Database\Seeders;

use App\Models\Carrito;
use App\Models\CarritoDetalle;
use App\Models\Categoria;
use App\Models\Comentario;
use App\Models\Contribucion;
use App\Models\CuentaSocial;
use App\Models\Etiqueta;
use App\Models\HitoFinanciero;
use App\Models\Innovacion;
use App\Models\ListaDeseo;
use App\Models\LogSistema;
use App\Models\Notificacion;
use App\Models\Rol;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $roles = Rol::factory(12)->create();
        $categorias = Categoria::factory(12)->create();
        $etiquetas = Etiqueta::factory(12)->create();
        $usuarios = Usuario::factory(20)->recycle($roles)->create();
        $innovaciones = Innovacion::factory(25)->recycle($usuarios)->recycle($categorias)->create();

        $proveedores = ['google', 'facebook', 'github', 'twitter', 'linkedin'];
        $usuarios->take(15)->each(function ($usuario, $indice) use ($proveedores) {
            CuentaSocial::factory()->create([
                'usuario_id' => $usuario->id,
                'proveedor' => $proveedores[$indice % count($proveedores)],
            ]);
        });

        $innovaciones->each(function ($innovacion) use ($etiquetas) {
            $innovacion->etiquetas()->attach($etiquetas->random(3)->pluck('id'));
        });

        HitoFinanciero::factory(30)->recycle($innovaciones)->create();

        $usuarios->zip($innovaciones->take(20)->shuffle())->each(function ($par) {
            ListaDeseo::factory()->create([
                'usuario_id' => $par[0]->id,
                'innovacion_id' => $par[1]->id,
            ]);
        });

        $carritos = $usuarios->take(12)->map(function ($usuario) {
            return Carrito::factory()->create(['usuario_id' => $usuario->id]);
        });

        $carritos->each(function ($carrito) use ($innovaciones) {
            $innovaciones->random(2)->each(function ($innovacion) use ($carrito) {
                CarritoDetalle::factory()->create([
                    'carrito_id' => $carrito->id,
                    'innovacion_id' => $innovacion->id,
                ]);
            });
        });

        Contribucion::factory(30)->recycle($usuarios)->recycle($innovaciones)->create();

        $comentariosRaiz = Comentario::factory(18)->recycle($usuarios)->recycle($innovaciones)->create();
        Comentario::factory(7)->recycle($usuarios)->recycle($innovaciones)->create([
            'comentario_padre_id' => fn () => $comentariosRaiz->random()->id,
            'es_respuesta' => true,
        ]);

        Notificacion::factory(20)->recycle($usuarios)->create();
        LogSistema::factory(18)->recycle($usuarios)->create();
        LogSistema::factory(2)->create(['usuario_id' => null]);
    }
}
