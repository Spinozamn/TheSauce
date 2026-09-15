<?php
namespace Database\Factories;
use App\Models\CarritoDetalle;
use App\Models\Carrito;
use App\Models\Innovacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarritoDetalleFactory extends Factory
{
    protected $model = CarritoDetalle::class;
    public function definition(): array
    {
        return [
            'carrito_id' => Carrito::factory(),
            'innovacion_id' => Innovacion::factory(),
            'monto_comprometido' => fake()->randomFloat(2, 100, 5000),
            'mensaje_apoyo' => fake()->sentence(),
            'recompensa_seleccionada' => fake()->randomElement(['ninguna', 'basica', 'intermedia', 'premium']),
        ];
    }
}