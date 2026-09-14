<?php

namespace Database\Factories;

use App\Models\Carrito;
use App\Models\CarritoDetalle;
use App\Models\Innovacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CarritoDetalle>
 */
class CarritoDetalleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'carrito_id' => Carrito::factory(),
            'innovacion_id' => Innovacion::factory(),
            'monto_comprometido' => fake()->randomFloat(2, 100, 5000),
            'mensaje_apoyo' => fake()->optional()->sentence(),
            'recompensa_seleccionada' => fake()->randomElement(['ninguna', 'basica', 'intermedia', 'premium']),
        ];
    }
}
