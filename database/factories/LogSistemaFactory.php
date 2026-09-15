<?php
namespace Database\Factories;
use App\Models\LogSistema;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogSistemaFactory extends Factory
{
    protected $model = LogSistema::class;
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'accion' => fake()->randomElement(['login', 'logout', 'create', 'update', 'delete']),
            'entidad_afectada' => fake()->randomElement(['innovacion', 'usuario', 'comentario', 'contribucion']),
            'entidad_id' => fake()->numberBetween(1, 50),
            'detalles' => json_encode(['info' => fake()->sentence()]),
            'ip_origen' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}