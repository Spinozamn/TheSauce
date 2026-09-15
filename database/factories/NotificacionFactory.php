<?php
namespace Database\Factories;
use App\Models\Notificacion;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificacionFactory extends Factory
{
    protected $model = Notificacion::class;
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'titulo' => fake()->sentence(4),
            'mensaje' => fake()->paragraph(),
            'tipo' => fake()->randomElement(['info', 'exito', 'advertencia', 'error']),
            'entidad_relacionada' => fake()->randomElement(['innovacion', 'contribucion', 'comentario']),
            'entidad_id' => fake()->numberBetween(1, 50),
            'url_accion' => '/detalle/' . fake()->numberBetween(1, 50),
            'leido' => fake()->boolean(),
        ];
    }
}