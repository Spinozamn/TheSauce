<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Innovacion;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Innovacion>
 */
class InnovacionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'categoria_id' => Categoria::factory(),
            'titulo' => fake()->unique()->sentence(3),
            'descripcion' => fake()->paragraph(3, true),
            'imagen_portada' => null,
            'meta_financiera' => fake()->randomFloat(2, 10000, 500000),
            'monto_recaudado' => fn (array $atributos) => round($atributos['meta_financiera'] * fake()->randomFloat(2, 0.05, 0.95), 2),
            'estado' => fake()->randomElement(['borrador', 'activo', 'activo', 'financiado', 'cancelado']),
            'fecha_inicio' => fn () => Carbon::now()->subDays(fake()->numberBetween(30, 120)),
            'fecha_fin' => fn (array $atributos) => Carbon::parse($atributos['fecha_inicio'])->addDays(fake()->numberBetween(30, 180)),
        ];
    }
}
