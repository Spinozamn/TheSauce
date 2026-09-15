<?php
namespace Database\Factories;
use App\Models\CuentaSocial;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class CuentaSocialFactory extends Factory
{
    protected $model = CuentaSocial::class;
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'proveedor' => fake()->randomElement(['github', 'google', 'twitter']),
            'id_proveedor' => fake()->uuid(),
            'token' => fake()->sha256(),
            'refresh_token' => fake()->sha256(),
            'expira_en' => fake()->dateTimeBetween('+1 day', '+1 year'),
        ];
    }
}