<?php

namespace Database\Factories;

use App\Models\CuentaSocial;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CuentaSocial>
 */
class CuentaSocialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'proveedor' => fake()->randomElement(['google', 'facebook', 'github', 'twitter', 'linkedin']),
            'id_proveedor' => fake()->unique()->numerify('##########'),
            'token' => fake()->optional()->sha256(),
            'refresh_token' => fake()->optional()->sha256(),
            'expira_en' => fake()->optional()->dateTimeInInterval('+1 week', '+1 year'),
        ];
    }
}
