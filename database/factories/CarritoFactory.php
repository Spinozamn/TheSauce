<?php
namespace Database\Factories;
use App\Models\Carrito;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarritoFactory extends Factory
{
    protected $model = Carrito::class;
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'estado' => fake()->randomElement(['activo', 'finalizado', 'abandonado']),
        ];
    }
}