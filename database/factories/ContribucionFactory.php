<?php
namespace Database\Factories;
use App\Models\Contribucion;
use App\Models\Usuario;
use App\Models\Innovacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContribucionFactory extends Factory
{
    protected $model = Contribucion::class;
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'innovacion_id' => Innovacion::factory(),
            'monto' => fake()->randomFloat(2, 50, 10000),
            'metodo_pago' => fake()->randomElement(['tarjeta', 'paypal', 'transferencia', 'oxxo']),
            'estado' => fake()->randomElement(['pendiente', 'aprobada', 'rechazada', 'reembolsada']),
            'referencia_transaccion' => 'TXN-' . strtoupper(fake()->bothify('??###??')),
        ];
    }
}