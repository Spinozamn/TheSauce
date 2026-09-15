<?php
namespace Database\Factories;
use App\Models\Innovacion;
use App\Models\Usuario;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class InnovacionFactory extends Factory
{
    protected $model = Innovacion::class;
    public function definition(): array
    {
        $meta = fake()->randomFloat(2, 10000, 500000);
        return [
            'usuario_id' => Usuario::factory(),
            'categoria_id' => Categoria::factory(),
            'titulo' => fake()->sentence(4),
            'descripcion' => fake()->paragraph(),
            'meta_financiera' => $meta,
            'monto_recaudado' => fake()->randomFloat(2, 0, $meta),
            'estado' => fake()->randomElement(['borrador', 'activo', 'financiado', 'cancelado']),
            'fecha_inicio' => fake()->dateTimeBetween('-1 month', 'now'),
            'fecha_fin' => fake()->dateTimeBetween('now', '+6 months'),
        ];
    }
}