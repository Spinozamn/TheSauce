<?php
namespace Database\Factories;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'rol_id' => Rol::factory(),
            'nombre' => fake()->firstName(),
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'telefono' => fake()->numerify('##########'),
            'estado' => fake()->randomElement(['activo', 'inactivo', 'suspendido']),
        ];
    }
}