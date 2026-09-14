<?php

namespace Database\Factories;

use App\Models\Carrito;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Carrito>
 */
class CarritoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'estado' => fake()->randomElement(['activo', 'activo', 'finalizado', 'abandonado']),
        ];
    }
}
