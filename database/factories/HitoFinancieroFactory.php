<?php
namespace Database\Factories;
use App\Models\HitoFinanciero;
use App\Models\Innovacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class HitoFinancieroFactory extends Factory
{
    protected $model = HitoFinanciero::class;
    public function definition(): array
    {
        return [
            'innovacion_id' => Innovacion::factory(),
            'titulo' => fake()->sentence(3),
            'descripcion' => fake()->paragraph(),
            'monto_objetivo' => fake()->randomFloat(2, 1000, 50000),
            'fecha_limite' => fake()->dateTimeBetween('now', '+1 year'),
            'estado' => fake()->randomElement(['pendiente', 'cumplido', 'vencido']),
        ];
    }
}