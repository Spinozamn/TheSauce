<?php

namespace Database\Factories;

use App\Models\Etiqueta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Etiqueta>
 */
class EtiquetaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->randomElement([
                'IA',
                'IoT',
                'Sustentable',
                'Reciclaje',
                'Energía Solar',
                'Movilidad',
                'Salud Digital',
                'Finanzas',
                'Robótica',
                'Big Data',
                'Blockchain',
                'Código Abierto',
                'Inclusión',
                'Comunidad',
                'Biotecnología',
                'Automatización',
                'AgroTech',
                'Gamificación',
            ]),
            'color' => fake()->hexColor(),
        ];
    }
}
