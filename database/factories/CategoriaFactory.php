<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categoria>
 */
class CategoriaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->randomElement([
                'Tecnología',
                'Salud',
                'Educación',
                'Medio Ambiente',
                'Energía',
                'Agricultura',
                'Transporte',
                'Vivienda',
                'Comunicaciones',
                'Entretenimiento',
                'Deportes',
                'Gastronomía',
                'Moda',
                'Turismo',
                'Finanzas',
                'Seguridad',
            ]),
            'descripcion' => fake()->sentence(),
            'icono' => fake()->randomElement([
                'bi-lightbulb',
                'bi-rocket-takeoff',
                'bi-heart-pulse',
                'bi-book',
                'bi-tree',
                'bi-lightning-charge',
                'bi-people',
                'bi-globe',
            ]),
            'estado' => fake()->randomElement(['activa', 'activa', 'activa', 'inactiva']),
        ];
    }
}
