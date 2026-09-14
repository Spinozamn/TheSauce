<?php

namespace Database\Factories;

use App\Models\Notificacion;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notificacion>
 */
class NotificacionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'titulo' => fake()->sentence(3),
            'mensaje' => fake()->paragraph(1, true),
            'tipo' => fake()->randomElement(['info', 'exito', 'exito', 'advertencia', 'error']),
            'entidad_relacionada' => fake()->optional(0.2)->randomElement(['innovacion', 'contribucion', 'comentario', 'usuario']),
            'entidad_id' => fn () => fake()->numberBetween(1, 25),
            'url_accion' => fn (array $atributos) => $atributos['entidad_relacionada'] !== null
                ? '/'.$atributos['entidad_relacionada'].'s/'.$atributos['entidad_id']
                : null,
            'leido' => fn () => fake()->boolean(60),
            'fecha_leido' => fn (array $atributos) => $atributos['leido']
                ? Carbon::now()->subHours(fake()->numberBetween(1, 48))
                : null,
            'fecha_notificacion' => fn () => Carbon::now()->subHours(fake()->numberBetween(1, 100)),
        ];
    }
}
