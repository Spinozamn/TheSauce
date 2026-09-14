<?php

namespace Database\Factories;

use App\Models\Comentario;
use App\Models\Innovacion;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comentario>
 */
class ComentarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'innovacion_id' => Innovacion::factory(),
            'comentario_padre_id' => null,
            'contenido' => fake()->paragraph(2, true),
            'es_respuesta' => false,
            'fecha_comentario' => fn () => Carbon::now()->subDays(fake()->numberBetween(0, 30)),
        ];
    }
}
