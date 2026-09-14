<?php

namespace Database\Factories;

use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rol>
 */
class RolFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->randomElement([
                'Administrador',
                'Moderador',
                'Creador de Contenido',
                'Inversionista',
                'Patrocinador',
                'Revisor',
                'Editor',
                'Analista',
                'Soporte Técnico',
                'Community Manager',
                'Mentor',
                'Evaluador',
                'Promotor',
                'Observador',
            ]),
            'descripcion' => fake()->sentence(),
        ];
    }
}
