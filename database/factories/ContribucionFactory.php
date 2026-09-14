<?php

namespace Database\Factories;

use App\Models\Contribucion;
use App\Models\Innovacion;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contribucion>
 */
class ContribucionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'innovacion_id' => Innovacion::factory(),
            'monto' => fake()->randomFloat(2, 50, 20000),
            'metodo_pago' => fake()->randomElement(['tarjeta', 'paypal', 'transferencia', 'oxxo']),
            'estado' => fake()->randomElement(['pendiente', 'aprobada', 'aprobada', 'aprobada', 'rechazada', 'reembolsada']),
            'referencia_transaccion' => fake()->bothify('TXN-????-#####'),
            'fecha_contribucion' => fn () => Carbon::now()->subDays(fake()->numberBetween(0, 90)),
        ];
    }
}
