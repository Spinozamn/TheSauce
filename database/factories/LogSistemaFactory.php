<?php

namespace Database\Factories;

use App\Models\LogSistema;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LogSistema>
 */
class LogSistemaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'accion' => fake()->randomElement([
                'inicio_sesion',
                'registro_usuario',
                'creacion_innovacion',
                'actualizacion_perfil',
                'publicacion_comentario',
                'registro_contribucion',
            ]),
            'entidad_afectada' => fake()->optional(0.2)->randomElement(['usuarios', 'innovaciones', 'contribuciones', 'comentarios']),
            'entidad_id' => fn () => fake()->numberBetween(1, 25),
            'detalles' => fn () => [
                'resultado' => fake()->randomElement(['exito', 'exito', 'error']),
                'tiempo_respuesta_ms' => fake()->numberBetween(20, 900),
            ],
            'ip_origen' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'fecha_registro' => fn () => Carbon::now()->subHours(fake()->numberBetween(1, 200)),
        ];
    }
}
