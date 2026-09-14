<?php

namespace Database\Factories;

use App\Models\HitoFinanciero;
use App\Models\Innovacion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HitoFinanciero>
 */
class HitoFinancieroFactory extends Factory
{
    public function definition(): array
    {
        return [
            'innovacion_id' => Innovacion::factory(),
            'titulo' => fake()->sentence(4),
            'descripcion' => fake()->paragraph(2, true),
            'monto_objetivo' => fake()->randomFloat(2, 5000, 200000),
            'fecha_limite' => fn () => Carbon::now()->addDays(fake()->numberBetween(10, 180)),
            'estado' => fake()->randomElement(['pendiente', 'pendiente', 'cumplido', 'vencido']),
        ];
    }
}
