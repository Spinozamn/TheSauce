<?php
namespace Database\Factories;
use App\Models\Comentario;
use App\Models\Usuario;
use App\Models\Innovacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComentarioFactory extends Factory
{
    protected $model = Comentario::class;
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'innovacion_id' => Innovacion::factory(),
            'comentario_padre_id' => null, // Lo manejaremos en el seeder para las respuestas
            'contenido' => fake()->paragraph(),
            'es_respuesta' => false,
        ];
    }
}