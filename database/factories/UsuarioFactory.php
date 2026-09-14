<?php

namespace Database\Factories;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rol_id' => Rol::factory(),
            'nombre' => fake()->firstName(),
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->optional()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'telefono' => fake()->unique()->numerify('55########'),
            'foto_perfil' => null,
            'estado' => fake()->randomElement(['activo', 'activo', 'activo', 'inactivo', 'suspendido']),
        ];
    }
}
